<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\ItemDetail;
use App\Models\Request as ItemRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test di feature per la gestione delle richieste di oggetti a magazzino con controllo periodi sovrapposti e validità delle date.

 */
class RequestOverlapTest extends TestCase
{
    // Usa il trait per resettare il database ad ogni test, garantendo isolamento e dati puliti.
    use RefreshDatabase;

    /**
     * Verifica che NON sia possibile richiedere un oggetto per un periodo che si sovrappone a una richiesta già esistente.
     *
     * Scenario:
     * 1. Un utente crea una richiesta valida per un oggetto in un certo periodo.
     * 2. Lo stesso utente prova a richiedere lo stesso oggetto per un periodo che si sovrappone parzialmente.
     * 3. Il sistema deve bloccare la richiesta e restituire un errore di validazione su 'data_inizio'.
     */
    public function test_cannot_request_item_with_overlapping_periods()
    {
        // Crea un utente fittizio
        $user = User::factory()->create();
        // Crea un oggetto disponibile a magazzino
        $item = ItemDetail::factory()->create(['stato' => 'disponibile']);

        // Prima richiesta: periodo 20-25 agosto 2025
        ItemRequest::create([
            'user_id' => $user->id,
            'item_detail_id' => $item->id,
            'data_inizio' => '2025-08-20',
            'data_fine' => '2025-08-25',
            'stato' => 'in_attesa',
        ]);

        // Seconda richiesta: periodo 22-28 agosto 2025 (sovrapposta)
        $this->actingAs($user)
            ->post(route('user.requests.store'), [
                'item_detail_id' => $item->id,
                'data_inizio' => '2025-08-22',
                'data_fine' => '2025-08-28',
            ])
            // Ci aspettiamo un errore di validazione su 'data_inizio' per sovrapposizione
            ->assertSessionHasErrors(['data_inizio']);
    }

    /**
     * Verifica che NON sia possibile richiedere un oggetto per un periodo che inizia nel passato.
     *
     * Scenario:
     * 1. Un utente prova a richiedere un oggetto per un periodo che inizia prima di oggi.
     * 2. Il sistema deve bloccare la richiesta e restituire un errore di validazione su 'data_inizio'.
     */
    public function test_cannot_request_item_for_past_period()
    {
        // Crea un utente fittizio
        $user = User::factory()->create();
        // Crea un oggetto disponibile a magazzino
        $item = ItemDetail::factory()->create(['stato' => 'disponibile']);

        // Calcola le date: ieri e domani rispetto ad oggi
        $yesterday = now()->subDay()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');

        // Prova a richiedere l'oggetto per un periodo che inizia nel passato
        $this->actingAs($user)
            ->post(route('user.requests.store'), [
                'item_detail_id' => $item->id,
                'data_inizio' => $yesterday,
                'data_fine' => $tomorrow,
            ])
            // Ci aspettiamo un errore di validazione su 'data_inizio' per data non valida
            ->assertSessionHasErrors(['data_inizio']);
    }
}
