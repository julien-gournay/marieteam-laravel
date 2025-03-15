<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Liaison;
use App\Models\Trajet;
use App\Models\Bateau;
use Carbon\Carbon;

class GenerateUnTrajet extends Command
{
    protected $signature = 'generate:untrajet
                            {idLiaison : ID de la liaison} 
                            {dateDebut : Date de début au format YYYY-MM-DD} 
                            {dateFin : Date de fin au format YYYY-MM-DD} 
                            {plageDebut : Heure de début de la plage horaire (format HH:mm)} 
                            {plageFin : Heure de fin de la plage horaire (format HH:mm)} 
                            {trajetsParJourMin=5 : Nombre minimum de trajets par jour (minimum 5)} 
                            {trajetsParJourMax=10 : Nombre maximum de trajets par jour (maximum 10)}';

    protected $description = 'Génère des trajets pour une liaison spécifique selon les paramètres.';

    public function handle()
    {
        $idLiaison = $this->argument('idLiaison');
        $dateDebut = $this->argument('dateDebut');
        $dateFin = $this->argument('dateFin');
        $plageDebut = $this->argument('plageDebut');
        $plageFin = $this->argument('plageFin');
        $trajetsParJourMin = (int) $this->argument('trajetsParJourMin');
        $trajetsParJourMax = (int) $this->argument('trajetsParJourMax');

        if ($trajetsParJourMin < 5 || $trajetsParJourMax > 10 || $trajetsParJourMin > $trajetsParJourMax) {
            $this->error('Le nombre de trajets par jour doit être compris entre 5 et 10, et trajetsParJourMin doit être inférieur ou égal à trajetsParJourMax.');
            return;
        }

        $plageDebutTime = Carbon::createFromTimeString($plageDebut);
        $plageFinTime = Carbon::createFromTimeString($plageFin);

        if ($plageDebutTime->greaterThanOrEqualTo($plageFinTime)) {
            $this->error('La plage horaire de début doit être avant la plage horaire de fin.');
            return;
        }

        $liaison = Liaison::find($idLiaison);

        if (!$liaison) {
            $this->error("Aucune liaison trouvée avec l'ID : {$idLiaison}");
            return;
        }

        $this->info("Génération des trajets pour la liaison : {$liaison->idLiai}");

        $dureeLiaisonMinutes = $this->convertTimeToMinutes($liaison->duree);
        $bateaux = Bateau::all();
        $currentDate = Carbon::createFromFormat('Y-m-d', $dateDebut);

        while ($currentDate->format('Y-m-d') <= $dateFin) {
            $trajetsParJour = rand($trajetsParJourMin, $trajetsParJourMax);
            $this->info("Date : {$currentDate->format('Y-m-d')} ($trajetsParJour trajets)");

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

        $this->info('Génération des trajets terminée.');
    }

    private function generateRandomTime(Carbon $start, Carbon $end)
    {
        $diffInMinutes = $start->diffInMinutes($end);
        $randomMinutes = rand(0, $diffInMinutes);

        return $start->copy()->addMinutes($randomMinutes);
    }

    private function convertTimeToMinutes($time)
    {
        list($hours, $minutes, $seconds) = explode(':', $time);
        return ($hours * 60) + $minutes + ($seconds / 60);
    }
}
