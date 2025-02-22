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
        Schema::create('crypto_prices', function (Blueprint $table) {
            $table->id();
            $table->string('pair');
            $table->string('exchange');
            $table->decimal('average_price', 16, 8);
            $table->decimal('price_change', 16, 8);
            $table->enum('change_direction', ['upward', 'downward']);
            $table->timestamps();

            $table->index(['pair', 'exchange']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_prices');
    }
};
