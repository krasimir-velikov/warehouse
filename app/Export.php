<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function export_product(){
        return $this->hasMany(ExportProduct::class);
    }
}
