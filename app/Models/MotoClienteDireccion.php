<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotoClienteDireccion extends Model
{
    use HasFactory;
    protected $table = 'moto_cliente_direccion';
    public $timestamps = false;
}
