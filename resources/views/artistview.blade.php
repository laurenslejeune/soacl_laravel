@extends('master')

@section('titel', "Artist")

@section('inhoud')
    <h1>{{$artist->name}}</h1>
    
    <h3>Albums</h3>
    <div>
        <table>
            @foreach ($albums as $album)
            <tr>
                <td>
                    <a href="../albums/{{$album->id}}">{{$album->name}}</a>
                </td>
            </tr>
            @endforeach            
        </table>
    </div>
    
    <h3>Wikipedia information</h3>
    @if ($artist->wikipedia_url != '')
    <div> 
        <object type="text/html" data="{{$artist->wikipedia_url}}" width="80%x" height="400px" style="overflow:auto;border:2px black">
        </object>
        <form action="../artists/{{$artist->id}}/edit">
            <input type="submit" name="selection" value="Select">
        </form>
     </div>
    @else
    <div>
        <p>Currently no Wikipedia page is linked to this artist. Press select to search for a page.</p>
        <form action="../artists/{{$artist->id}}/edit">
            <input type="submit" name="selection" value="Select">
        </form>
    </div>
    @endif
@stop
