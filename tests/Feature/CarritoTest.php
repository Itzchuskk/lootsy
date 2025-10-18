<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarritoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_ver_carrito_vacio()
    {
        $res = $this->get('/carrito');
        $res->assertStatus(200)->assertSee('Tu carrito');
    }

    public function test_agregar_producto_crea_item()
    {
        $p = Producto::factory()->create(['precio' => 10.00, 'stock' => 5]);

        $this->post("/carrito/agregar/{$p->id}")
             ->assertRedirect('/carrito');

        $this->get('/carrito')->assertSee($p->nombre);
    }

    public function test_agregar_mismo_producto_incrementa_cantidad()
    {
        $p = Producto::factory()->create(['stock' => 5]);
        $this->post("/carrito/agregar/{$p->id}");
        $this->post("/carrito/agregar/{$p->id}");

        $this->get('/carrito')->assertSee((string)2);
    }

    public function test_no_permite_agregar_sin_stock()
    {
        $p = Producto::factory()->create(['stock' => 0]);
        $this->post("/carrito/agregar/{$p->id}")
             ->assertSessionHas('error');
    }

    public function test_eliminar_disminuye_y_borra_en_cero()
    {
        $p = Producto::factory()->create(['stock' => 5]);
        $this->post("/carrito/agregar/{$p->id}");
        $this->post("/carrito/agregar/{$p->id}");

        $this->delete("/carrito/eliminar/{$p->id}")
             ->assertRedirect('/carrito');

        $this->get('/carrito')->assertSee((string)1);

        $this->delete("/carrito/eliminar/{$p->id}")
             ->assertRedirect('/carrito');

        $this->get('/carrito')->assertDontSee($p->nombre);
    }
}
