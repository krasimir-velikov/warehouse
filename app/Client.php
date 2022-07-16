<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function product(){
        return $this->hasMany(Product::class);
    }
}
