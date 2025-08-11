<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\ItemDetail;
use App\Models\Request as ItemRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestOverlapTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_request_item_with_overlapping_periods()
    {
        $user = User::factory()->create();
        $item = ItemDetail::factory()->create(['stato' => 'disponibile']);
        // Prima richiesta valida
        ItemRequest::create([
            'user_id' => $user->id,
            'item_detail_id' => $item->id,
            'data_inizio' => '2025-08-20',
            'data_fine' => '2025-08-25',
            'stato' => 'in_attesa',
        ]);
        $this->actingAs($user)
            ->post(route('user.requests.store'), [
                'item_detail_id' => $item->id,
                'data_inizio' => '2025-08-22',
                'data_fine' => '2025-08-28',
            ])
            ->assertSessionHasErrors(['data_inizio']);
    }

    public function test_cannot_request_item_for_past_period()
    {
        $user = User::factory()->create();
        $item = ItemDetail::factory()->create(['stato' => 'disponibile']);
        $yesterday = now()->subDay()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');
        $this->actingAs($user)
            ->post(route('user.requests.store'), [
                'item_detail_id' => $item->id,
                'data_inizio' => $yesterday,
                'data_fine' => $tomorrow,
            ])
            ->assertSessionHasErrors(['data_inizio']);
    }
}
