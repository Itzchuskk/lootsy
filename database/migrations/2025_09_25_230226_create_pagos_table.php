<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('method', 50)->nullable();   // 'simulado', 'stripe'
            $table->string('status', 20)->default('paid'); // pending|paid|failed
            $table->string('reference')->nullable();    // id del gateway (ej. pi_... de Stripe)
            $table->json('raw_payload')->nullable();    // respuesta cruda del gateway (opcional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
