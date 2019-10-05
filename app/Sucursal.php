<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
     //
     public $connection = 'informix';
     protected $table = "sucursales";
     protected $primarykey = "codigo";
     public $timestamps = false;
     protected $fillable = ['nombre', 'ciudad'];
   
 
     public function scopeObtenerCodigo($query){
 
         return $query->where('nombre','like','%A%');
     }
     
     public function getTonteriaAttribute(){
 
     }
}
