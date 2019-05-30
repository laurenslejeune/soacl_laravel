@extends('master')

@section('titel','Song searcher') 
@section('header','Search for a song')

@section('inhoud')

<script type="text/javascript">
    
    let keyadder_lastfm = "../add_api_key/lastfm";
    //let keyadder_lastfm = "http://localhost/mediamanager/public/add_api_key/lastfm"; 
    let keyadder_local = "../add_api_key/local/";
    //let keyadder_local = "http://localhost/mediamanager/public/add_api_key/local/";
    //let local_api = "http://127.0.0.1:8000/api/";
    
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
    
    function useLastFM()
    {
        if(!document.getElementById("lastfm"))
        {
            return false;
        }
        return document.getElementById("lastfm").checked;
    }
   
    function useLocalDatabase()
    {
        if(!document.getElementById("local"))
        {
            return false;
        }
        return document.getElementById("local").checked;
    }
    
    function saveResults()
    {
        if(!document.getElementById("save"))
        {
            return false;
        }
        return document.getElementById("save").checked;
    } 
    
    function searchLastFmSong()
    {
        if(includeArtist() && includeSong())
        {
            let songtitle = document.getElementById("songtitle").value;
            let artistname = document.getElementById("artistname").value;
            
            let lastfm_url = "method=track.search&track=" + songtitle + "&artist=" + artistname +"&format=json";
            //let url = "http://ws.audioscrobbler.com/2.0/?method=track.search&track=" + songtitle + "&artist=" + artistname +  "&api_key=1c29722debda9b327250154f911004b6&format=json";
            let url = keyadder_lastfm + "/" + lastfm_url;

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
                console.log(response);
                let received_songs = response.results.trackmatches.track;
                for(var i=0; i < received_songs.length; i++)
                {
                    let name = received_songs[i].name;
                    let artist = received_songs[i].artist;
                    trackInfoLastFm(artist, name);
                }
            });
        }
        else if(includeSong())
        {
            let songtitle = document.getElementById("songtitle").value;
            
            let lastfm_url = "method=track.search&track=" + songtitle +"&format=json";
            //let url = "http://ws.audioscrobbler.com/2.0/?method=track.search&track=" + songtitle + "&api_key=1c29722debda9b327250154f911004b6&format=json";
            let url = keyadder_lastfm + "/" + lastfm_url;
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
    }
    
    function searchLastFmArtist()
    {
        let songtitle = document.getElementById("songtitle").value;
        let artistname = document.getElementById("artistname").value;
        if(includeArtist()&&includeSong())
        {
            
            //let url = "http://ws.audioscrobbler.com/2.0/?method=track.search&track=" + songtitle + "&artist=" + artistname + "&api_key=1c29722debda9b327250154f911004b6&format=json";
            let lastfm_url = "method=track.search&track=" + songtitle +"&format=json" + "&artist=" + artistname;
            let url = keyadder_lastfm + "/" + lastfm_url;
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
                    //let name = received_songs[i].name;
                    let artist = received_songs[i].artist;
                    artistInfoLastFm(artist)
                }
            });
        }
        else
        {
            //url = "http://ws.audioscrobbler.com/2.0/?method=artist.search&artist=" + artistname + "&api_key=1c29722debda9b327250154f911004b6&format=json";
            let lastfm_url = "method=artist.search&format=json&artist=" + artistname;
            let url = keyadder_lastfm + "/" + lastfm_url;
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
                let received_artists = response.results.artistmatches.artist;
                for(var i=0; i < received_artists.length; i++)
                {
                    //let name = received_songs[i].name;
                    let artist = received_artists[i].name;
                    artistInfoLastFm(artist);
                }
            });
        }
    }
    
    function searchLastFmAlbum()
    {
        let albumname = document.getElementById("albumname").value;
        let songtitle = document.getElementById("songtitle").value;
        let artistname = document.getElementById("artistname").value;
        //url = "http://ws.audioscrobbler.com/2.0/?method=album.search&album="+albumname+"&api_key=1c29722debda9b327250154f911004b6&format=json";
        
        if(includeArtist() && includeSong())
        {
            let lastfm_url = "method=track.search&track=" + songtitle +"&format=json" + "&artist=" + artistname;
            let url = keyadder_lastfm + "/" + lastfm_url;
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
                    trackInfoLastFmForAlbum(artist, name);
                }
            });
        }
        else if(includeAlbum())
        {
            let lastfm_url = "method=album.search&format=json&album=" + albumname;
            let url = keyadder_lastfm + "/" + lastfm_url;
            
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
                let received_albums = response.results.albummatches.album;
                for(var i=0; i < received_albums.length; i++)
                {
                    let artist = received_albums[i].artist;
                    let album = received_albums[i].name;
                    albumInfoLastFm(album,artist);
                }
            });
        }
    }
    
    function trackInfoLastFm(artist, song)
    {
        //let url = 'http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=1c29722debda9b327250154f911004b6&artist='+artist+'&track='+song+'&format=json';
        
        let lastfm_url = "method=track.getInfo&format=json&artist="+artist+"&track="+song;
        let url = keyadder_lastfm + "/" + lastfm_url;
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
            if(typeof received_song.album === 'undefined')
            {
                console.log("Album is undefined, result will not be stored");
                //We add this, for good measure
                let string = "<tr><td>Last.fm</td><td>"+title+"</td><td>"+artistname+"</td><td>Album not found</td></tr>";
                document.getElementById("results").innerHTML += string;
            }
            else
            {
                //More information, see:
                //https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch
                let album = received_song.album.title;
                if(saveResults())
                {
                    //Do not add a trailing "/" to the url, this causes errors
                    let post_url = keyadder_local + 'songs';
                    console.log("Posting to " + post_url);
                    //fetch(local_api + 'songs/',
                    fetch(post_url,
                    {
                        method: 'POST',
                        headers: 
                        {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Csrf-Token': '{{csrf_token()}}'
                        },
                        body: JSON.stringify({song:title,artist:artistname,album:album, source:'lastfm'})
                    });
                }
                let string = "<tr><td>Last.fm</td><td>"+title+"</td><td>"+artistname+"</td><td>"+album+"</td></tr>";
                document.getElementById("results").innerHTML += string;
            }   
                
        });
       
    }
    /*
    * 
    Search an album for the given artist and song, by first inquiring for the information of that artist and song.
     * @param {type} artist
     * @param {type} song
     * @return {undefined}     */
    function trackInfoLastFmForAlbum(artist, song)
    {
        let lastfm_url = "method=track.getInfo&format=json&artist="+artist+"&track="+song;
        let url = keyadder_lastfm + "/" + lastfm_url;
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
            if(typeof received_song.album === 'undefined')
            {
                console.log("Album is undefined, result will not be stored");
                //We add this, for good measure
                let string = "<tr><td>Last.fm</td><td>"+title+"</td><td>"+artistname+"</td><td>Album not found</td></tr>";
                document.getElementById("results").innerHTML += string;
            }
            else
            {
                let album = received_song.album.title;
                albumInfoLastFm(album,artistname);    
            }
        }); 
    }
    
    function artistInfoLastFm(artist)
    {
        //let url = 'http://ws.audioscrobbler.com/2.0/?method=artist.getInfo&api_key=1c29722debda9b327250154f911004b6&artist='+artist+'&format=json';
        let lastfm_url = "method=artist.getInfo&format=json&artist="+artist;
        let url = keyadder_lastfm + "/" + lastfm_url;
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
            if(typeof response.artist === 'undefined')
            {
                //Do nothing, faulty data
            }
            else
            {
                let received_artist = response.artist;
                //let title = received_artist.name;
                let artistname = received_artist.name;
                let img_url = received_artist.image[0]['#text'];
                if(saveResults())
                {
                    //Do not add a trailing "/" to the url, this causes errors
                    let post_url = keyadder_local + 'artists';
                    //let post_url = local_api + 'artists/';
                    fetch(post_url,
                    {
                        method: 'POST',
                        headers: 
                        {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Csrf-Token': '{{csrf_token()}}'
                        },
                        body: JSON.stringify({artist:artistname,img_url:img_url, source:'lastfm'})
                    });
                }
                let string = "<tr><td>Last.fm</td><td>"+artistname+"</td><td><img src=\""+img_url+"\" alt=\"No image found\"></td></tr>";

                document.getElementById("results").innerHTML += string;
            }
        });
       
    }
    
    function albumInfoLastFm(album, artist)
    {
        //let url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=1c29722debda9b327250154f911004b6&album="+album+"&artist="+artist+"&format=json";
        let lastfm_url = "method=album.getinfo&format=json&album="+album+"&artist="+artist;
        let url = keyadder_lastfm + "/" + lastfm_url;
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
            if(typeof response.album === 'undefined')
            {
                //Do nothing, faulty data
            }
            else
            {
                let received_album = response.album;
                //let title = received_artist.name;
                let albumname = received_album.name;
                let artistname = received_album.artist;
                let img_url = received_album.image[2]['#text'];
                if(saveResults() && img_url !== null)
                {
                    //Do not add a trailing "/" to the url, this causes errors
                    let post_url = keyadder_local + 'albums';
                    //let post_url = local_api + 'albums/';
                    fetch(post_url,
                    {
                        method: 'POST',
                        headers: 
                        {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Csrf-Token': '{{csrf_token()}}'
                        },
                        body: JSON.stringify({artist:artistname,img_url:img_url, album:albumname, source:'lastfm'})
                    });
                }
                let string = "<tr><td>Last.fm</td><td>"+albumname+"</td><td>"+artistname+"</td><td><img src=\""+img_url+"\" alt=\"No image found\"></td></tr>";
                document.getElementById("results").innerHTML += string;
            }
        });
    }
    function searchLocalDatabaseSong()
    {
        //let base = local_api;
        let base = keyadder_local;
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
        
        console.log("Using url : " + url);
        
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
            console.log(response);
            for(var i = 0;i<response.length;i++)
            {
                let song_id = response[i].id;
                let title = response[i].title;
                let artist_id = response[i].artist_id;
                
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
                        let string = "<tr><td>Local database</td><td><a href=\"../music/songs/" + song_id + "\">"+
                                title + "</a></td><td><a href=\"../music/artists/" + artist_id + "\">"+artistname+
                                "</a></td><td><a href=\"../music/albums/" + album_id + "\">"+albumname+"</a></td></tr>"
                        document.getElementById("results").innerHTML += string;
                    });
                });
            }
        });
        
    }
    
    function searchLocalDatabaseArtist()
    {
        //let base = local_api + "/artists";
        let base = keyadder_local + "artists";
        let url = base;
        //let url = "http://127.0.0.1:8000/api/artists";

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
        
        console.log("Testing url : " + url);
        
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
            console.log(response);
            for(var i = 0;i<response.length;i++)
            {
                let artist_id = response[i].id;
                let artist = response[i].name;
                let img_url = response[i].img_url;
                let string = "<tr><td>Local database</td><td><a href=\"../music/artists/" + artist_id + "\">"+artist+"</a></td><td><img src=\""+img_url+"\" alt=\"No image found\"></td></tr>";
                document.getElementById("results").innerHTML += string;
            }
        });
        
    }
    
    function searchLocalDatabaseAlbum()
    {
        //let base = "http://127.0.0.1:8000/api/";
        let base = keyadder_local;
        let url = base + "albums";
        //let url = base + "albums";

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
        
        console.log("Testing url : " + url);
        
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
                let album_id = response[i].id;
                let album = response[i].name;
                let artist_id = response[i].artist_id;
                let img_url = response[i].img_url;
                
                let artist_url = base + "artists/" + artist_id;
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
                    let artist = response.name;
                    let string = "<tr><td>Local database</td><td><a href=\"../music/albums/"+ album_id +"\">"+album+
                                 "</a></td><td><a href=\"../music/artists/"+ artist_id +"\">"+artist+"</a></td><td><img src=\""+img_url+"\" alt=\"No image found\"></td></tr>";
                    document.getElementById("results").innerHTML += string;
                });
            }
        });
    }
    
    function searchSongs()
    {
        let initialString = "<thead><tr><th>Source</th><th>Title</th><th>Artist</th><th>Album</th></tr></thead><tbody id=\"results\"></tbody>";
        document.getElementById("base_table").innerHTML = initialString;
        if(useLastFM())
        {
            searchLastFmSong();
        }
        if(useLocalDatabase())
        {
            searchLocalDatabaseSong();
        }
    }
    
    function searchArtists()
    {
        document.getElementById("base_table").innerHTML = "<thead><tr><th>Source</th><th>Artist</th><th>Image</th></tr></thead><tbody id=\"results\"></tbody>";
        if(useLastFM())
        {
            searchLastFmArtist();
        }
        if(useLocalDatabase())
        {
            searchLocalDatabaseArtist();
        }
    }
    
    function searchAlbums()
    {
        document.getElementById("base_table").innerHTML = "<thead><tr><th>Source</th><th>Album</th><th>Artist</th><th>Image</th></tr></thead><tbody id=\"results\"></tbody>";
        if(useLastFM())
        {
            searchLastFmAlbum();
        }
        if(useLocalDatabase())
        {
            searchLocalDatabaseAlbum();
        }
    }
    
    function isSufficientlyIncludedType()
    {
        return includeArtist() || includeAlbum() || includeSong();
    }
    
    function isSufficientlyIncludedSource()
    {
        return useLastFM() || useLocalDatabase();
    }
    
    function search()
    {
        //Check that at least one of the three search parameters (song, artist, album) is included
        //Also check that at least one source will be used
        if(isSufficientlyIncludedType() && isSufficientlyIncludedSource())
        {
            document.getElementById("error_message").innerHTML = "";
            let searchType = document.getElementById("operation").value;
            if(searchType==="Songs")
            {
                searchSongs();
            }
            else if (searchType==="Artists")
            {
                searchArtists();
            }
            else
            {
                searchAlbums();
            }
        }
        else
        {
            let error = document.getElementById("error_message");
            error.style.color = 'red';
            if(isSufficientlyIncludedType())
            {
                error.innerHTML = "Please select at least one source of data";
            }
            else
            {
                error.innerHTML = "Please select at least one search parameter";
            }
        }
    }
</script>

<p>
    If only one song corresponding to the given title is in the database, it will be returned. Otherwise, a list of possible songs will be returned.
</p>
    <form method="post" action=" ../songs" target="_blank">
        Select the type to search for:<br>
        <select id="operation">
            <option value="Songs">Songs</option>
            <option value="Artists">Artists</option>
            <option value="Albums">Albums</option>
        </select> <br><br>
        
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="type" value="list">
        Song:<br>
        <input type="text" name="song" value="Song" id="songtitle"> <input type="checkbox" name="includeSong" value="IncludeSong" id="includeSong"> Include song <br><br>
        
        Artist:<br>
        <input type="text" name="artist" value="Artist" id="artistname"> <input type="checkbox" name="includeArtist" value="IncludeArtist" id="includeArtist"> Include artist <br><br>
        
        Album:<br>
        <input type="text" name="album" value="Album" id="albumname"> <input type="checkbox" name="includeAlbum" value="IncludeAlbum" id="includeAlbum"> Include album <br><br>
        <table>
            <tr>
                <td>
                    <input type="checkbox" name="lastfm" id="lastfm" value="Lastfm"> Last.fm
                </td>
                <td>
                    <input type="checkbox" name="local" id="local" value="Local"> Local database
                </td>
                <td>
                    <input type="checkbox" name="save" id="save" value="Save"> Save search results
                </td>
            </tr>
        </table>

        <input type="button" value="Submit" onclick="search()">
    </form>


<div id="error_message">
    
</div>

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
}

.fixed_header thead {
  background: black;
  color:#fff;
}

.fixed_header td {
  padding: 10px;
  text-align: left;
  width: 25%;
}

.fixed_header th{ 
  padding: 10px;
  text-align: left;
  width: 7%;
}
</style>
<br>
<div id="query_results">
    <table id="base_table" class="fixed_header">
    </table>
</div>
@stop