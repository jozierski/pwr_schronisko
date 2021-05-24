<?php

	session_start();
	if((!isset($_POST['Username']))||(!isset($_POST['Password']))){ //przekierowanie usera, jeśli wejdzie w niepoprawny link
		if((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany']==true)){
			header('Location: ./home.php');
			exit();
		}
	}
	require_once "./dataBase.php";

	mysqli_report(MYSQLI_REPORT_STRICT);
	try{ // używanie bloczków try catch do obsługiwania wyjątków
		$polaczenie = new mysqli($servername, $username, $password, $database);
		if ($polaczenie->connect_errno!=0){
			throw new Exception(mysqli_connect_errno());
		} else{
            $login=filter_var($_POST['login'],FILTER_SANITIZE_STRING);
            $haslo=$_POST['password'];
            $haslo_hash = password_hash($haslo,PASSWORD_DEFAULT);
//                var_dump($haslo_hash); //odkomenduj aby dostać hash hasła
//                die();
            $sql = "SELECT * FROM `users` WHERE `users`.`login`='$login'";

			if ($rezultat = $polaczenie->query($sql)){
				$ilu_usr=$rezultat->num_rows;
				if ($ilu_usr>0){ // sprawdzanie czy user podal odpowiedni nick
					
					$wiersz=$rezultat->fetch_assoc(); // tutaj pobierane są dane z bazy 
					
					if(password_verify($haslo,$wiersz['password'])){ //weryfikacja podanego hasła

						if(isset(($_SESSION['fl_login']))) unset($_SESSION['fl_login']);

						//ustawianie zmiennych sesyjnych
						$_SESSION['zalogowany']=true;
						$_SESSION['uprawnienia']=$wiersz['uprawnienia'];

					$rezultat->close();
					header('Location: ./home.php');
					} else { //złe haslo
						$_SESSION['error_msg']='Nieprawidłowy login lub hasło';
						$_SESSION['fl_login']=$_POST['login'];
						header('Location: ./login.php');
					}
				
				$polaczenie->close();
			}
			else { //zly login
				$_SESSION['error_msg']='Nieprawidłowy login lub hasło';
				$_SESSION['fl_login']=$_POST['login'];
				header('Location: ./login.php');
			}
		}
	}
}
catch(Exception $e){
	$_SESSION['error_msg']='<span style="color:red"> Błąd serwera </span>';
	// echo '<br/>dev info: '.$e; //odkomentuj tutaj jeśli cos nie działa
	header('Location: ./login.php');
}
