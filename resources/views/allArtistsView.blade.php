@extends('master')

@section('titel','Artists') 
@section('header','Overview of all stored artists')

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
  width: 21%;
}
</style>
<br><br>
<table class="fixed_header">
    <thead>
        <tr>
            <th>
                Artist
            </th>
            <th>
                Wikipedia page
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($artists as $artist)
            <tr>
                <td>
                     <a href="../music/artists/{{$artist->id}}">{{$artist->name}}</a>

                </td>
                <td>
                    <a href="{{$artist->wikipedia_url}}">Wikipedia</a>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
@stop