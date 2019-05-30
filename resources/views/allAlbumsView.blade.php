@extends('master')

@section('titel','Albums') 
@section('header','Overview of all stored albums')

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
                Album
            </th>
            <th>
                Artist
            </th>
            <th>
                Image
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($albums as $album)
            <tr>
                <td>
                     <a href="../music/albums/{{$album->id}}">{{$album->name}}</a>

                </td>
                <td>
                    <a href="../music/artists/{{$album->artist()->first()->id}}">{{$album->artist()->first()->name}}</a>
                </td>
                <td>
                    <img src="{{$album->img_url}}" alt="No image found">
                </td>

            </tr>
        @endforeach
    </tbody>

</table>
@stop