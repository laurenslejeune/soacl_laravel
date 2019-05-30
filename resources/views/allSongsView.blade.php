@extends('master')

@section('titel','Songs') 
@section('header','Overview of all stored songs')

@section('inhoud')

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
<br><br>
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
    <tbody>
        @foreach ($songs as $song)
            <tr>
                <td>
                     <a href="../music/songs/{{$song->song_id}}">{{$song->title}}</a>

                </td>
                <td>
                    <a href="../music/artists/{{$song->artist_id}}">{{$song->artistname}}</a>
                </td>
                <td>
                    <a href="../music/albums/{{$song->album_id}}">{{$song->albumname}}</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop