<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liaison extends Model
{
    use HasFactory;

    protected $table = 'liaison';
    protected $primaryKey = 'idLiai';
    public $incrementing = false; // Clé primaire non incrémentale
    protected $keyType = 'string';

    protected $fillable = ['idVilleDepart', 'idVilleArrivee', 'duree'];

    public function portDepart()
    {
        return $this->belongsTo(Port::class, 'idVilleDepart', 'idVille');
    }

    public function portArrivee()
    {
        return $this->belongsTo(Port::class, 'idVilleArrivee', 'idVille');
    }
}
