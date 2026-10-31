<?php

namespace Database\Factories;

use App\Models\PartnerConnection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PartnerConnection>
 */
class PartnerConnectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider' => PartnerConnection::PROVIDER_HAMA,
            'service_name' => 'ProyectoAPI',
            'base_url' => 'http://127.0.0.1:8000',
            'access_token' => fake()->sha256(),
            'scopes' => ['hamas.read'],
            'capabilities' => [
                ['key' => 'hamas.list', 'allowed' => true],
            ],
            'last_status' => 'connected',
            'records_count' => 0,
            'last_verified_at' => now(),
        ];
    }
}
