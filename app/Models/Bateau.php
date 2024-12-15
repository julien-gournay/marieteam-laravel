<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bateau extends Model
{
    use HasFactory;

    protected $table = 'bateau';
    protected $primaryKey = 'idBateau';

    protected $fillable = ['idCapitaine', 'nomBateau', 'marque'];

    public function capitaine()
    {
        return $this->belongsTo(Personnel::class, 'idCapitaine', 'idPers');
    }
}
