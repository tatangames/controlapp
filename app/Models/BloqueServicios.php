<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueServicios extends Model
{
    use HasFactory;
    protected $table = 'bloque_servicios';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'imagen',
        'posicion',
        'activo',
    ];
}
