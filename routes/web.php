<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

//Waarom werkte dit niet:
//1) Trailing slashes mogen niet in eeb POST request
//2) CSRF token moet toegevoegd worden met de header X-Csrf-Token
Route::post('/test',function()
{
    return 'aap';
});

Route::put('/test/{id}',function(Request $request,$id)
{
    return $id;
});

//Route::get('/','LastFMController@index');
//Route::get('/test','LastFMController@index');
//Route::get('/test/{param}','LastFMController@show');
//Route::resource('/music',function (){return view('home');});
Route::get('/music/', function() { return view('home');} );
Route::get('/about/', function() { return view('about');} );
Route::get('/music/search', function() { return view('searcher');} );
Route::resource('/music/musicmanager','LastFMController');
Route::resource('/music/songs','SongController');
Route::resource('/music/albums','AlbumController');
Route::resource('/music/artists','ArtistController');
Route::resource('/music/playlists','PlaylistController');

Route::get('/users/email_in_use/{email}','Controller@isEmailAddressAlreadyUsed');
Route::post('/users/add','Controller@addUser');
Route::post('/users/getAPI','Controller@getAccessKey');
Route::get('/users/',function()
{
    return view('apiKeyRequest');
});
/*
 * All routes below this point serve to connect to an API from JS. They are used
 * to add an API key to the HTTP request. This way, not API keys have to hard coded
 * in JS, which would be publicly available.
 */
//Adding API key to a search:
//Post requests:
Route::post('/add_api_key/local/songs','GeneralController@songs_store');
Route::post('/add_api_key/local/albums','GeneralController@albums_store');
Route::post('/add_api_key/local/artists','GeneralController@artists_store');

//Put requests:
Route::put('/add_api_key/local/playlists/{id}','GeneralController@playlists_put');

//Delete requests:
Route::delete('/add_api_key/local/playlists/{id}','GeneralController@playlists_delete_id');

//Adding songs to an album:
Route::get('/add_api_key/local/albums/add/{id}','GeneralController@albums_add');

//Translation
Route::get('/add_api_key/local/translate/en-nl/{id}','GeneralController@translation_en_nl');

//Searching:
//For LastFM:
Route::get('/add_api_key/lastfm/{url}','GeneralController@addApiKeyLastFM');
//For the local API:
//Problem: the presences of slashes "/" in the request urls of the local database,
//makes it very impractical to directly send the url to a php function (the slashes
//in the message would change the address)
//That creates the necessity to create a seperate function for each lookup:
//Playlists:
Route::get('/add_api_key/local/playlists/{id}','GeneralController@playlists_get_id');
////Songs:
//General:
Route::get('/add_api_key/local/songs/{id}','GeneralController@songs_get_id');

Route::get('/add_api_key/local/songs/song/{song}','GeneralController@songs_search_song');
Route::get('/add_api_key/local/songs/album/{album}','GeneralController@songs_search_album');
Route::get('/add_api_key/local/songs/artist/{artist}','GeneralController@songs_search_artist');
//2 keywords
Route::get('/add_api_key/local/songs/artist/{artist}/album/{album}','GeneralController@songs_search_artist_album');
Route::get('/add_api_key/local/songs/album/{album}/artist/{artist}','GeneralController@songs_search_album_artist');
Route::get('/add_api_key/local/songs/album/{album}/song/{song}','GeneralController@songs_search_album_song');
Route::get('/add_api_key/local/songs/song/{song}/album/{album}','GeneralController@songs_search_song_album');
Route::get('/add_api_key/local/songs/song/{song}/artist/{artist}','GeneralController@songs_search_song_artist');
Route::get('/add_api_key/local/songs/artist/{artist}/song/{song}','GeneralController@songs_search_artist_song');
//3 keywords
Route::get('/add_api_key/local/songs/artist/{artist}/song/{song}/album/{album}','GeneralController@songs_search_artist_song_album');
Route::get('/add_api_key/local/songs/album/{album}/song/{song}/artist/{artist}','GeneralController@songs_search_album_song_artist');
Route::get('/add_api_key/local/songs/song/{song}/artist/{artist}/album/{album}','GeneralController@songs_search_song_artist_album');
Route::get('/add_api_key/local/songs/song/{song}/album/{album}/artist/{artist}','GeneralController@songs_search_song_album_artist');
Route::get('/add_api_key/local/songs/album/{album}/artist/{artist}/song/{song}','GeneralController@songs_search_album_artist_song');
Route::get('/add_api_key/local/songs/artist/{artist}/album/{album}/song/{song}','GeneralController@songs_search_artist_album_song');

//Albums:
//Route::get('/add_api_key/albums/add/{id}','GeneralController@albums_search_song');
//General:
Route::get('/add_api_key/local/albums/','GeneralController@albums_get_index');
Route::get('/add_api_key/local/albums/{id}','GeneralController@albums_get_id');
////Specific search options
//1 keyword
Route::get('/add_api_key/local/albums/song/{song}','GeneralController@albums_search_song');
Route::get('/add_api_key/local/albums/album/{album}','GeneralController@albums_search_album');
Route::get('/add_api_key/local/albums/artist/{artist}','GeneralController@albums_search_artist');

//2 keywords
Route::get('/add_api_key/local/albums/artist/{artist}/album/{album}','GeneralController@albums_search_artist_album');
Route::get('/add_api_key/local/albums/album/{album}/artist/{artist}','GeneralController@albums_search_album_artist');
Route::get('/add_api_key/local/albums/album/{album}/song/{song}','GeneralController@albums_search_album_song');
Route::get('/add_api_key/local/albums/song/{song}/album/{album}','GeneralController@albums_search_song_album');
Route::get('/add_api_key/local/albums/song/{song}/artist/{artist}','GeneralController@albums_search_song_artist');
Route::get('/add_api_key/local/albums/artist/{artist}/song/{song}','GeneralController@albums_search_artist_song');

//3 keywords
Route::get('/add_api_key/local/albums/artist/{artist}/song/{song}/album/{album}','GeneralController@albums_search_artist_song_album');
Route::get('/add_api_key/local/albums/album/{album}/song/{song}/artist/{artist}','GeneralController@albums_search_album_song_artist');
Route::get('/add_api_key/local/albums/song/{song}/artist/{artist}/album/{album}','GeneralController@albums_search_song_artist_album');
Route::get('/add_api_key/local/albums/song/{song}/album/{album}/artist/{artist}','GeneralController@albums_search_song_album_artist');
Route::get('/add_api_key/local/albums/album/{album}/artist/{artist}/song/{song}','GeneralController@albums_search_album_artist_song');
Route::get('/add_api_key/local/albums/artist/{artist}/album/{album}/song/{song}','GeneralController@albums_search_artist_album_song');


//Artist API
//Specific search options:
//General:
Route::get('/add_api_key/local/artists/{id}','GeneralController@artists_get_id');
//1 keyword
Route::get('/add_api_key/local/artists/artist/{artist}','GeneralController@artists_search_artist');
Route::get('/add_api_key/local/artists/song/{song}','GeneralController@artists_search_song');
Route::get('/add_api_key/local/artists/album/{album}','GeneralController@artists_search_album');

//2 keywords
Route::get('/add_api_key/local/artists/artist/{artist}/song/{song}','GeneralController@artists_search_artist_song');
Route::get('/add_api_key/local/artists/song/{song}/artist/{artist}','GeneralController@artists_search_song_artist');
Route::get('/add_api_key/local/artists/artist/{artist}/album/{album}','GeneralController@artists_search_artist_album');
Route::get('/add_api_key/local/artists/album/{album}/artist/{artist}','GeneralController@artists_search_album_artist');
Route::get('/add_api_key/local/artists/album/{album}/song/{song}','GeneralController@artists_search_album_song');
Route::get('/add_api_key/local/artists/song/{song}/album/{album}','GeneralController@artists_search_song_album');

//3 keywords
Route::get('/add_api_key/local/artists/song/{song}/album/{album}/artist/{artist}','GeneralController@artists_search_song_album_artist');
Route::get('/add_api_key/local/artists/album/{album}/song/{song}/artist/{artist}','GeneralController@artists_search_album_song_artist');
Route::get('/add_api_key/local/artists/album/{album}/artist/{artist}/song/{song}','GeneralController@artists_search_album_artist_song');
Route::get('/add_api_key/local/artists/artist/{artist}/album/{album}/song/{song}','GeneralController@artists_search_artist_album_song');
Route::get('/add_api_key/local/artists/song/{song}/artist/{artist}/album/{album}','GeneralController@artists_search_song_artist_album');
Route::get('/add_api_key/local/artists/artist/{artist}/song/{song}/album/{album}','GeneralController@artists_search_artist_song_album');



//Route::get('/add_api_key/local_get/{url}','GeneralController@addApiKeyLocalGet');
//Route::get('/add_api_key/local_post/{url}','GeneralController@addApiKeyLocalPost');