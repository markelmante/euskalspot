<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    protected $fillable = ['nombre'];

    public function spots()
    {
        return $this->belongsToMany(Spot::class);
    }
}