<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class GenerateCapacites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:capacites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les capacités pour chaque bateau et chaque catégorie avec des valeurs aléatoires';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Récupérer tous les bateaux
        $bateaux = DB::table('bateau')->get();

        if ($bateaux->isEmpty()) {
            $this->error("Aucun bateau trouvé dans la base de données.");
            return Command::FAILURE;
        }

        // Catégories avec leurs intervalles de valeurs
        $categories = [
            'A' => [80, 500],   // Catégorie A : Entre 80 et 500, arrondi à la dizaine près
            'B' => [10, 100],   // Catégorie B : Entre 10 et 100
            'C' => [20, 150],   // Catégorie C : Entre 20 et 150
            'D' => [5, 50],     // Catégorie D : Entre 5 et 50
        ];

        // Boucle pour chaque bateau
        foreach ($bateaux as $bateau) {
            $this->info("Bateau : {$bateau->idBateau} ({$bateau->nomBateau})");

            // Boucle pour chaque catégorie
            foreach ($categories as $categorie => $interval) {
                $capacite = rand($interval[0], $interval[1]);  // Génération aléatoire dans l'intervalle
                $capacite = round($capacite / 10) * 10;       // Arrondi à la dizaine près

                // Insérer ou mettre à jour la capacité dans la table
                DB::table('capacite')->updateOrInsert(
                    [
                        'idBateau' => $bateau->idBateau,
                        'idCategorie' => $categorie,
                    ],
                    [
                        'capacite' => $capacite,
                    ]
                );

                $this->info("  Catégorie : {$categorie}, Capacité : {$capacite}");
            }
        }

        $this->info("Génération des capacités terminée avec succès.");
        return Command::SUCCESS;
    }
}