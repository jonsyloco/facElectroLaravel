<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compania extends Model
{
    
    public $connection = 'ibg_100_7';
    protected $table = "companias";
    protected $primarykey = "codigo";
    public $timestamps = false;
    // protected $fillable = ['nombre', 'ciudad']; esta comentado porque no se puede guardar nada en BD, solo consultar
  
    
}
