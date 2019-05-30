@extends('master')

@section('titel',$album)
@section('header','Overview of '.$album)
@section('inhoud')

<script type="text/javascript">
    let local_api = "../../add_api_key/local/";
    //let local_api = "http://127.0.0.1:8000/api/";
    //let local_web = "http://localhost/mediamanager/public/music/songs/";
    let local_web = "../songs/";
    function addSongs()
    {
        let album_id = {!!$album_id!!};
        let album = "{!!$album!!}";
        fetch(local_api + 'albums/add/' + album_id)
                .then(response=>
        {
            if(response.ok)
            {
                console.log(response);
                return response.text();
            }
            else
            {
                console.log(response);
                return Promise.reject("Unable to contact the local database");
            }
        })
                .then(response=>
        {
            if(response === "No additional songs found")
            {
                document.getElementById("songlist").innerHTML += "<p>No additional songs found</p>";
            }
            else
            {
                fetch(local_api + 'songs/album/'+album)
                    .then(response=>
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
                        .then(response=>
                {
                    console.log(response);
                    let totalString = "<table>";
                    for(var i = 0;i<response.length; i++)
                    {
                        if(response[i].album_id === album_id)
                        {
                            let title = response[i].title;
                            let song_id = response[i].id;
                            let song_url = local_web + song_id;
                            let song_html = "<a href=\""+ song_url +"\">" + title + "</a>";
                            totalString += "<tr><td>" + song_html + "</tr></td>";
                        }
                        document.getElementById("songlist").innerHTML = totalString + "</table>";
                    }
                })
            }
            
        });
    }
</script>

<style type="text/css">

.info tr td{ 
  padding: 10px;
  text-align: center;
}
</style>

<table class="info">
    <tr>
        <td>
            <table>
                <tr>
                    <td>
                        <b>Name:</b>
                    </td>
                    <td>
                        <a href="../albums/{{$album_id}}">{{$album}}</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Artist:</b>
                    </td>
                    <td>
                        <a href="../artists/{{$artist_id}}">{{$artist}}</a>
                    </td>
                </tr>
            </table>
        </td>
        <td>
            <img src="{{$album_img}}" alt="No image found">
        </td>
    </tr>
</table>


<h3>Songs</h3>

<div id="songlist">
    <table>
        @foreach ($songs as $song)
        <tr>
            <td>
                <a href="../songs/{{$song->id}}">{{$song->title}}</a>
            </td>
        </tr>
        @endforeach
    </table>
</div>


<div>
    <input type="button" value="Find more songs" onclick="addSongs()">
</div>
@stop