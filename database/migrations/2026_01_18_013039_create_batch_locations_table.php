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
        Schema::create('batch_locations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_batch_id')->index()->constrained();

            $table->foreignId('location_id')->index()->constrained();

            $table->integer('remaining_quantity')->default(0);
            $table->unique(['product_batch_id', 'location_id']);

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_locations');
    }
};
