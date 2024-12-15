<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBilletTable extends Migration
{
    public function up()
    {
        Schema::create('billet', function (Blueprint $table) {
            $table->string('reference', 10);
            $table->string('idType', 2);
            $table->integer('quantite');
            $table->timestamps();

            $table->foreign('idType')->references('idType')->on('type')->onDelete('cascade');
            $table->foreign('reference')->references('reference')->on('reservation')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('billet');
    }
}

