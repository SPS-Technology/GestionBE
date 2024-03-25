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
        Schema::create('ligneentrercomptes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banques_id');
            $table->foreign('banques_id')->references('id')->on('banques')->onDelete('restrict');

            // $table->unsignedBigInteger('client_id');
            //$table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->unsignedBigInteger('id_facture');
            $table->foreign('id_facture')->references('id')->on('factures')->onDelete('restrict');
            $table->string('avance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligneentrercomptes');
    }
};
