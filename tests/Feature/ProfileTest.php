<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test di feature per la gestione del profilo utente.
 *
 * Questi test coprono:
 * - Visualizzazione della pagina profilo
 * - Aggiornamento delle informazioni profilo
 * - Gestione della verifica email
 * - Eliminazione account utente
 * - Validazione password per eliminazione account
 */
class ProfileTest extends TestCase
{
    // Usa il trait per resettare il database ad ogni test, garantendo isolamento e dati puliti.
    use RefreshDatabase;

    /**
     * Verifica che la pagina profilo sia accessibile da un utente autenticato.
     *
     * Scenario:
     * 1. Crea un utente fittizio.
     * 2. Effettua il login come quell'utente.
     * 3. Effettua una GET su /profile.
     * 4. La risposta deve essere OK (200).
     */
    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    /**
     * Verifica che le informazioni del profilo possano essere aggiornate correttamente.
     *
     * Scenario:
     * 1. Crea un utente fittizio.
     * 2. Effettua il login come quell'utente.
     * 3. Esegue una PATCH su /profile con nuovi dati (nome, email).
     * 4. La risposta deve essere senza errori e con redirect su /profile.
     * 5. Dopo il refresh, i dati dell'utente devono essere aggiornati e la verifica email azzerata.
     */
    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        // Verifica che i dati siano stati aggiornati
        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        // La verifica email viene azzerata se l'email cambia
        $this->assertNull($user->email_verified_at);
    }

    /**
     * Verifica che lo stato di verifica email NON cambi se l'email rimane invariata.
     *
     * Scenario:
     * 1. Crea un utente fittizio.
     * 2. Effettua il login come quell'utente.
     * 3. Esegue una PATCH su /profile con lo stesso indirizzo email.
     * 4. La risposta deve essere senza errori e con redirect su /profile.
     * 5. Dopo il refresh, la verifica email deve rimanere presente.
     */
    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        // La verifica email deve rimanere presente
        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    /**
     * Verifica che l'utente possa eliminare il proprio account fornendo la password corretta.
     *
     * Scenario:
     * 1. Crea un utente fittizio.
     * 2. Effettua il login come quell'utente.
     * 3. Esegue una DELETE su /profile con la password corretta.
     * 4. La risposta deve essere senza errori e con redirect alla home.
     * 5. L'utente deve risultare disconnesso e il record eliminato dal database.
     */
    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        // L'utente deve essere disconnesso e il record eliminato
        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    /**
     * Verifica che sia necessario fornire la password corretta per eliminare l'account.
     *
     * Scenario:
     * 1. Crea un utente fittizio.
     * 2. Effettua il login come quell'utente.
     * 3. Esegue una DELETE su /profile con password errata.
     * 4. La risposta deve contenere errore di validazione su 'password' e redirect su /profile.
     * 5. L'utente deve esistere ancora nel database.
     */
    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect('/profile');

        // L'utente non deve essere eliminato
        $this->assertNotNull($user->fresh());
    }
}
