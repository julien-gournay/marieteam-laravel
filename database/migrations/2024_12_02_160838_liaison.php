<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiaisonTable extends Migration
{
    public function up()
    {
        Schema::create('liaison', function (Blueprint $table) {
            $table->string('idLiai', 10)->primary();
            $table->string('idVilleDepart', 3);
            $table->string('idVilleArrivee', 3);
            $table->time('duree');
            $table->timestamps();

            $table->foreign('idVilleDepart')->references('idVille')->on('port')->onDelete('cascade');
            $table->foreign('idVilleArrivee')->references('idVille')->on('port')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('liaison');
    }
}
