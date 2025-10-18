<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase; // 👈 esto crea las tablas (migraciones) antes de cada test

    public function test_the_application_returns_a_successful_response(): void
    {
        // Opcional: correr migraciones explícitamente si no quieres usar el trait
        // $this->artisan('migrate');

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
