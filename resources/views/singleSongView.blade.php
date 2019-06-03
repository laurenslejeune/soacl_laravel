@extends('master')

@section('titel',$song) 
@section('header','Overview of '.$song)
@section('welkom','General information')

@push('styles')
    <link href="{{ asset('css/myStyles.css') }}" rel="stylesheet">
@endpush

@section('inhoud')

<script type="text/javascript">
    
    let local_api = "../../add_api_key/local/";

    /**
     * Toggle the lyrics between English and Dutch
     */
    function toggleTranslation()
    {
        if(document.getElementById("translated_lyrics").innerHTML === "")
        {
            getTranslationEnglishToDutch();
            document.getElementById("original_lyrics").style.display = 'none';
            document.getElementById("translated_lyrics").style.display = 'block';
        }
        else
        {
            if(document.getElementById("translated_lyrics").style.display === 'none')
            {
                document.getElementById("original_lyrics").style.display = 'none';
                document.getElementById("translated_lyrics").style.display = 'block';
            }
            else
            {
                document.getElementById("original_lyrics").style.display = 'block';
                document.getElementById("translated_lyrics").style.display = 'none';
            }
        }
    }
        
    /**
     * For the English lyrics, get the translation to Dutch
     */
    function getTranslationEnglishToDutch()
    {
        let url = local_api + "translate/en-nl/" + {!!$song_obj->id!!};
        
        fetch(url).then(response=>
        {
            if(response.ok)
            {
                console.log(response);
                return response.text();
            }
            else
            {
                console.log(response);
                return Promise.reject("Translation not found");
            }
        })
        .then(response => 
        {
            document.getElementById("translated_lyrics").innerHTML = response;
        });
    }
    
    

</script>

<table>
    <tr>
        <td>
            Title
        </td>
        <td>
            <a href="../songs/{{$song_obj->id}}">{{$song}}</a>
        </td>
    </tr>
    <tr>
        <td>
            Artist
        </td>
        <td>
            <a href="../artists/{{$song_obj->artist_id}}">{{$artist}}</a>
        </td>
    </tr>
    <tr>
        <td>
            Album
        </td>
        <td>
            <a href="../albums/{{$song_obj->album_id}}">{{$album}}</a>
        </td>
    </tr>
</table>

<h3>Youtube</h3>

@if ($chooseYoutube == true)
<p>No valid youtube url was stored for this song. Select the correct video from the ones displayed below.</p>
    <table>
    @foreach ($youtube as $item)
    <tr>
        <td>
            {{$item->snippet->title}}
        </td>
        <td>        
            <img src="{{$item->snippet->thumbnails->default->url}}" alt="Thumbnail"> 
        </td>
        <td>        
            <form action="../songs/{{$song_obj->id}}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="youtube_id" value="{{$item->id->videoId}}">
                <input type="hidden" name="intention" value="add_youtube">
                <input type="submit" value="Submit">
            </form> 
        </td>
    </tr>
    @endforeach
</table>
    
@else
    <div>
        {!!$youtube!!}
        <form action="../songs/{{$song_obj->id}}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="youtube_id" value="">
            <input type="hidden" name="intention" value="clear_youtube">
            <input type="submit" value="Clear youtube data">
        </form> 
    </div>
@endif
<h3>Lyrics</h3>

<style type="text/css">
    .original
    {
        display: block;
    }
    .translation /*Dit is een klasse*/
    {
        display: none;
    }
    
    #most_common 
    {
        width: 300px;
        display: inline-block;
        /*float:left;  add this */
    }
    
    #sentiment 
    {
        width: 300px;
        display: inline-block;
        /*float: left;  add this */
    }
    
    #lyrics_analysis_wrapper
    {
            /*overflow: hidden;  add this to contain floated children */
    }
</style>

<div id="original_lyrics" class="original">
    {!!$lyrics!!}
</div>

<div id='translated_lyrics' class="translation"></div>
<input type="button" value="Toggle Translation" name="translate" onclick="toggleTranslation()"/>

<div>
    <br>
    <br>
    <br>
</div>
<div>
    <h3>Lyrics analysis</h3>
    @if($analysis == true)
    <div id="lyrics_analysis_wrapper">
        <div id="most_common">
            <h5>Most common words</h5>
            <table>
                <tr>
                    <th>
                        Word
                    </th>
                    <th>
                        Count
                    </th>
                </tr>
                @foreach ($words as $pair)
                    <tr>
                        <td>
                            {{$pair->word}}
                        </td>
                        <td>
                            {{$pair->count}}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div id="sentiment">
            <h5>Sentiment</h5>
            <table>
                <tr>
                    <td>
                        Negative
                    </td>
                    <td>
                        {{$sentiment->neg}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Neutral
                    </td>
                    <td>
                        {{$sentiment->neu}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Positive
                    </td>
                    <td>
                        {{$sentiment->pos}}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    
                

    @else
        <p>No analysis available</p>
    @endif
</div>
@stop