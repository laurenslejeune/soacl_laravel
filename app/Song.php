<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    //
    public function artist()
    {
        return $this->belongsTo('App\Artist');
    }
    
    public function album()
    {
        return $this->belongsTo('App\Album');
    }
    
    public function playlists()
    {
        return $this->belongsToMany('App\Playlist','additions');
    }
}
