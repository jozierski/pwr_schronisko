<?php
session_start();
require_once('dataBase.php');
if ($_SESSION['uprawnienia'] != 'admin') {
  header("Location: error.php");
  die();
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Admin | Wesoły Zwierzak</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/carousel/">

  <!-- Bootstrap core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link href="style.css" rel="stylesheet">
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light">
      <?php
      if (isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']) == true) {
        echo "<p class='text-right'>&nbsp&nbspZalogowano jako: ";
        echo $_SESSION['imie'], " ", $_SESSION['nazwisko'], "</p>";
      }
      ?>
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
            <li class="nav-item active">
              <a class="nav-link" href="search_animal.php">Wyszukaj zwierzęta</a>
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
        <div class="row featurette" style="text-align: center;">
          <h2 class="featurette-heading"><strong>Zarządzaj kontami</strong></h2>
        </div>
        <hr class="featurette-divider">
        <?php
        $sqlQuery = "SELECT * FROM users";
        $resultSet = mysqli_query($conn, $sqlQuery) or die("database error:" . mysqli_error($conn));
        ?>
        <table id="editableTable" class="table table-bordered">
          <thead>
            <tr>
              <th>User_id</th>
              <th>Imie</th>
              <th>Nazwisko</th>
              <th>Login</th>
              <th>Uprawnienie</th>
              <th>Data dołączenia</th>
              <th>Liczba zwierząt pod opieką</th>
              <th> </th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultSet)) { ?>
              <tr id="<?php echo $row['user_id']; ?>">

                <?php $sqlQuery2 = "SELECT COUNT(*) c FROM animals WHERE opiekun = " . $row['user_id'];
                $result2 = mysqli_query($conn, $sqlQuery2) or die("database error:" . mysqli_error($conn));
                $row2 = mysqli_fetch_array($result2) ?>

                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['imie']; ?></td>
                <td><?php echo $row['nazwisko']; ?></td>
                <td><?php echo $row['login']; ?></td>
                <td><?php echo $row['uprawnienia']; ?></td>
                <td><?php echo $row['data_dolaczenia']; ?></td>
                <td><?php echo $row2['c']; ?></td>
                <td><a href="#" class="link-success">EDYTUJ</a></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
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