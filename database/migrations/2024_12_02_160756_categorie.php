<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorieTable extends Migration
{
    public function up()
    {
        Schema::create('categorie', function (Blueprint $table) {
            $table->char('idCategorie', 1)->primary();
            $table->string('libelleCategorie', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categorie');
    }
}