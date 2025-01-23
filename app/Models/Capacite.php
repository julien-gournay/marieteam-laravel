<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacite extends Model
{
    use HasFactory;

    protected $table = 'capacite';
    protected $fillable = ['idBateau', 'idCategorie', 'capacite'];

    public function bateau()
    {
        return $this->belongsTo(Bateau::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}
