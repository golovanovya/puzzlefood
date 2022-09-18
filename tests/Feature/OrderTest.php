<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateOrder()
    {
        $fullName = $this->faker->name;
        $amount = rand(1, 200000);
        $address = $this->faker->address;
        $response = $this->postJson('/api/orders', [
            'full_name' => $fullName,
            'amount' => $amount,
            'address' => $address,
        ]);
        $response->assertJson(fn ($json) =>
            $json->has('id')
                ->where('full_name', $fullName)
                ->where('amount', $amount)
                ->where('address', $address)
                ->has('updated_at')
        );
        $response->assertStatus(201);
    }

    public function testShowOrder()
    {
        $order = Order::factory()->create();

        $this->assertDatabaseCount('orders', 1);

        $response = $this->getJson("/api/orders/{$order->id}");
        $response->assertJson(fn ($json) =>
            $json->where('id', $order->id)
                ->where('full_name', $order->full_name)
                ->where('amount', $order->amount)
                ->where('address', $order->address)
                ->has('updated_at')
        );
    }

    public function testUpdateOrder()
    {
        $address = $this->faker->address;
        $order = Order::factory()->create();
        $this->assertDatabaseCount('orders', 1);
        $response = $this->putJson("/api/orders/{$order->id}", [
            'address' => $address,
        ]);
        $response->assertStatus(200);
        $response->assertJson(fn ($json) =>
            $json->where('id', $order->id)
                ->where('full_name', $order->full_name)
                ->where('amount', $order->amount)
                ->where('address', $address)
                ->has('updated_at')
        );
        $this->assertDatabaseHas('orders', [
            'address' => $address,
        ]);
    }

    public function testDeleteOrder()
    {
        $order = Order::factory()->create();
        $this->assertDatabaseCount('orders', 1);
        $response = $this->deleteJson("/api/orders/{$order->id}");
        $response->assertStatus(204);
        $this->assertDatabaseCount('orders', 0);
    }

    public function testIndexOrders()
    {
        Order::factory(150)->create();
        $response = $this->getJson('/api/orders');
        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 150)
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(100, 'data');
    }
}
