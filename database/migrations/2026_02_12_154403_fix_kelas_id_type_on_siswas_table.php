<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {

            // hapus dulu kolom lama
            $table->dropColumn('kelas_id');
        });

        Schema::table('siswas', function (Blueprint $table) {

            $table->foreignId('kelas_id')
                  ->nullable()
                  ->after('id') // ⬅️ INI SUPAYA DI POSISI KEDUA
                  ->constrained('kelas')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');

            $table->string('kelas_id')->after('id');
        });
    }
};
