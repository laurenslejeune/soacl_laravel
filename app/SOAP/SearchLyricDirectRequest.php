<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchLyricDirectRequest
 *
 * @author Laurens
 */

namespace App\SOAP;

class SearchLyricDirectRequest {
    //put your code here
    
    protected $artist;
    protected $song;
    
    public function __construct($artist, $song) {
        $this->artist = $artist;
        $this->song   = $song;
    }
    
    public function getArtist()
    {
        return $this->artist;
    }
    
    public function getSong()
    {
        return $this->song;
    }
}
