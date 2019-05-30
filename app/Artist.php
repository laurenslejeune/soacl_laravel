<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Album;
class Artist extends Model
{
    //
    protected $fillable = ['wikipedia_url'];
    public function albums()
    {
        return $this->hasMany('App\Album');
    }
    
    public function songs()
    {
        return $this->hasMany('App\Song');
    }
}
