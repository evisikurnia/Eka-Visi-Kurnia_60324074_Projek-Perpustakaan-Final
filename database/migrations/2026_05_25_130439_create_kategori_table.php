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
        Schema::create('kategori', function (Blueprint $table) {
            $table->id(); // bigint, primary key, auto increment
            $table->string('nama_kategori', 50)->unique(); // string(50), unique, not null
            $table->text('deskripsi')->nullable(); // text, nullable
            $table->string('icon', 50)->nullable(); // string(50), nullable
            $table->string('warna', 20)->nullable(); // string(20), nullable
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};