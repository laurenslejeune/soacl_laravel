<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\MusicManager;
use App\Song;
class TranslationController extends Controller
{
    
    /**
     * Get the translation of the lyrics of the given song
     * @param int $id The id of the given song
     * @return string The translation of the song's lyrics, if applicable
     */
    public function translateEnglishToDutchHtml($id)
    {
        //First: get the lyrics to translate :
        $text = Song::find($id)->lyrics;
        
        if($text != "")
        {
            //Next: translate the text:
            $translation = MusicManager::translateLyrics($text);

            //Finally: Add break points (if necessary):
            return MusicManager::translatedLyricsToHTML($translation);
        }
        else
        {
            return "";
        }
        
    }
}
