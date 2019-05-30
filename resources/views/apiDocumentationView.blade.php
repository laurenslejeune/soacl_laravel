@extends('master')

@section('titel','API Documentation') 
@section('header','API Documentation')
@section('welkom','General information')


@section('inhoud')
<div>
    <p>This API supports a number of operations. More precisely, it is possible to:</p>
     <ul>
        <li>Translate lyrics from English to Dutch using <a href="https://translate.yandex.com/">Yandex</a></li>
        <li>Search for songs, albums and artists in the database</li>
        <li>Manipulate the songs, artists and albums in the database</li>
        <li>Manage playlists</li>
    </ul>
    <p>It is important to note that, unless specified otherwise, all GET responses are in JSON. The responses for other HTTP requests are in plain text.</p>
</div>

<div>
    <h2>API Key</h2>
    <p>This API uses API keys for authenticantion. In order to be able to use its functionality, an API keys must be added to each request.
    If you don't have an API key currently, create an account <a href="http://laurenslaravelwebapp.azurewebsites.net/users/">here</a>. Users receive one API key.
    If you have forgotten your API key, please retrieve it <a href="http://laurenslaravelwebapp.azurewebsites.net/users/">here</a>.</p>
</div>

<div>
    <h2>Translation</h2>
    <p>It is possible to translate the lyrics of a song stored in the database (from English to Dutch).
       In order to do this, a GET request should be sent to the following url:</p>
    <p><a href="../api/translate/en-nl/1/api_key=your_api_key">"../api/translate/en-nl/SONG_ID/api_key=your_api_key"</a></p>
    <p>SONG_ID in this context is the id of the song as it is stored in the database.
       Note that the lyrics will only be translated if they are stored in the database. The response of this function is in plain text.</p>
</div>
<div>
    <h2>Search function</h2>
    <p>If it is required to search the database for specific songs, artists or albums, the API search functions may be used.
    The main urls to send a GET request to are</p>
    <ul>
        <li><a href="../api/songs/api_key=your_api_key">"../api/songs/api_key=your_api_key"</a></li>
        <li><a href="../api/albums/api_key=your_api_key">"../api/albums/api_key=your_api_key"</a></li>
        <li><a href="../api/artists/api_key=your_api_key">"../api/artists/api_key=your_api_key"</a></li>
    </ul>
    <p>In and of theirselves, these urls will return an overview of respectively all songs, albums or artists in the database. It is however
    possible to add search parameters to these urls to obtain more specific results.</p>
    <ul>
        <li>"/song/SONGTITLE"</li>
        <li>"/album/ALBUMNAME/"</li>
        <li>"/artist/ARTISTNAME/"</li>
    </ul>
    <p>The order of these parameter is of no relevance, and their use is optional.</p>
    <p>In the search after a song, these parameters will do the following:</p>
    <ul>
        <li>"/song/SONGTITLE/" will only include songs that have SONGTITLE in their title.</li>
        <li>"/album/ALBUMNAME/" will only include songs that are part of an album that bas ALBUMNAME in its name.</li>
        <li>"/artist/ARTISTNAME/" will only include songs that are written by an artist that has ARTISTNAME in its name.</li>
    </ul>
    <p>If for example the database must be inquired after the song "How you remind me" of "Nickelback", 
        specifically from the "Silver Side Up" album, the following url can be used.
    </p>
    <p><a href="../api/songs/song/How you remind me/album/Silver Side Up/artist/Nickelback/api_key=your_api_key">"../api/songs/song/How you remind me/album/Silver Side Up/artist/Nickelback/api_key=your_api_key"</a></p>
    <p>Similarly, the next url searches for an album of "Nickelback" that includes the song "How you remind me".</p>
    <p><a href="../api/albums/song/How you remind me/artist/Nickelback/api_key=your_api_key">"../api/songs/song/How you remind me/artist/Nickelback/api_key=your_api_key"</a></p>
    <p>Searching for an artist that has written "How you remind me", can then for example be done using:</p>
    <p><a href="../api/artists/song/How you remind me/api_key=your_api_key">"../api/artists/song/How you remind me/api_key=your_api_key"</a></p>
</div>
<div>
    <h2>Database manipulation</h2>
    <p>Besides searching for songs, albums and artists, it also possible to view specific items in the database. 
        When an id is acquired (for example by using the search function), this can be used to view that item in the database:
    </p>
    <ul>
        <li><a href="../api/songs/1/api_key=your_api_key">"../api/songs/ID/api_key=your_api_key" returns the song with id = ID.</a></li>
        <li><a href="../api/albums/1/api_key=your_api_key">"../api/albums/ID/api_key=your_api_key" returns the album with id = ID.</a></li>
        <li><a href="../api/artists/1/api_key=your_api_key">"../api/artists/ID/api_key=your_api_key" returns the artist with id = ID.</a></li>
    </ul>
    <p>Moreover, storing items in the database is also possible. All POST request headers correspond to:</p>
    <ul>
        <li>'Accept': 'application/json'</li>
        <li>'Content-Type': 'application/json'</li>
    </ul>
    <h5>Storing songs</h5>
    <p>Storing songs in the database can be done by sending a POST request to:</p>
    <p><a href="../api/songs/api_key=your_api_key">"../api/songs/api_key=your_api_key"</a></p>
    <p>This POST request should contain the following properties:</p>
    <ul>
        <li>song:   Title of the song to add</li>
        <li>album:  Album of the song to add</li>
        <li>artist: Artist of the song to add</li>
    </ul>
    <p>These items are all mandatory.</p>
    <h5>Storing albums</h5>
    <p>Storing an album in the database can be done by sending a POST request to:</p>
    <p><a href="../api/albums/api_key=your_api_key">"../api/albums/api_key=your_api_key"</a></p>
    <p>This POST request should contain the following properties:</p>
    <ul>
        <li>img_url:    Url to an image depicting the album</li>
        <li>album:      Album of the song to add</li>
        <li>artist:     Artist of the song to add</li>
    </ul>
    <p>These items are all mandatory. If no image is available, an empty string should still be sent.</p>
    
    <h5>Storing artists</h5>
    <p>Storing an artist in the database can be done by sending a POST request to:</p>
    <p><a href="../api/artists/api_key=your_api_key">"../api/artists/api_key=your_api_key"</a></p>
    <p>This POST request should contain the following properties:</p>
    <ul>
        <li>img_url:    Url to an image depicting the artist</li>
        <li>artist:     Artist of the song to add</li>
    </ul>
    <p>These items are all mandatory. If no image is available, an empty string should still be sent.</p>
</div>
<div>
    <h2>Playlists</h2>
    <p>A playlist can be accessed by sending a GET request to:</p>
    <ul>
        <li>GET: <a href="../api/playlists/api_key=your_api_key">"../api/playlists/api_key=your_api_key"</a> to get all playlists</li>
        <li>GET: <a href="../api/playlists/1/api_key=your_api_key">"../api/playlists/ID/api_key=your_api_key"</a> to get a specific playlist</li>
    </ul>
    <p>ID in this context is the ID of the required playlist.<br>
    Possibly, it is required to create a new playlist, or to delete an existing one. Creating and deleting playlists can be done by:</p>
    <ul>
        <li>POST:   <a href="../api/playlists/api_key=your_api_key">"../api/playlists/api_key=your_api_key"</a> to create a playlist.</li>
        <li>DELETE: <a href="../api/playlists/1/api_key=your_api_key">"../api/playlists/ID/api_key=your_api_key"</a> to delete a playlist.</li>
    </ul>
    <p>
        A POST request for creating a playlist should include the following parameters:
    </p>
    <ul>
        <li>name:            The name of the playlist</li>
        <li>description:     A (brief) description of the playlist</li>
        <li>api_key:         Your api_key, so the playlist can connected to your account</li>
    </ul>
    <p>
        A DELETE request on the other hand should only include the id of the playlist you wish to delete.<br>
        Finally, adding songs to the playlist and removing songs from a playlist can be done using a PUT request:<br>
    </p>
    <ul>
        <li>PUT: <a href="../api/playlists/1/api_key=your_api_key">"../api/playlists/ID/api_key=your_api_key"</a></li>
    </ul>    
    <p>
        This stores/removes the song added in the request inside the playlist with id = ID. The request should include the parameters:
    </p>
    <ul>
        <li>song_id:   Id of the song in the database</li>
        <li>type:      "song_addition" to add a song, "song_removal" to remove a song</li>
    </ul>
    
</div>

@stop