@extends('master')

@section('titel','Song searcher') 
@section('header','Search for a song')

@section('inhoud')

<script type="text/javascript">
    
    function includeArtist()
    {
        if(!document.getElementById("includeArtist"))
        {
            //if(document.getElementById("includeArtist")) is true if not null/undefined/...
            return false;
        }
        return document.getElementById("includeArtist").checked;
    }
    
    function useLastFM()
    {
        if(!document.getElementById("lastfm"))
        {
            return false;
        }
        return document.getElementById("lastfm").checked;
    }
    
    function useSpotify()
    {
        if(!document.getElementById("spotify"))
        {
            return false;
        }
        return document.getElementById("spotify").checked;
    }
    
    function useLocalDatabase()
    {
        if(!document.getElementById("local"))
        {
            return false;
        }
        return document.getElementById("local").checked;
    }
    
    
    let responses = [];
    
    
    function searchLastFm(songtitle)
    {
        let url = "http://ws.audioscrobbler.com/2.0/?method=track.search&track=" + songtitle + "&api_key=1c29722debda9b327250154f911004b6&format=json";
        
        fetch(url).then(response=>
        {
            if(response.ok)
            {
                console.log(response);
                return response.json();
            }
            else
            {
                console.log(response);
                return Promise.reject("Last.fm data not found");
            }
        })
        
        .then(response => 
        {
            let received_songs = response.results.trackmatches.track;
            for(var i=0; i < received_songs.length; i++)
            {
                let name = received_songs[i].name;
                let artist = received_songs[i].artist;
                trackInfoLastFm(artist, name)
                //document.getElementById("lastfm_results").innerHTML += "<p>" + name + " " + artist + "</p>";
                
                
                //console.log(received_songs[i]);
            }
            //console.log(response.results.trackmatches);
            //document.getElementById("lastfm_results").innerHTML = response.results.trackmatches.track[0].name;
        });
    }
    
    function searchLastFmArtist(songtitle, artistname)
    {
        let url = "http://ws.audioscrobbler.com/2.0/?method=track.search&track=" + songtitle + "&artist=" + artistname + "&api_key=1c29722debda9b327250154f911004b6&format=json";
        
        fetch(url).then(response=>
        {
            if(response.ok)
            {
                console.log(response);
                return response.json();
            }
            else
            {
                console.log(response);
                return Promise.reject("Last.fm data not found");
            }
        })
        
        .then(response => 
        {
            let received_songs = response.results.trackmatches.track;
            for(var i=0; i < received_songs.length; i++)
            {
                let name = received_songs[i].name;
                let artist = received_songs[i].artist;
                trackInfoLastFm(artist, name)
            }
        });
    }
    
    function trackInfoLastFm(artist, song)
    {
        let url = 'http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=1c29722debda9b327250154f911004b6&artist='+artist+'&track='+song+'&format=json';
        fetch(url).then(response=>
        {
            if(response.ok)
            {
                console.log(response);
                return response.json();
            }
            else
            {
                console.log(response);
                return Promise.reject("Last.fm data not found");
            }
        })
        
        .then(response => 
        {
            let received_song = response.track;
            let title = received_song.name;
            let artistname = received_song.artist.name;
            let album = received_song.album.title;
            let string = "<tr><td>Last.fm</td><td>"+title+"</td><td>"+artistname+"</td><td>"+album+"</td></tr>"
            document.getElementById("results").innerHTML += string;
        });
    }
    
    function searchLocalDatabase(song)
    {
        let url = "http://127.0.0.1:8000/api/songs?song=" + song;
        fetch(url).then(response=>
        {
            if(response.ok)
            {
                console.log(response);
                return response.json();
            }
            else
            {
                console.log(response);
                return Promise.reject("Last.fm data not found");
            }
        })
        .then(response => 
        {
            for(var i = 0;i<response.length;i++)
            {
                let title = response[i].title;
                let artist_id = response[i].artist_id;
                let album_id = response[i].album_id;
                let string = "<tr><td>Local database</td><td>"+title+"</td><td>"+artist_id+"</td><td>"+album_id+"</td></tr>"
                document.getElementById("results").innerHTML += string;
            }
        });
    }
    
    function searchSong()
    {
        document.getElementById("results").innerHTML = "";
        let songtitle = document.getElementById("songtitle").value;

        if(includeArtist())
        {
            console.log(includeArtist());

            let artist = document.getElementById("artistname").value;
            
            if(useLastFM())
            {
                searchLastFmArtist(songtitle, artist);
            }
            if(useSpotify())
            {
                //TODO
            }
            if(useLocalDatabase())
            {
                //TODO
            }
        }
        else
        {
            console.log(includeArtist());
            if(useLastFM())
            {
                //lastfmresult = await searchLastFm(songtitle).then(function(result){return result;});
                searchLastFm(songtitle);
                //lastfmresult = searchLastFm(songtitle);
                //console.log(lastfmresult);
                //console.log(lastfmresult.results.trackmatches);
            }
            if(useLocalDatabase())
            {
                searchLocalDatabase(songtitle);
            }
        }
        //console.log(lastfmresult);
        //document.getElementById("results").innerHTML = lastfmresult.json().results.trackmatches.track[0].name;
        
    }
</script>


<p>
    If only one song corresponding to the given title is in the database, it will be returned. Otherwise, a list of possible songs will be returned.
</p>
    <form method="post" action=" ../songs" target="_blank">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="type" value="list">
        Song:<br>
        <input type="text" name="song" value="Song" id="songtitle"><br><br>
        
        Artist:<br>
        <input type="text" name="artist" value="Artist" id="artistname"> <input type="checkbox" name="includeArtist" value="IncludeArtist" id="includeArtist"> Include artist <br><br>
        <table>
            <tr>
                <td>
                    <input type="checkbox" name="lastfm" id="lastfm" value="Lastfm"> Last.fm
                </td>
                <td>
                    <input type="checkbox" name="spotify" id="spotify" value="Spotify"> Spotify
                </td>
                <td>
                    <input type="checkbox" name="local" id="local" value="Local"> Local database
                </td>
            </tr>
        </table>
        
        <input type="submit" value="Submit and save">
        <input type="button" value="Submit" onclick="searchSong()">
    </form>

<div id="query_results" style="overflow-y:scroll">
    <table id="results">
        <tr>
            <th>
                Source
            </th>
            <th>
                Title
            </th>
            <th>
                Artist
            </th>
            <th>
                Album
            </th>
        </tr>
    </table>
</div>
@stop