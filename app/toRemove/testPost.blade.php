@extends('master')

@section('titel','test') 
@section('header','Overview of test')
@section('welkom','General information')


<script type="text/javascript">
    
    function testPost()
    {
        console.log('click');
        let title = 'TestTitle';
        let artistname = 'TestArtist';
        let album = 'TestAlbum';
        fetch('http://127.0.0.1:8000/api/songs/', {
                method: 'POST',
                headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({song:title,artist:artistname,album:album, source:'lastfm'})
                });
    }
</script>

@section('inhoud')

<input type="submit" value="Button" name="testpost" onclick="testPost()"/>

@stop