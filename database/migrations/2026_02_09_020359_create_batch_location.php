<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batch_location', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_batch_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->integer('remaining_quantity');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_location');
    }
};
