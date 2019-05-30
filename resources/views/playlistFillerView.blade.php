@extends('master')

@section('titel','Playlistfiller') 
@section('header','Fill the playlist')

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
    
    function includeAlbum()
    {
        if(!document.getElementById("includeAlbum"))
        {
            //if(document.getElementById("includeArtist")) is true if not null/undefined/...
            return false;
        }
        return document.getElementById("includeAlbum").checked;
    }
    
    function includeSong()
    {
        if(!document.getElementById("includeSong"))
        {
            //if(document.getElementById("includeArtist")) is true if not null/undefined/...
            return false;
        }
        return document.getElementById("includeSong").checked;
    }
       
    function searchLocalDatabaseSong(playlist_id)
    {
        let base = "../../../add_api_key/local/";
        //let base = "http://127.0.0.1:8000/api/"
        let url = base + "songs";

        if(includeAlbum())
        {
            let album = document.getElementById("albumname").value;
            url = url.concat("/album/" + album);
        }
        if(includeArtist())
        {
            let artist = document.getElementById("artistname").value;
            url = url.concat("/artist/" + artist);
        }
        if(includeSong())
        {
            let song = document.getElementById("songtitle").value;
            url = url.concat("/song/" + song);
        }
        
        console.log("Lookup url: " + url);
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
                return Promise.reject("Local database data not found");
            }
        })
        .then(response => 
        {
            for(var i = 0;i<response.length;i++)
            {
                let title = response[i].title;
                let artist_id = response[i].artist_id;
                let song_id = response[i].id;
                let album_id = response[i].album_id;
                
                let artist_url = base + "artists/" + artist_id;
                let album_url = base + "albums/" + album_id;
                fetch(artist_url).then(response=>
                {
                    if(response.ok)
                    {
                        console.log(response);
                        return response.json();
                    }
                    else
                    {
                        console.log(response);
                        return Promise.reject("Local database data not found");
                    }
                })
                .then(response => 
                {
                    let artistname = response.name;
                    fetch(album_url).then(response=>
                    {
                        if(response.ok)
                        {
                            console.log(response);
                            return response.json();
                        }
                        else
                        {
                            console.log(response);
                            return Promise.reject("Local database data not found");
                        }
                    })
                    .then(response => 
                    {
                        let albumname = response.name;
                        let songHtml = "<a href=\"../../songs/" + song_id +"\">" + title + "</a>";
                        let artistHtml = "<a href=\"../../artists/" + artist_id +"\">" + artistname + "</a>";
                        let albumHtml = "<a href=\"../../albums/" + album_id +"\">" + albumname + "</a>";
                        let putSong = "<form><input type=\"button\" value=\"Add\" onclick=\"addSongToPlayList("+playlist_id+","+song_id+")\"></form>";
                        let string = "<tr><td>"+songHtml+"</td><td>"+artistHtml+"</td><td>"+albumHtml+"</td></td><td>"+putSong+"</td></tr>";
                        document.getElementById("results").innerHTML += string;
                    });
                });
            }
        });
        
    }
    
    function addSongToPlayList(playlist_id,song_id)
    {
        let keyadder_local = "../../../add_api_key/local/";
        //'http://127.0.0.1:8000/api/playlists/'
        fetch( keyadder_local + 'playlists/' + playlist_id,
        {
            method: 'PUT',
            headers: 
            {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Csrf-Token': '{{csrf_token()}}'
            },
            body: JSON.stringify({song_id:song_id,type:'song_addition'})
        }).then(response=>
        {
            if(response.ok)
            {
                //console.log(response);
                return response.text();
            }
            else
            {
                console.log(response);
                return Promise.reject("Song not succesfully added");
            }
        
        }).then(response=>
        {
            console.log(response);
            showCurrentPlaylist(playlist_id);
        });
    }
    
    function removeSongFromPlaylist(playlist_id,song_id)
    {
        let keyadder_local = "../../../add_api_key/local/";
        //'http://127.0.0.1:8000/api/playlists/'
        fetch( keyadder_local + 'playlists/' + playlist_id,
        {
            method: 'PUT',
            headers: 
            {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Csrf-Token': '{{csrf_token()}}'
            },
            body: JSON.stringify({song_id:song_id,type:'song_removal'})
        }).then(response=>
        {
            if(response.ok)
            {
                //console.log(response);
                return response.text();
            }
            else
            {
                console.log(response);
                return Promise.reject("Song not succesfully added");
            }
        
        }).then(response=>
        {
            console.log(response);
            showCurrentPlaylist(playlist_id);
        });
    }
    
    function showCurrentPlaylist(playlist_id)
    { 
        let base = "../../../add_api_key/local/";
        let url = base + "playlists/";
        
        //'http://127.0.0.1:8000/api/playlists/' + playlist_id
        fetch(url + playlist_id).
        then(response=>
        {
            if(response.ok)
            {
                console.log(response);
                return response.json();
            }
            else
            {
                console.log(response);
                return Promise.reject("Local database data not found");
            }
        })
        .then(response => 
        {
            let totalString =  "";
            
            //document.getElementById("currentPlaylist").innerHTML = "<table>";
            for(var i = 0;i<response.length; i++)
            {
                let title = response[i].title;
                let song_id = response[i].song_id;
                let song_url = "../../songs/" + song_id;
                let song_html = "<a href=\""+ song_url +"\">" + title + "</a>";
        
                let artist = response[i].artistname;
                let artist_id = response[i].artist_id;
                let artist_url = "../../artists/" + artist_id;
                let artist_html = "<a href=\""+ artist_url +"\">" + artist + "</a>";
                
                
                let album = response[i].albumname;
                let album_id = response[i].album_id;
                let album_url = "../../albums/" + album_id;
                let album_html = "<a href=\""+ album_url +"\">" + album + "</a>";
                
                let removeSong = "<form><input type=\"button\" value=\"Remove\" onclick=\"removeSongFromPlaylist("+playlist_id+","+song_id+")\"></form>";
                
                let string = "<tr><td>"+song_html+"</td><td>"+artist_html+"</td><td>"+album_html+"</td><td>"+removeSong+"</td></tr>";
                totalString += string;
                //document.getElementById("currentPlaylist").innerHTML += string;
            }
            document.getElementById("currentPlaylist").innerHTML = totalString;
        });
        
    }
    
    function isSufficientlyIncludedType()
    {
        return includeArtist() || includeAlbum() || includeSong();
    }
    
    
    function search(playlist_id)
    {
        //Check that at least one of the three search parameters (song, artist, album) is included
        //Also check that at least one source will be used
        if(isSufficientlyIncludedType())
        {
            document.getElementById("error_message").innerHTML = "";
            searchLocalDatabaseSong(playlist_id);
        }
        else
        {
            let error = document.getElementById("error_message");
            error.style.color = 'red';
            error.innerHTML = "Please select at least one search parameter";
        }
    }
    
    window.onload = showCurrentPlaylist({!!$playlist_id!!});
</script>
<style type="text/css">
/*Source: https://medium.com/@vembarrajan/html-css-tricks-scroll-able-table-body-tbody-d23182ae0fbc*/
.fixed_header{
    width: 95%;
    table-layout: fixed;
    border-collapse: collapse;
}

.fixed_header tbody{
  display:block;
  width: 100%;
  overflow: auto;
  height: 200px;
}

.fixed_header thead tr {
   display: block;
   width: 100%;
}

.fixed_header thead {
  background: black;
  color:#fff;
}

.fixed_header td {
  padding: 5px;
  text-align: left;
  width: 25%;
}

.fixed_header th{ 
  padding: 5px;
  text-align: left;
  width: 25%;
}
</style>
<div>
    <form method="get" action=" ../{{$playlist_id}}">
        
        <input type="submit" value="Done editing">
    </form>
</div>

<div>
    <h5>Current playlist</h5>
    <table class="fixed_header">
        <thead>
            <tr>
                <th>
                    Song
                </th>
                <th>
                    Artist
                </th>
                <th>
                    Album
                </th>
                <th>
                    Remove
                </th>
            </tr>
        </thead>
        <tbody id="currentPlaylist">
            
        </tbody>
    </table>
</div>



<p>
    Add songs to the playlist by searching and selecting items below.
</p>
    <form method="post" action=" ../songs" target="_blank">
        
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="type" value="list">
        Song:<br>
        <input type="text" name="song" value="Song" id="songtitle"> <input type="checkbox" name="includeSong" value="IncludeSong" id="includeSong"> Include song <br><br>
        
        Artist:<br>
        <input type="text" name="artist" value="Artist" id="artistname"> <input type="checkbox" name="includeArtist" value="IncludeArtist" id="includeArtist"> Include artist <br><br>
        
        Album:<br>
        <input type="text" name="album" value="Album" id="albumname"> <input type="checkbox" name="includeAlbum" value="IncludeAlbum" id="includeAlbum"> Include album <br><br>
        
        <input type="button" value="Search" onclick="search({!!$playlist_id!!})">
    </form>


<div id="error_message">
    
</div>

<br><br>
<div id="query_results">
    <table class="fixed_header">
        <thead>
            <tr>
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
        </thead>
        <tbody id="results">
            
        </tbody>
    </table>
</div>

@stop