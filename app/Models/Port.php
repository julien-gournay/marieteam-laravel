<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $table = 'port';
    protected $primaryKey = 'idVille';
    public $incrementing = false; // Clé primaire non incrémentale
    protected $keyType = 'string';

    protected $fillable = ['nom', 'pays', 'photo', 'description'];
}
