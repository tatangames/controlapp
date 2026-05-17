<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordenes extends Model
{
    use HasFactory;
    protected $table = 'ordenes';
    public $timestamps = false;

    protected $fillable = [
        'id_clientes',
        'nota',
        'precio_consumido',
        'fecha_orden',
        'estado_iniciada',
        'fecha_iniciada',
        'estado_finalizada',
        'fecha_finalizada',
        'estado_cancelada',
        'fecha_cancelada',
        'mensaje_cancelada',
        'cancelada_por',
        'visible',
    ];
}
