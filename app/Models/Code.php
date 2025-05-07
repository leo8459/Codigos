<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;
    protected $table = 'codigos'; // <-- nombre real de la tabla

    protected $fillable = ['codigo', 'barcode'];

}
