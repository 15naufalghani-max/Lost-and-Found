<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(Item::TYPES);

        return [
            'user_id' => User::factory(),
            'title' => fake()->words(3, true),
            'type' => $type,
            'category' => fake()->randomElement(Item::CATEGORIES),
            'location' => fake()->randomElement(Item::LOCATIONS),
            'description' => fake()->paragraph(),
            'image' => null,
            'status' => Item::STATUS_OPEN,
            'event_at' => fake()->dateTimeBetween('-14 days', 'now'),
            'expires_at' => now()->addDays(30),
            'verification_question' => $type === Item::TYPE_FOUND
                ? 'Apa ciri khusus pada barang ini?'
                : null,
        ];
    }

    public function lost(): static
    {
        return $this->state(fn () => [
            'type' => Item::TYPE_LOST,
            'verification_question' => null,
        ]);
    }

    public function found(): static
    {
        return $this->state(fn () => [
            'type' => Item::TYPE_FOUND,
            'verification_question' => 'Sebutkan warna atau isi khas barang ini?',
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn () => [
            'status' => Item::STATUS_RESOLVED,
        ]);
    }
}
