@extends('master')

@section('titel','Playlists') 
@section('header','Overview of all playlists')
@section('inhoud')

<script type="text/javascript">

    let local_api = "../add_api_key/local/";
    function deletePlaylist(playlist_id)
    {
        let url = local_api + 'playlists/' + playlist_id;
        console.log('Deleting ' + url)
        fetch(url,
        {
            method: 'DELETE',
            headers: 
            {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Csrf-Token': '{{csrf_token()}}'
            }
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
                return Promise.reject("Failed to delete playlist " + playlist_id);
            }
        })
        .then(response => 
        {
            location.reload(); 
        });
    }
    
</script>

<div>
    <h4>Create a new playlist</h4>
    <form method="get" action="../music/playlists/create">
        <input type="submit" value="Create">
    </form>
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
<div>
    <h4>Current playlists</h4>
    <table class="fixed_header">
        <thead>
            <tr>
                <th>
                     Name

                </th>
                <th>
                     Description

                </th>
                <th>
                     Number of songs

                </th>
                <th>
                     Remove playlist
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($playlists as $playlist)
                <tr>
                    <td>
                         <a href="../music/playlists/{{$playlist->id}}">{{$playlist->name}}</a>

                    </td>
                    <td>
                        {{$playlist->description}}

                    </td>
                    <td>
                        {{$playlist->songs()->count()}}
                    </td>
                    <td>
                        <input type="button" value="Delete" onclick="deletePlaylist({{$playlist->id}})">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop