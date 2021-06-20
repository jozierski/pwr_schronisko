<?php

session_start();
if((!isset($_POST['imie']))) { //przekierowanie usera, jeśli wejdzie w niepoprawny link
    if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany'] == true)) {
        header('Location: ./home.php');
        exit();
    }
}

if(isset($_POST['imie'])) {
    //udana walidacja
    $sprawdzanie = true;

    //sprawdzanie imienia
    $imie = $_POST['imie'];
    if (!preg_match('#^[a-ząćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $imie)) {
        $sprawdzanie = false;
        $imie = null;
        $_SESSION['e_imie'] = "Podaj poprawne imie";
    }
    //sprawdzanie klatki
    $nrKlatki = $_POST['nr_klatki'];
    if (!preg_match('#^[a-z0-9]+$#i', $nrKlatki)) {
        $sprawdzanie = false;
        $nrKlatki = null;
        $_SESSION['e_klatka'] = "Podaj poprawny nr klatki";
    }
    //sprawdzanie opiekuna
    $opiekun = $_POST['opiekun'];
    if (!preg_match('#^[a-z0-9]+$#i', $opiekun)) {
        $sprawdzanie = false;
        $opiekun = null;
        $_SESSION['e_opiekun'] = "Podaj poprawny nr opiekuna";
    }
    //sprawdzanie nr chipa
    if ($_POST['nr_chipa'] < 1) {
        $chip = null;
    } else {
        $chip = $_POST['nr_chipa'];
        if (!preg_match('#^[a-z0-9]+$#i', $chip)) {
            $sprawdzanie = false;
            $chip = null;
            $_SESSION['e_chip'] = "Podaj poprawny nr chipa";
        }
    }
    //sprawdzanie rodzaju
    $rodzaj = $_POST['rodzaj'];
    if (!(($rodzaj == 'pies') || ($rodzaj == 'kot'))) {
        $sprawdzanie = false;
        $rodzaj = null;
        $_SESSION['e_rodzaj'] = "Podaj poprawny rodzaj";

    }
    //sprawdzanie plci
    $plec = $_POST['plec'];
    if (!(($plec == 'samiec') || ($plec == 'samica'))) {
        $sprawdzanie = false;
        $plec = null;
        $_SESSION['e_plec'] = "Podaj poprawną plec";

    }
    //sprawdzanie wykastrowania
    $wykastrowany = $_POST['wykastrowany'];
    if (!(($wykastrowany == 'tak') || ($wykastrowany == 'nie'))) {
        $sprawdzanie = false;
        $wykastrowany = null;
        $_SESSION['e_wykastrowany'] = "Podaj poprawną plec";

    }
    //sprawdzanie wieku
    $wiek = $_POST['wiek'];
    if (!preg_match('#^[a-z0-9]+$#i', $wiek)) {
        $sprawdzanie = false;
        $wiek = null;
        $_SESSION['e_wiek'] = "Podaj poprawny szacowany wiek";
    } elseif (($wiek <= 0) || ($wiek > 20)) {
        $sprawdzanie = false;
        $wiek = null;
        $_SESSION['e_wiek'] = "Podaj poprawny szacowany wiek";
    }

    require_once "./dataBase.php";

    mysqli_report(MYSQLI_REPORT_STRICT);
    try { // używanie bloczków try catch do obsługiwania wyjątków

        $polaczenie = new mysqli($servername, $username, $password, $database);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());

        } else {
            $polaczenie = new mysqli($servername, $username, $password, $database);
            if ($polaczenie->connect_errno != 0) {
                throw new Exception(mysqli_connect_errno());
            } else {
                $rezultat = $polaczenie->query("SELECT * FROM `users` WHERE `user_id` = " . $opiekun);

                if (!$rezultat) throw new Exception($polaczenie->error);
                $czyIstniejeOpiekun = $rezultat->num_rows;
                if ($czyIstniejeOpiekun == 0) {
                    $sprawdzanie = false;
                    $_SESSION['e_opiekun'] = "Nie istnieje opiekun o takim id";
                }

                if (!$rezultat) throw new Exception($polaczenie->error);

                if ($sprawdzanie == true) {
                    //sprawdzano, dodawania konta do bazy

                    if ($polaczenie->query("INSERT INTO `animals` (`animal_id`, `imie`, `nr_klatki`, `opiekun`, `rodzaj`, `plec`, `wykastrowany`, `nr_chipa`, `szacowany_wiek`, `data_dodania`, `stan`) VALUES (NULL, '$imie', '$nrKlatki', '$opiekun', '$rodzaj', '$plec', '$wykastrowany', '$chip', '$wiek', CURRENT_DATE(), 'Oczekuje opisu.')")) {

                        //wiadomość i przekierowanie
                        header('Location: ./manage_animals.php');

                    } else {
                        throw new Exception($polaczenie->error);
                    }
                } else {
                    header("Location: ./manage_animals.php");
                }

                $polaczenie->close();
            }
        }
    }

    catch(Exception $e){
        $_SESSION['error_msg']='<span style="color:red"> BŁĄD: </span>';
        $_SESSION['error_detail']=' '.$e; //odkomentuj tutaj jeśli cos nie działa
        header('Location: ./manage_animals.php');
    }
}