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
        Schema::create('uploaded_images', function (Blueprint $table) {
            $table->id();
            $table->string('url', 1024);
            $table->string('cloudflare_id', 255)->nullable()->index();
            $table->string('filename', 255)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploaded_images');
    }
};
