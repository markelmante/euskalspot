<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $fillable = ['nombre'];

    public function spots()
    {
        return $this->hasMany(Spot::class);
    }
}