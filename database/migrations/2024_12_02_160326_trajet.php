<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrajetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trajet', function (Blueprint $table) {
            $table->increments('idTrajet'); // Clé primaire auto-incrémentée
            $table->string('idLiaison', 10); // Référence à la table liaison
            $table->unsignedInteger('idBateau'); // Référence à la table bateau
            $table->date('dateDepart'); // Date du départ
            $table->time('heureDepart'); // Heure du départ
            $table->date('dateArrive'); // Date d'arrivée
            $table->time('heureArrive'); // Heure d'arrivée

            // Clés étrangères
            $table->foreign('idLiaison')->references('idLiai')->on('liaison')->onDelete('cascade');
            $table->foreign('idBateau')->references('idBateau')->on('bateau')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trajet');
    }
}
