<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExportProduct extends Model
{
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function export(){
        return $this->belongsTo(Export::class);
    }
}
