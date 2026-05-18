<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatRemovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_route_no_longer_exists(): void
    {
        $user = User::factory()->create();
        $material = Material::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("/chat/{$material->id}", [
            'message' => 'Hello',
        ]);

        $response->assertStatus(404);
    }
}
