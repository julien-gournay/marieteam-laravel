<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateUneCapacite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:unecapacite {idBateau}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Génère les capacités pour un bateau donné par son ID";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $idBateau = $this->argument('idBateau');

        // Vérifier si le bateau existe
        $bateau = DB::table('bateau')->where('idBateau', $idBateau)->first();

        if (!$bateau) {
            $this->error("Aucun bateau trouvé avec l'ID {$idBateau}.");
            return Command::FAILURE;
        }

        $this->info("Bateau : {$bateau->idBateau} ({$bateau->nomBateau})");

        // Catégories avec leurs intervalles de valeurs
        $categories = [
            'A' => [80, 500],   // Catégorie A : Entre 80 et 500, arrondi à la dizaine près
            'B' => [10, 100],   // Catégorie B : Entre 10 et 100
            'C' => [20, 150],   // Catégorie C : Entre 20 et 150
            'D' => [5, 50],     // Catégorie D : Entre 5 et 50
        ];

        // Boucle pour chaque catégorie
        foreach ($categories as $categorie => $interval) {
            $capacite = rand($interval[0], $interval[1]);  // Génération aléatoire dans l'intervalle
            $capacite = round($capacite / 10) * 10;       // Arrondi à la dizaine près

            // Insérer ou mettre à jour la capacité dans la table
            DB::table('capacite')->updateOrInsert(
                [
                    'idBateau' => $idBateau,
                    'idCategorie' => $categorie,
                ],
                [
                    'capacite' => $capacite,
                ]
            );

            $this->info("  Catégorie : {$categorie}, Capacité : {$capacite}");
        }

        $this->info("Génération des capacités terminée avec succès pour le bateau ID {$idBateau}.");
        return Command::SUCCESS;
    }
}
