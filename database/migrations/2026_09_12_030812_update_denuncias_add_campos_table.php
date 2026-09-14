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
        Schema::table('denuncias', function (Blueprint $table) {
            $table->dropColumn('foto');
            $table->foreignId('usuario_id')->nullable()->after('tipo_denuncia_id')->constrained('users')->nullOnDelete();
            $table->string('direccion')->nullable()->after('longitud');
            $table->string('prioridad')->default('moderado')->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('denuncias', function (Blueprint $table) {
            $table->dropConstrainedForeignId('usuario_id');
            $table->dropColumn(['direccion', 'prioridad']);
            $table->string('foto');
        });
    }
};
