<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public function product(){
        return $this->hasMany(Product::class);
    }
    public function import(){
        return $this->hasMany(Import::class);
    }
}
