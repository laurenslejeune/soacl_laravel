@extends('master')

@section('titel','Result') 
@section('header','Overview of corresponding songs')
@section('welkom','Select the correct song')

@section('inhoud')

    <table>
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
            <th>
                Select
            </th>
        </tr>
    
    @foreach($songs as $song)
    
        <tr>
            <td>
                {{$song->title}}
            </td>
            <td>
                {{$song->artist()->first()->name}}
            </td>
            <td>
                {{$song->album()->first()->name}}
            </td>
            <td>
                <form action="../music/songs/{{$song->id}}" method="get">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="type" value="single">
                    <input type="submit" name="song_id" value="{{$song->id}}">
                </form>
                <!--<input name="" type="checkbox" value="{{$song->title}}">-->
            </td>
        </tr>
    @endforeach
    </table>
@stop