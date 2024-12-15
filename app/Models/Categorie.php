<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categorie';
    protected $primaryKey = 'idCategorie';
    public $incrementing = false; // Clé primaire non incrémentale
    protected $keyType = 'string';

    protected $fillable = ['idCategorie', 'libelleCategorie'];
}
