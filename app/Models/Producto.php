<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'producto';
    public $timestamps = false;

    protected $fillable = [
        'id_categorias',
        'nombre',
        'imagen',
        'descripcion',
        'precio',
        'activo',
        'posicion',
        'utiliza_nota',
        'nota',
        'utiliza_imagen'
    ];


}
