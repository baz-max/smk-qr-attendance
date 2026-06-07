<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
    $table->id();
    $table->string('nis')->unique();
    $table->string('nama');
    $table->string('kelas'); // 10 / 11 / 12
    $table->uuid('qr_token')->unique(); // ⬅️ INI PENTING
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
