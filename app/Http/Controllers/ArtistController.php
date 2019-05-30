<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Artist;
use App\MusicManager;
class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('allArtistsView')->with('artists',Artist::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('artistview')->with('albums',Artist::find($id)->albums()->get())
                                 ->with('artist',Artist::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //return $id;
        
       
        $wikipedia_response = MusicManager::setWikipediaInformation(Artist::where('id',$id)->first());
        
        if($wikipedia_response == NULL)
        {
            
            return view('wikipediaNotFound')->with('id',$id);
        }
        else
        {
            //return $wikipedia_response[1][1];
            
            return view('wikipediaSelector')->with('choices',$wikipedia_response[1])
                                            ->with('descriptions',$wikipedia_response[2])
                                            ->with('urls',$wikipedia_response[3])
                                            ->with('id',$id);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $url = $request->wikipedia_selection;
        
        //Problem: wikipedia url has:
        //  -All / characters changed to %2F
        //  -All : characters changed to %3A
        // Solution:
        //str_replace('%2F', '/', $url);
        //str_replace('%3A', ':', $url);
        Artist::where('id',$id)->first()->update(['wikipedia_url'=>$url]);
        
        return view('artistview')->with('albums',Artist::find($id)->albums()->get())
                                 ->with('artist',Artist::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
