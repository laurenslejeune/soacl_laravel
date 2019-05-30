@extends('master')

@section('titel','Home') 
@section('header','Home')
@section('welkom','General information')

@section('inhoud')
<div>
    <ul>
        <li>Author: Laurens Le Jeune</li>
        <li>Course: SOA and Cloud Computing</li>
    </ul>
    <p>Obligatory addition (because of the used translation service):</p>
    <a href="https://translate.yandex.com">Powered by Yandex.Translate</a>
    <br>
    <h4>Created services (besides the REST service in Laravel, which also includes the database).</h4>
    <ul>
        <li><a href="http://lyricsanalyzerapi.westeurope.azurecontainer.io/documentation">Lyrics analyzer REST api in Flask, Python</a></li>
        <li><a href="http://hasherapi.westeurope.azurecontainer.io/">Hash function REST api in Node.JS</a></li>
        <li><a href=".">Translation SOAP api in C#</a>
    </ul>
</div>
@stop