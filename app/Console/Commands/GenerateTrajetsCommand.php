<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Liaison;
use App\Models\Trajet;
use App\Models\Bateau;
use Carbon\Carbon;

class GenerateTrajetsCommand extends Command
{
    /**
     * Le nom et la description de la commande.
     */
    protected $signature = 'generate:trajets2 
                            {dateDebut : Date de début au format YYYY-MM-DD} 
                            {dateFin : Date de fin au format YYYY-MM-DD} 
                            {plageDebut : Heure de début de la plage horaire (format HH:mm)} 
                            {plageFin : Heure de fin de la plage horaire (format HH:mm)} 
                            {trajetsParJour=5 : Nombre de trajets par jour (entre 5 et 8)}';

    protected $description = 'Génère des trajets automatiquement selon les paramètres.';

    public function handle()
    {
        // Récupération des paramètres
        $dateDebut = $this->argument('dateDebut');
        $dateFin = $this->argument('dateFin');
        $plageDebut = $this->argument('plageDebut');
        $plageFin = $this->argument('plageFin');
        $trajetsParJour = (int) $this->argument('trajetsParJour');

        // Vérification si le nombre de trajets par jour est valide
        if ($trajetsParJour < 5 || $trajetsParJour > 8) {
            $this->error('Le nombre de trajets par jour doit être compris entre 5 et 8.');
            return;
        }

        // Conversion des plages horaires en objets Carbon
        $plageDebutTime = Carbon::createFromTimeString($plageDebut);
        $plageFinTime = Carbon::createFromTimeString($plageFin);

        // Vérification des paramètres
        if ($plageDebutTime->greaterThanOrEqualTo($plageFinTime)) {
            $this->error('La plage horaire de début doit être avant la plage horaire de fin.');
            return;
        }

        // Récupération des liaisons et des bateaux disponibles
        $liaisons = Liaison::all();
        $bateaux = Bateau::all();

        // Boucle sur les liaisons
        foreach ($liaisons as $liaison) {
            $this->info("Génération des trajets pour la liaison : {$liaison->idLiai}");

            // Récupérer la durée de la liaison (exprimée en minutes)
            $dureeLiaison = Carbon::parse($liaison->duree)->diffInMinutes();

            // Boucle sur chaque jour entre dateDebut et dateFin
            $currentDate = Carbon::createFromFormat('Y-m-d', $dateDebut);

            while ($currentDate->format('Y-m-d') <= $dateFin) {
                $this->info("Date : {$currentDate->format('Y-m-d')}");

                // Utilisation du nombre de trajets par jour fourni en paramètre
                for ($i = 0; $i < $trajetsParJour; $i++) {
                    // Choisir un bateau aléatoire
                    $bateau = $bateaux->random();

                    // Heure de départ aléatoire dans la plage
                    $heureDepart = $this->generateRandomTime($plageDebutTime, $plageFinTime);

                    // Calcul de l'heure d'arrivée en ajoutant la durée de la liaison
                    $heureArrivee = (clone $heureDepart)->addMinutes($dureeLiaison);

                    // Insérer le trajet dans la base de données
                    Trajet::create([
                        'idLiaison' => $liaison->idLiai,
                        'idBateau' => $bateau->idBateau,
                        'dateDepart' => $currentDate->format('Y-m-d'),
                        'heureDepart' => $heureDepart->format('H:i:s'),
                        'dateArrive' => $heureDepart->greaterThan($heureArrivee) 
                            ? $currentDate->copy()->addDay()->format('Y-m-d') 
                            : $currentDate->format('Y-m-d'), // Si le trajet dépasse minuit
                        'heureArrive' => $heureArrivee->format('H:i:s'),
                    ]);
                }

                // Passer au jour suivant
                $currentDate->addDay();
            }
        }

        $this->info('Génération des trajets terminée.');
    }

    /**
     * Génère une heure aléatoire entre deux plages horaires.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return Carbon
     */
    private function generateRandomTime(Carbon $start, Carbon $end)
    {
        $diffInMinutes = $start->diffInMinutes($end);
        $randomMinutes = rand(0, $diffInMinutes);

        return $start->copy()->addMinutes($randomMinutes);
    }
}
