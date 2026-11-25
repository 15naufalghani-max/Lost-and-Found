<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Claim>
 */
class ClaimFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory()->found(),
            'user_id' => User::factory(),
            'answer' => fake()->sentence(8),
            'proof_image' => null,
            'status' => Claim::STATUS_PENDING,
            'reviewed_at' => null,
        ];
    }
}
