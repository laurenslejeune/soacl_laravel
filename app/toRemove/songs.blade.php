@extends('master')

@section('titel','Albums') 
@section('header','Overview of all albums')
@section('welkom','blank')

@section('inhoud')

    @foreach($songs as $song)
    <div>
        <p>Title = {{$song->name}}</p>
        <p>Artist = {{$song->artist()->first()->name}}</p>
        
    </div>
    @endforeach
@stop