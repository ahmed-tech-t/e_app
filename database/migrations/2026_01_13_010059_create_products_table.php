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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained();

            $table->string('code')->unique();

            $table->string('original_code')->unique();

            $table->string('name_ar');

            $table->string('name_en')->nullable();

            $table->string('origin')->nullable()->default('China');

            $table->string('description')->nullable();

            $table->string('brand');

            $table->foreignId('sale_unit_id')->constrained();

            $table->integer('units_per_carton')->default(1);

            $table->string('image')->nullable()->default('images/store.png');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
