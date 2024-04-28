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
        Schema::create('bon__livraisons', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->date('date');
            $table->unsignedBigInteger('commande_id')->nullable();  // Ajout de ->nullable()
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('restrict');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon__livraisons');
    }
};
