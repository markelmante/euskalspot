<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Spot extends Model
{
    protected $fillable = [
        'nombre',
        'tipo',
        'municipio_id',
        'descripcion',
        'foto', // Ahora guardará un JSON con 3 rutas
        'latitud',
        'longitud'
    ];

    protected $casts = [
        'foto' => 'array',
    ];

    // Relaciones
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function etiquetas(): BelongsToMany
    {
        return $this->belongsToMany(Etiqueta::class);
    }

    public function favorecidoPor()
    {
        return $this->belongsToMany(User::class, 'favoritos');
    }
}