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
        Schema::create('productos_abono', function (Blueprint $table) {
            $table->id('id_producto');
            $table->unsignedBigInteger('id_usuario');
            $table->string('titulo', 100);
            $table->text('descripcion');
            $table->enum('tipo_abono', ['composta', 'humus', 'abono_organico', 'otro']);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio', 10, 2);
            $table->string('imagen_producto', 255)->nullable();
            $table->enum('estado', ['disponible', 'agotado', 'inactivo'])->default('disponible');
            $table->dateTime('fecha_publicacion')->useCurrent();
            $table->timestamps();

            // Clave foránea
            $table->foreign('id_usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_abono');
    }
};
