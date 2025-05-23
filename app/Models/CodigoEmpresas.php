<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoEmpresas extends Model
{
    use HasFactory;

    protected $table = 'codigoempresa';

    protected $fillable = [
        'codigo',
        'barcode',
        'empresa_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'empresa_id');
    }
}
