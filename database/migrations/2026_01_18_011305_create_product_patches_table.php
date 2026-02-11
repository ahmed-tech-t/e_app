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
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_code');

            $table->integer('initial_quantity')->default(0);
            $table->integer('remaining_quantity')->default(0);

            $table->foreignId('product_id')->constrained();

            $table->decimal('cost_price', 15, 2);
            $table->decimal('retail_price', 15, 2);
            $table->decimal('wholesale_price', 15, 2);

            $table->softDeletes();

            $table->timestamps();

            $table->unique(['product_id', 'batch_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_patches');
    }
};
