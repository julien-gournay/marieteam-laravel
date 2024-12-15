<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    use HasFactory;

    protected $table = 'billet';
    protected $primaryKey = 'reference';
    public $incrementing = false; // Clé primaire non incrémentale
    protected $keyType = 'string';

    protected $fillable = ['reference', 'idType', 'quantite'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reference', 'reference');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'idType', 'idType');
    }
}
