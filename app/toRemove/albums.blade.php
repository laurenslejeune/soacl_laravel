@extends('master')

@section('titel','Albums') 
@section('header','Overview of all albums')
@section('welkom','blank')

@section('inhoud')

    @foreach($albums as $album)
    <div>
        <p>Name = {{$album->name}}</p>
        <p>Artist = {{$album->artist()->first()->name}}</p>
        
    </div>
    @endforeach
@stop