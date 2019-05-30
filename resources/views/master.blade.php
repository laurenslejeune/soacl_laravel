<!doctype html>
<html>
    <head>
        <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        <title>@yield('titel')</title>
        
        
    </head>
    <body>
        <h1>@yield('header')</h1>
        <h2>@yield('welkom')</h2>
        
        <div class="container-fluid" id="container" style="width: 80%">
            @yield('inhoud')
        </div>
        
        
        <!--
        <style type="text/css">
            #footer {
                    height : 100px;
                    /*width: 400px;*/
                    background-color: lightblue;
                    border : solid 2px lightcyan;
                }
    
            body {
              background-color: powderblue;
            }
        </style>
        -->
        <!-- Footer -->
        <footer class="page-footer font-small blue pt-4">

          <!-- Footer Links -->
          <div class="container-fluid text-center text-md-left">

            <!-- Grid row -->
            <div class="row">

              <!-- Grid column -->
              <div class="col-md-6 mt-md-0 mt-3">

                <!-- Content -->
                <h5 class="text-uppercase">Media manager</h5>
                <p>This is a project created by Laurens Le Jeune within the context of the course "Service Oriented Architectures and Cloud computing".</p>

              </div>
              <!-- Grid column -->

              <hr class="clearfix w-100 d-md-none pb-3">

              <!-- Grid column -->
              <div class="col-md-3 mb-md-0 mb-3">

                <!-- Links -->
                <h5 class="text-uppercase">Useful</h5>

                <ul class="list-unstyled">
                  <li>
                    <a href="http://localhost/mediamanager/public/music">Home</a>
                  </li>
                  <li>
                    <a href="http://localhost/mediamanager/public/about">About</a>
                  </li>
                  <li>
                    <a href="http://127.0.0.1:8000/api/documentation">API</a>
                  </li>
                  <li>
                    <a href="http://localhost/mediamanager/public/music/search">Searcher</a>
                  </li>
                </ul>

              </div>
              <!-- Grid column -->

              <!-- Grid column -->
              <div class="col-md-3 mb-md-0 mb-3">

                <!-- Links -->
                <h5 class="text-uppercase">Database</h5>

                <ul class="list-unstyled">
                  <li>
                    <a href="http://localhost/mediamanager/public/music/songs">Songs</a>
                  </li>
                  <li>
                    <a href="http://localhost/mediamanager/public/music/albums">Albums</a>
                  </li>
                  <li>
                    <a href="http://localhost/mediamanager/public/music/artists">Artists</a>
                  </li>
                  <li>
                    <a href="http://localhost/mediamanager/public/music/playlists">Playlists</a>
                  </li>
                </ul>

              </div>
              <!-- Grid column -->

            </div>
            <!-- Grid row -->

          </div>
          <!-- Footer Links -->

         

        </footer>
<!-- Footer -->
       
        <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        
    </body>
</html>
