<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $table = 'tarif';
    protected $primaryKey = 'idTarif';

    protected $fillable = ['idLiai', 'idPeriode', 'idType', 'tarif'];

    public function liaison()
    {
        return $this->belongsTo(Liaison::class, 'idLiai', 'idLiai');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'idPeriode', 'idPeriode');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'idType', 'idType');
    }
}
