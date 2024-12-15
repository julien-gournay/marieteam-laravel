<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBateauTable extends Migration
{
    public function up()
    {
        Schema::create('bateau', function (Blueprint $table) {
            $table->id('idBateau');
            $table->unsignedBigInteger('idCapitaine');
            $table->string('nomBateau', 255);
            $table->string('marque', 255);
            $table->timestamps();

            $table->foreign('idCapitaine')->references('idPers')->on('personnel')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bateau');
    }
}
