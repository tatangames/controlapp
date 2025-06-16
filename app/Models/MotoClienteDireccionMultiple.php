<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotoClienteDireccionMultiple extends Model
{
    use HasFactory;
    protected $table = 'moto_cliente_direccion_multi';
    public $timestamps = false;
}
