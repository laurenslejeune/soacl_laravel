@extends('master')

@section('titel','Songs') 
@section('header','Overview of all stored songs')
@section('welkom','General information')

@section('inhoud')

<div>
    <form method="post" action="../playlists">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="api_key" value="admin">
        Name<br>
        <input type="text" value="Name" id="name" name="name"><br>
        Description<br>
        <input type="text" value="Description" id="description" name="description"><br>
        <input type="submit" value="Submit">
    </form>
</div>

@stop

