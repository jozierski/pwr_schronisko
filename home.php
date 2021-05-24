<?php
    session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Strona Główna | Wesoły Zwierzak</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/carousel/">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">
  </head>
  <body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light">
            <div class="container">
                <img style="width: 150px; margin-right: 20px" src="src/logo.png">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Strona Główna</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                                <?php
                                if (isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany'])==true) {
                                    echo "<a class='nav-link' href='./scryptLogoff.php'>";
                                    echo 'wyloguj';
                                    echo "</a>";
                                } else{
                                    echo "<a class='nav-link' href='./scryptLogin.php'>";
                                    echo 'zaloguj';
                                    echo "</a>";
                                }
                                ?>
                        </li>
                     </ul>
                </div>
            </div>
        </nav>
    </header>

    <main role="main">

        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="first-slide" src="src/piesek_banner3.jpg">
                    <div class="container">
                        <div class="carousel-caption text-left">
                            <h1><span style="background-color:rgba(0, 0, 0, 0.230); padding-left:7px; padding-right:7px;">Każdy zwierzak zasługuje na <span style="color: rgb(50,205,50);">miłość.</span></span></h1>
                            <p><span style="background-color:rgba(0, 0, 0, 0.230); padding-left:7px; padding-right:7px;">Nowy dom to najlepsza rzecz którą możesz im podarować!</span></p>
                            <p><a class="btn btn-md btn-primary" href="#" role="button">Przeglądaj zwierzaki</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
      <div class="container marketing">

        <hr class="featurette-divider">

        <div class="row featurette">
          <div class="col-md-7">
            <h2 class="featurette-heading">Zadbane zwierzęta gotowe do <span class="text-muted">kochania.</span></h2>
            <p class="lead">Wszystkie zwierzaki które do nas trafiają są często badane oraz szczepione jeżeli jest taka konieczność. Kiedy przebywają u nas, są regularnie myte oraz myte oraz opiekowane przez naszych wysoko wyszkolonych miłośników zwierząt (...pracowników).</p>
          </div>
          <div class="col-md-5">
            <img src="src/psie_spa.jpg">
          </div>
        </div>

        <hr class="featurette-divider">

        <div class="row featurette">
          <div class="col-md-7 order-md-2">
            <h2 class="featurette-heading">Nieskończona <span class="text-muted">wdzięczność.</span></h2>
            <p class="lead">Zwierzęta które są adptowane i trafiają do nowych domów w których są kochane okazują niespotykaną wdzięczność oraz wierność wobec swoich nowych właścicieli. Przekonajcie się o tym sami!</p>
          </div>
          <div class="col-md-5 order-md-1">
            <img src="src/empatia.jpg">
          </div>
        </div>

        <hr class="featurette-divider">

        <div class="row featurette">
          <div class="col-md-7">
            <h2 class="featurette-heading">Znadziesz odpowiedniego zwierzaka dla <span class="text-muted">siebie.</span></h2>
            <p class="lead">Mamy bardzo rozszerzony indeks zwierząt do adopcji. Co więcej, zawsze dajemy możliwość wizyt na spotkanie oraz oswojania z zwierzakami abyś mógł poczuć tą wyjątkową więź.</p>
          </div>
          <div class="col-md-5">
            <img src="src/dla_cb.jpg">
          </div>
        </div>

        <hr class="featurette-divider">

      </div>

      <!-- FOOTER -->
      <footer class="container">
        <p class="float-right"><a href="#">Wróć na górę strony</a></p>
      </footer>
    </div>
    </main>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../assets/js/vendor/popper.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>

  </body>
</html>
