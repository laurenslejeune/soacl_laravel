@extends('master')

@section('titel', "Search")
@section('welkom')
    "Zie eens wat een geweldig aanbod op <?php print(now()) ?>"
@stop

@section('inhoud')
    <form method="post" action=" ../music" target="_blank">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        First name:<br>
        <input type="text" name="artist" value="Artist"><br><br>
        <input type="submit" value="Submit">
    </form> 
@stop
