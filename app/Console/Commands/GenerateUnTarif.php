<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateUnTarif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:untarif {idLiaison} {idPeriode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les tarifs pour une liaison et une période spécifique';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $idLiaison = $this->argument('idLiaison');
        $idPeriode = $this->argument('idPeriode');

        // Vérifier si la liaison existe
        $liaison = DB::table('liaison')->where('idLiai', $idLiaison)->first();
        if (!$liaison) {
            $this->error("Aucune liaison trouvée avec l'ID $idLiaison.");
            return Command::FAILURE;
        }

        $this->info("Liaison: {$liaison->idLiai} ({$liaison->idvilleDepart} -> {$liaison->idvilleArrivee})");

        // Demander le prix de base pour le type A1
        $basePrice = $this->ask("Quel est le prix de base pour le type A1 pour la liaison {$liaison->idLiai} ?", 10);

        if (!is_numeric($basePrice) || $basePrice <= 0) {
            $this->error("Prix de base invalide. Opération annulée.");
            return Command::FAILURE;
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
                'idLiaison' => $idLiaison,
                'idPeriode' => $idPeriode,
                'idType' => $type,
                'tarif' => round($price, 2),
            ]);
            $this->info("Tarif généré : Liaison $idLiaison, Type $type, Prix $price");
        }

        $this->info('Génération des tarifs terminée avec succès.');
        return Command::SUCCESS;
    }
}
