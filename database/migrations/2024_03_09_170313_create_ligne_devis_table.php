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
        Schema::create('ligne_devis', function (Blueprint $table) {
            $table->id();
            $table->string('ligne');
            $table->string('Code_produit');
            $table->string('designation');
            $table->string('quantite');
            $table->decimal('prix_vente')->nullable();
            $table->unsignedBigInteger('id_devis');
            $table->foreign('id_devis')->references('id')->on('devis')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linge_devis');
    }
};