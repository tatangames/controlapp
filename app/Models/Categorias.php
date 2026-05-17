<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;
    protected $table = 'categorias';
    public $timestamps = false;

    protected $fillable = [
        'id_bloque_servicios',
        'nombre',
        'posicion',
        'activo',
    ];

    // Relación con BloqueServicios
    public function bloqueServicios()
    {
        return $this->belongsTo(BloqueServicios::class, 'id_bloque_servicios');
    }

    // Relación con Producto (necesaria para el whereHas)
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categorias');
    }
}
