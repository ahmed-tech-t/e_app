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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_batch_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->decimal('quantity', 15, 2);
            $table->enum('type', ['entry', 'sale', 'transfer_in', 'transfer_out', 'adjust_initial']);
            $table->string('bill_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movement');
    }
};
