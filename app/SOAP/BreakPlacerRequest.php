<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SOAP;

/**
 * Description of BreakPlacerRequest
 *
 * @author Laurens
 */
class BreakPlacerRequest 
{
    protected $lyrics;
    
    public function __construct($lyrics) 
    {
        $this->lyrics = $lyrics;
    }
    
    public function getLyrics()
    {
        return $this->lyrics;
    }
}
