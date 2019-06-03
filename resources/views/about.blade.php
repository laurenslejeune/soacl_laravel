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
    <br>
    <h4>Used services</h4>
    <h5>REST</h5>
    <ul>
        <li><a href="https://www.last.fm/api">LastFM</a></li>
        <li><a href="https://developers.google.com/youtube/v3/">Youtube</a></li>
        <li><a href="https://en.wikipedia.org/w/api.php">Wikipedia</a></li>
        <li><a href="https://translate.yandex.com">Yandex translation</a></li>
    </ul>
    <h5>SOAP</h5>
    <ul>
        <li><a href="http://api.chartlyrics.com/apiv1.asmx">Chartlyrics</a></li>
    </ul>
</div>
@stop