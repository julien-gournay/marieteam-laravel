<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    use HasFactory;

    // Nom de la table dans la base de données
    protected $table = 'trajet';

    // Nom de la clé primaire
    protected $primaryKey = 'idTrajet';

    // Désactiver les timestamps (created_at, updated_at)
    public $timestamps = false;

    // Colonnes remplissables
    protected $fillable = [
        'idLiaison',
        'idBateau',
        'dateDepart',
        'heureDepart',
        'dateArrive',
        'heureArrive',
    ];

    /**
     * Relation avec la table Liaison
     */
    public function liaison()
    {
        return $this->belongsTo(Liaison::class, 'idLiaison', 'idLiai');
    }

    /**
     * Relation avec la table Bateau
     */
    public function bateau()
    {
        return $this->belongsTo(Bateau::class, 'idBateau', 'idBateau');
    }
}
