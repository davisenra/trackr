<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PackageEvent>
 */
class PackageEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            'Objeto não entregue - carteiro não atendido',
            'Objeto não localizado no fluxo postal',
            'Objeto saiu para entrega ao destinatário',
            'Objeto entregue ao destinatário',
            'Objeto em trânsito - por favor aguarde',
            'Objeto postado',
            'Informações eletrônicas enviadas para análise da autoridade aduaneira',
        ];

        $status = $this->faker->randomElement($statuses);
        $eventedAt = $this->faker->dateTimeBetween('-1 month', 'now');
        $statusHash = md5($status.$eventedAt->format('Y-m-d H:i:s'));

        return [
            'status' => $status,
            'status_hash' => $statusHash,
            'location' => $this->faker->city(),
            'destination' => $this->faker->boolean() ? $this->faker->city() : null,
            'evented_at' => $eventedAt,
        ];
    }
}
