<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Artist;
class Album extends Model
{
    //
    public function artist()
    {
        return $this->belongsTo('App\Artist');
    }
    
    public function songs()
    {
        return $this->hasMany('App\Song');
    }
}
