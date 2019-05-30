@extends('master')

@section('titel','Select wikipedia') 
@section('header','Select the correct wikipedia page')
@section('welkom','Possibilities')

@section('inhoud')

<table>
    
    <tr>
            <th>
                Type
            </th>
            <th>
                Description
            </th>
            <th>
                URL
            </th>
            <th>
                Select
            </th>
        </tr>
    @for ($i = 0; $i < count($choices); $i++)
        <tr>
            <td>
                {{$choices[$i]}}
            </td>
            <td>
                {{$descriptions[$i]}}
            </td>
            <td>
                {{$urls[$i]}}
            </td>
            <td>
                <form action="../{{$id}}" method="post">
                    <!--Note: Since HTML forms only support POST and GET, PUT and DELETE 
                    methods will be spoofed by automatically adding a _method hidden field
                    to your form. (Laravel docs)
                    Note that method="post" is still necessary as method of the form-->
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="wikipedia_selection" value="{{$urls[$i]}}">
                    <input type="submit" name="Selected" value="Select">
                </form>
            </td>
        </tr>
    @endfor
    
</table>

@stop