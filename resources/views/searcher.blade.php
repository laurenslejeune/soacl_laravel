@extends('master')

@section('titel','Song searcher') 
@section('header','Search for a song')

@section('inhoud')

<script type="text/javascript">
    
    let keyadder_lastfm = "../add_api_key/lastfm";
    let keyadder_local = "../add_api_key/local/";
    
    /**
     * Check if the artist needs to be included in the search
     * @return true if the artist needs to be included
     */
    function includeArtist()
    {
        if(!document.getElementById("includeArtist"))
        {
            return false;
        }
        return document.getElementById("includeArtist").checked;
    }
    
    /**
     * Check if the album needs to be included in the search
     * @return true if the album needs to be included
     */
    function includeAlbum()
    {
        if(!document.getElementById("includeAlbum"))
        {
            return false;
        }
        return document.getElementById("includeAlbum").checked;
    }
    
    /**
     * Check if the song needs to be included in the search
     * @return true if the song needs to be included
     */
    function includeSong()
    {
        if(!document.getElementById("includeSong"))
        {
            return false;
        }
        return document.getElementById("includeSong").checked;
    }
    
    /**
     * Check if lastfm needs to be searched
     * @return true if lastfm needs to be searched
     */
    function useLastFM()
    {
        if(!document.getElementById("lastfm"))
        {
            return false;
        }
        return document.getElementById("lastfm").checked;
    }
   
    /**
     * Check if the local database needs to be searched
     * @return true if the local database needs to be searched
     */
    function useLocalDatabase()
    {
        if(!document.getElementById("local"))
        {
            return false;
        }
        return document.getElementById("local").checked;
    }
    
    /**
     * Check if search results (from lastfm) need to be saved in the local database
     * @return true if they need to be saved
     */
    function saveResults()
    {
        if(!document.getElementById("save"))
        {
            return false;
        }
        return document.getElementById("save").checked;
    } 
    
    /**
     * Search LastFM for songs
     */
    function searchLastFmSong()
    {
        if(includeArtist() && includeSong())
        {
            let songtitle = document.getElementById("songtitle").value;
            let artistname = document.getElementById("artistname").value;
            
            let lastfm_url = "method=track.search&track=" + songtitle + "&artist=" + artistname +"&format=json";
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
    
    /**
     * Search LastFM for artists
     */
    function searchLastFmArtist()
    {
        let songtitle = document.getElementById("songtitle").value;
        let artistname = document.getElementById("artistname").value;
        if(includeArtist()&&includeSong())
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
                    //let name = received_songs[i].name;
                    let artist = received_songs[i].artist;
                    artistInfoLastFm(artist)
                }
            });
        }
        else
        {
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
    
    /**
     * Search LastFM for albums
     */
    function searchLastFmAlbum()
    {
        let albumname = document.getElementById("albumname").value;
        let songtitle = document.getElementById("songtitle").value;
        let artistname = document.getElementById("artistname").value;
        
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
    
    /**
     * Get lastfm information about the song (=track) of the specified artist
     * @param string artist Name of the artist
     * @param string song Name of the song
     */
    function trackInfoLastFm(artist, song)
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
                //More information, see:
                //https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch
                let album = received_song.album.title;
                if(saveResults())
                {
                    //Do not add a trailing "/" to the url, this causes errors
                    let post_url = keyadder_local + 'songs';
                    console.log("Posting to " + post_url);
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
     * @param string artist Name of the artist
     * @param string song Name of the song
     *
     */
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
    
    /**
    * Get the LastFM information about the given artist
    * @param string artist Name of the artist
     */
    function artistInfoLastFm(artist)
    {
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
                let artistname = received_artist.name;
                let img_url = received_artist.image[0]['#text'];
                if(saveResults())
                {
                    //Do not add a trailing "/" to the url, this causes errors
                    let post_url = keyadder_local + 'artists';
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
    
    /**
    * Get the LastFm info of the given album by the given artist
    * @param string album Name of the album
    * @param string artist Name of the artist
    */
    function albumInfoLastFm(album, artist)
    {
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
                let albumname = received_album.name;
                let artistname = received_album.artist;
                let img_url = received_album.image[2]['#text'];
                if(saveResults() && img_url !== null)
                {
                    //Do not add a trailing "/" to the url, this causes errors
                    let post_url = keyadder_local + 'albums';
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
    
    /*
    * Search the local database for a song
     */
    function searchLocalDatabaseSong()
    {
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
    
    /*
    * Search the local databse for an artist
     */
    function searchLocalDatabaseArtist()
    {
        let base = keyadder_local + "artists";
        let url = base;

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
    
    /**
    * Search the local database for an album
    */
    function searchLocalDatabaseAlbum()
    {
        let base = keyadder_local;
        let url = base + "albums";

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
    
    /**
     * Search for songs
     */
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
    
    /**
     * Search for artists
     */
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
    
    /**
     * Search for albums
     */
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
    
    /**
     * Check if enough types are included (it is impossible to search for something, if you cannot include song, album or artist)
     * @type true if at least one type is included
     */
    function isSufficientlyIncludedType()
    {
        return includeArtist() || includeAlbum() || includeSong();
    }
    
    /**
     * Check if sufficient sources are included (it is impossible to search something that is not specified)
     * @return true if at least one source is specified
     */
    function isSufficientlyIncludedSource()
    {
        return useLastFM() || useLocalDatabase();
    }
    
    /**
     * Search for the specified items
     */
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

<div>
    <h5>Instruction</h5>
    <p>Search online for songs, artists and albums by specifying the "Last.fm" source. Due to the implementation of Last.fm, only the following search
    operations are implemented</p>
    <p>Searching for songs:</p>
    <ul>
        <li>Song + Artist</li>
        <li>Song</li>
    </ul>
    <p>Searching for artists:</p>
    <ul>
        <li>Song + Artist</li>
        <li>Artist</li>
    </ul>
    <p>Searching for albums:</p>
    <ul>
        <li>Song + Artist</li>
        <li>Album</li>
    </ul>
    <p>Searching the local database can use any combination of search parameters for songs, artists and albums</p>
</div>
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