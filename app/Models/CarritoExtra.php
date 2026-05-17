<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoExtra extends Model
{
    use HasFactory;
    protected $table = 'carrito_extra';
    public $timestamps = false;

    protected $fillable = [
        'id_carrito_temporal',
        'id_producto',
        'nota_producto',
        'cantidad',
    ];
}
