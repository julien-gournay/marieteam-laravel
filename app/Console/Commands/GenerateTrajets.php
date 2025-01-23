<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Liaison;
use App\Models\Trajet;
use App\Models\Bateau;
use Carbon\Carbon;

class GenerateTrajets extends Command
{
    /**
     * Nom et description de la commande.
     */
    protected $signature = 'generate:trajets
                            {dateDebut : Date de début au format YYYY-MM-DD} 
                            {dateFin : Date de fin au format YYYY-MM-DD} 
                            {plageDebut : Heure de début de la plage horaire (format HH:mm)} 
                            {plageFin : Heure de fin de la plage horaire (format HH:mm)} 
                            {trajetsParJourMin=5 : Nombre minimum de trajets par jour (minimum 5)} 
                            {trajetsParJourMax=10 : Nombre maximum de trajets par jour (maximum 10)}';

    protected $description = 'Génère des trajets automatiquement selon les paramètres.';

    public function handle()
    {
        // Récupération des paramètres
        $dateDebut = $this->argument('dateDebut');
        $dateFin = $this->argument('dateFin');
        $plageDebut = $this->argument('plageDebut');
        $plageFin = $this->argument('plageFin');
        $trajetsParJourMin = (int) $this->argument('trajetsParJourMin');
        $trajetsParJourMax = (int) $this->argument('trajetsParJourMax');

        // Validation des paramètres
        if ($trajetsParJourMin < 5 || $trajetsParJourMax > 10 || $trajetsParJourMin > $trajetsParJourMax) {
            $this->error('Le nombre de trajets par jour doit être compris entre 5 et 8, et trajetsParJourMin doit être inférieur ou égal à trajetsParJourMax.');
            return;
        }

        $plageDebutTime = Carbon::createFromTimeString($plageDebut);
        $plageFinTime = Carbon::createFromTimeString($plageFin);

        if ($plageDebutTime->greaterThanOrEqualTo($plageFinTime)) {
            $this->error('La plage horaire de début doit être avant la plage horaire de fin.');
            return;
        }

        $liaisons = Liaison::all();
        $bateaux = Bateau::all();

        foreach ($liaisons as $liaison) {
            $this->info("Génération des trajets pour la liaison : {$liaison->idLiai}");

            $dureeLiaisonMinutes = $this->convertTimeToMinutes($liaison->duree);

            $currentDate = Carbon::createFromFormat('Y-m-d', $dateDebut);

            while ($currentDate->format('Y-m-d') <= $dateFin) {
                // Nombre de trajets à générer pour ce jour
                $trajetsParJour = rand($trajetsParJourMin, $trajetsParJourMax);

                $this->info("Date : {$currentDate->format('Y-m-d')} ($trajetsParJour)");

                for ($i = 0; $i < $trajetsParJour; $i++) {
                    $bateau = $bateaux->random();

                    $heureDepart = $this->generateRandomTime($plageDebutTime, $plageFinTime);
                    $heureArrivee = (clone $heureDepart)->addMinutes($dureeLiaisonMinutes);

                    $dateArrivee = $currentDate;

                    if ($heureArrivee->isBefore($heureDepart)) {
                        $dateArrivee = $currentDate->copy()->addDay();
                    }

                    Trajet::create([
                        'idLiaison' => $liaison->idLiai,
                        'idBateau' => $bateau->idBateau,
                        'dateDepart' => $currentDate->format('Y-m-d'),
                        'heureDepart' => $heureDepart->format('H:i:s'),
                        'dateArrive' => $dateArrivee->format('Y-m-d'),
                        'heureArrive' => $heureArrivee->format('H:i:s'),
                    ]);
                }

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

    /**
     * Convertit une durée au format HH:MM:SS en minutes.
     *
     * @param string $time
     * @return int
     */
    private function convertTimeToMinutes($time)
    {
        list($hours, $minutes, $seconds) = explode(':', $time);
        return ($hours * 60) + $minutes + ($seconds / 60);
    }
}
