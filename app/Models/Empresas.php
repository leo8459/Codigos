<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    use HasFactory;
     protected $table = 'empresa'; // nombre correcto de tu tabla

    protected $fillable = ['nombre', 'sigla', 'codigo_cliente',    'secuencia','ciclo'   // 👈 habilita asignación masiva
];
}
