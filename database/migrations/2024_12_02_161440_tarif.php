<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifTable extends Migration
{
    public function up()
    {
        Schema::create('tarif', function (Blueprint $table) {
            $table->id('idTarif');
            $table->string('idLiai', 10);
            $table->unsignedBigInteger('idPeriode');
            $table->string('idType', 2);
            $table->decimal('tarif', 10, 2);
            $table->timestamps();

            $table->foreign('idLiai')->references('idLiai')->on('liaison')->onDelete('cascade');
            $table->foreign('idPeriode')->references('idPeriode')->on('periode')->onDelete('cascade');
            $table->foreign('idType')->references('idType')->on('type')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tarif');
    }
}
