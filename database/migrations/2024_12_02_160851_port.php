<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortTable extends Migration
{
    public function up()
    {
        Schema::create('port', function (Blueprint $table) {
            $table->string('idVille', 3)->primary();
            $table->string('nom', 255);
            $table->string('pays', 255);
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('port');
    }
}
