<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodeTable extends Migration
{
    public function up()
    {
        Schema::create('periode', function (Blueprint $table) {
            $table->id('idPeriode');
            $table->string('libelle', 255);
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('periode');
    }
}
