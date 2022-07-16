<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function import_product(){
        return $this->hasMany(ImportProduct::class);
    }
}
