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
        Schema::create('ubicaciones_abono', function (Blueprint $table) {
            $table->id('id_ubicacion'); // Equivalente a int(11) AI PK
            $table->unsignedBigInteger('id_producto'); // int(11)
            $table->decimal('latitud', 10, 8); // decimal(10,8)
            $table->decimal('longitud', 11, 8); // decimal(11,8)
            $table->text('direccion'); // text
            $table->text('enlace_google_maps'); // text

            // Clave foránea opcional (si necesitas la relación)
            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('productos_abono')
                ->onDelete('cascade');

            $table->timestamps(); // Agrega created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubicaciones_abono');
    }
};
