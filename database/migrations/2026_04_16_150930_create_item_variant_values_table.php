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
Schema::create('item_variant_values', function (Blueprint $table) {
    $table->id();

    $table->foreignId('item_variant_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->foreignId('variant_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->unique(['item_variant_id', 'variant_id']); 
});
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_variant_values');
    }
};
