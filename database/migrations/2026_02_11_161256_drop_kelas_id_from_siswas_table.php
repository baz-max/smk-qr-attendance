<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {

            // kalau sebelumnya ada foreign key
            $table->dropForeign(['kelas_id']);

            // hapus kolom
            $table->dropColumn('kelas_id');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->unsignedBigInteger('kelas_id')->nullable();
        });
    }
};
