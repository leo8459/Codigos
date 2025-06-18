<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    use HasFactory;

    protected $fillable = [
        'accion',
        'descripcion',
        'codigo',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
