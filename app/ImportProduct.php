<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportProduct extends Model
{
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function import(){
        return $this->belongsTo(Import::class);
    }
}
