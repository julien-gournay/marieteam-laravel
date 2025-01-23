<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateTarifs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tarifs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les tarifs pour chaque liaison et chaque type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Récupérer toutes les liaisons depuis la base de données
        $liaisons = DB::table('liaison')->get();

        if ($liaisons->isEmpty()) {
            $this->error('Aucune liaison trouvée dans la base de données.');
            return Command::FAILURE;
        }

        // Boucle sur chaque liaison
        foreach ($liaisons as $liaison) {
            $this->info("Liaison: {$liaison->idLiai} ({$liaison->idvilleDepart} -> {$liaison->idvilleArrivee})");

            // Demander le prix de base pour le type A1
            $basePrice = $this->ask("Quel est le prix de base pour le type A1 pour la liaison {$liaison->idLiai} ?", 10);

            // Assurer que le prix est un nombre valide
            if (!is_numeric($basePrice) || $basePrice <= 0) {
                $this->error("Prix de base invalide pour la liaison {$liaison->idLiai}. Ignorée.");
                continue;
            }

            $basePrice = (float) $basePrice;

            // Définir les ajustements pour chaque type
            $types = [
                'A1' => $basePrice,
                'A2' => $basePrice * 0.90,
                'A3' => $basePrice * 0.75,
                'B1' => 5,
                'B2' => $basePrice * 1.30,
                'C1' => $basePrice * 1.53,
                'C2' => $basePrice * 1.80,
                'C3' => $basePrice * 1.85,
                'D1' => $basePrice * 2.15,
                'D2' => $basePrice * 2.10,
            ];

            // Insérer chaque type et tarif dans la table tarifs
            foreach ($types as $type => $price) {
                DB::table('tarif')->insert([
                    'idLiaison' => $liaison->idLiai,
                    'idPeriode' => 2, // Période fixe
                    'idType' => $type,
                    'tarif' => round($price, 2), // Prix arrondi à 2 décimales
                ]);
                $this->info("Tarif généré : Liaison {$liaison->idLiai}, Type {$type}, Prix {$price}");
            }
        }

        $this->info('Génération des tarifs terminée avec succès.');
        return Command::SUCCESS;
    }
}
