@extends('master')

@section('titel',$name) 
@section('header','Overview of the playlist ' . $name)
@section('welkom','General information')


<script type="text/javascript">
    
    let local_api = "../../add_api_key/local/";
    let local_web = "../";
    
    //let local_api = "http://127.0.0.1:8000/api/";
    //let local_web ="http://localhost/mediamanager/public/music/";
    
    /*
    function playVideos()
    {
        let playlist_id = {!!$playlist_id!!};
        let url = local_api + "playlists/" + playlist_id;
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
            let id_list = "";
            for(var i = 0;i<response.length;i++)
            {
                let youtube_id = response[i].youtube_id;
                if(youtube_id !== "")
                {
                    id_list+=youtube_id;
                }
            }
            let iframe = "<iframe width=\"720\" height=\"405\" src=https://www.youtube.com/embed/"+id_list[0]+"?playlist=\""+id_list+"?autoplay=1\"frameborder=\"0\" allowfullscreen>";
            
            document.getElementById("playvideos").innerHTML = iframe;
        });
    }
    */
    function playVideos()
    {
        let playlist_id = {!!$playlist_id!!};
        let url = local_api + "playlists/" + playlist_id;
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
            let id_list = [];
            let html_list = [];
            for(var i = 0;i<response.length;i++)
            {
                let youtube_id = response[i].youtube_id;
                if(youtube_id !== "")
                {
                    id_list.push(youtube_id);
                }
            }
            
            let video_ids = "";
            for(var i = 1; i<id_list.length;i++)
            {
                if(i > 1)
                {
                    video_ids += ",";
                }
                video_ids += id_list[i];
            }
            let allows = "allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen";
            let iframe = "<iframe width=\"720\" height=\"405\" src=https://www.youtube.com/embed/"+id_list[0]+"?autoplay=1&loop=1&playlist="+video_ids+" frameborder=\"0\" "+allows+"></iframe>";
            document.getElementById("playvideos").innerHTML = iframe;
        });
    }
    
    function showCurrentPlaylist()
    { 
        let playlist_id = {!!$playlist_id!!};
        fetch(local_api + 'playlists/' + playlist_id).
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
            //let totalString =  "<table>";
            let totalString = '';
            //document.getElementById("currentPlaylist").innerHTML = "<table>";
            for(var i = 0;i<response.length; i++)
            {
                let title = response[i].title;
                let song_id = response[i].song_id;
                let song_url = local_web + "songs/" + song_id;
                let song_html = "<a href=\""+ song_url +"\">" + title + "</a>";
        
                let artist = response[i].artistname;
                let artist_id = response[i].artist_id;
                let artist_url = local_web + "artists/" + artist_id;
                let artist_html = "<a href=\""+ artist_url +"\">" + artist + "</a>";
                
                
                let album = response[i].albumname;
                let album_id = response[i].album_id;
                let album_url = local_web + "albums/" + album_id;
                let album_html = "<a href=\""+ album_url +"\">" + album + "</a>";
                                
                let string = "<tr><td>"+song_html+"</td><td>"+artist_html+"</td><td>"+album_html+"</td></tr>";
                totalString += string;
                //document.getElementById("currentPlaylist").innerHTML += string;
            }
            document.getElementById("songs").innerHTML = totalString;
        });
        
    }
    
    window.onload = showCurrentPlaylist({!!$playlist_id!!});
</script>


@section('inhoud')

<div>
    <table>
        <tr>
            <td>
                <p><input type="button" value="Play" name="play" onclick="playVideos()"/></p>
            </td>
            <td>
                <form method="get" action="../playlists/{{$playlist_id}}/edit">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" value="Edit" name="Edit"/>
                </form>
                
            </td>
        </tr>
    </table>
    
</div>

<div id="playvideos">
    
</div>

<br>
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
  height: 400px;
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
  width: 33%;
}

.fixed_header th{ 
  padding: 10px;
  text-align: left;
  width: 5%;
}
</style>
<br>
<div id="currentPlaylist" class="fixed_header">
    <table>
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
            </tr>
        </thead>
        <tbody id='songs'>
            
        </tbody>
    </table>
</div>


@stop