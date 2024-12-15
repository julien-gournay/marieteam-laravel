<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeTable extends Migration
{
    public function up()
    {
        Schema::create('type', function (Blueprint $table) {
            $table->string('idType', 2)->primary();
            $table->string('libelleType', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type');
    }
}
