<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationTable extends Migration
{
    public function up()
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->string('reference', 10)->primary();
            $table->unsignedBigInteger('idClient');
            $table->unsignedBigInteger('idTrajet');
            $table->enum('etat', ['Validé', 'Archivé', 'Annulé', 'En attente']);
            $table->timestamps();

            $table->foreign('idClient')->references('idClient')->on('client')->onDelete('cascade');
            $table->foreign('idTrajet')->references('idTrajet')->on('trajet')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservation');
    }
}
