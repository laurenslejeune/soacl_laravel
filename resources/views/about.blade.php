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
    <h4>Created services</h4>
    <ul>
        <li><a href="http://laurenslaravelwebapp.azurewebsites.net/api/documentation">Database API</a>->
            <a href="https://github.com/laurenslejeune/soacl_laravel">Github</a></li>
        <li><a href="http://lyricsanalyzerapi.westeurope.azurecontainer.io/documentation">Lyrics analyzer REST api in Flask, Python</a>->
            <a href="https://github.com/laurenslejeune/lyricsanalyzer_api">Github</a></li>
        <li><a href="http://hasherapi.westeurope.azurecontainer.io/">Hash function REST api in Node.JS</a>->
            <a href="https://github.com/laurenslejeune/soacl_hasher">Github</a></li>
        <li><a href="https://lyricstranslator.azurewebsites.net/LyricsProcessor.asmx">Translation SOAP api in C#</a>-> 
            <a href="https://github.com/laurenslejeune/soacl_soap">Github</a></li>
    </ul>
    <br>
    <h4>MySQL Docker container</h4>
    <ul><li><a href="https://github.com/laurenslejeune/soacl_mysql_container">Configuration</a></li></ul>
</div>
@stop