<?php
session_start();
if (isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']) == true) {
  header('Location: index.php');
  exit();
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Login | Wesoły Zwierzak</title>

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
              <a class="nav-link" href="home.php">Strona Główna</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="view.php">Przęglądaj zwierzęta</a>
            </li>

            <?php
            if (isset($_SESSION['uprawnienia']) && $_SESSION['uprawnienia'] == 'admin') {
              echo '<li class="nav-item">';
              echo '<a class="nav-link" href="add.php">Utwórz konto</a>';
              echo '</li>';

              echo '<li class="nav-item">';
              echo '<a class="nav-link" href="manage_accts.php">Zarządzaj kontami</a>';
              echo '</li>';
            }
            ?>

            <li class="nav-item">
              <?php
              if (isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']) == true) {
                echo '<li class="nav-item">';
                echo "<a class='nav-link' href='./manage_animals.php'>Zarządzaj zwierzętami</a>";
                echo '</li>';

                echo '<li class="nav-item">';
                echo "<a class='nav-link' href='./scryptLogoff.php'>Wyloguj</a>";
                echo '</li>';
              } else {
                echo '<li class="nav-item">';
                echo "<a class='nav-link' href='./scryptLogin.php'>Zaloguj</a>";
                echo '</li>';
              }

              ?>
            </li>

          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main role="main">
    <div class="container">
      <div class="container marketing">
        <br>
        <div class="row featurette" style="text-align: center;">
          <h2 class="featurette-heading"><strong>Logowanie Pracownicze</strong></h2>
        </div>

        <hr class="featurette-divider">

        <!-- Login Form -->
        <form style="text-align: center;" action="./scryptLogin.php" method="post">
          <input type="text" id="login" name="login" placeholder="login" value="
<?php
if (isset($_SESSION['fl_login'])) {
  echo $_SESSION['fl_login'];
  //		unset($_SESSION['fl_login']);
}
?>"> <br><br>
          <input type="password" id="password" name="password" placeholder="hasło"><br><br>
          <input type="submit" class="btn btn-md btn-primary" value="Zaloguj">
        </form>

        <hr class="featurette-divider">

      </div>
    </div>
  </main>

  <!-- Bootstrap core JavaScript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script>
    window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')
  </script>
  <script src="../../assets/js/vendor/popper.min.js"></script>
  <script src="../../dist/js/bootstrap.min.js"></script>

</body>

</html>