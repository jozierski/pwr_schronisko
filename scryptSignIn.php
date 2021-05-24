<?php
	session_start();
	require_once "./dataBase.php";
	if((!isset($_POST['login']))){ //przekierowanie usera, jeśli wejdzie w niepoprawny link
		if(!((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany']==true))){
			header('Location: ./home.php');
			exit();
		}
	}

	if(isset($_POST['login'])){
		//udana walidacja
		$sprawdzanie=true;
		//sprawdzanie nicku
		$nick=$_POST['login'];
		$_SESSION['fr_login']=$nick;
		
		//sprawdzanie nick - długość
		if((strlen($nick)<3)||(strlen($nick)>20)){
			$sprawdzanie=false;
			$_SESSION['e_login']="Login musi posiadać od 3 do 20 znaków";
		}
		if (ctype_alnum($nick)==false){
			$sprawdzanie=false;
			$_SESSION['e_login']="Login nie może posiadać polskich i specjalnych znaków ";
		}
		
		//sprawdzanie hasła
		$haslo1=$_POST['password'];
		if ((strlen($haslo1)<8) || (strlen($haslo1)>30)){
			$sprawdzanie=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 30 znaków";
		}
		// haszowanie hasła
		$haslo_hash = password_hash($haslo1,PASSWORD_DEFAULT);

		//sprawdzanie imienia
		$imie=$_POST['imie'];
		$_SESSION['fr_imie']=$imie;
		if (!preg_match('#^[a-ząćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $imie)){
			$sprawdzanie=false;
			$imie=null;
			$_SESSION['e_imie']="Podaj poprawne imie";
		}

		//sprawdzanie nazwiska
		$nazwisko=$_POST['nazwisko'];
		$_SESSION['fr_nazwisko']=$nazwisko;
		if (!preg_match('#^[a-ząćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $nazwisko)){
			$sprawdzanie=false;
			$nazwisko=null;
			$_SESSION['e_nazwisko']="Podaj poprawne nazwisko";
		}

		//sprawdzanie uprawnień
		$uprawnienia=$_POST['uprawnienia'];
		$_SESSION['fr_uprawnienia']=$uprawnienia;
		if (!(($uprawnienia=='admin')||($uprawnienia=='opiekun'))){
			$sprawdzanie=false;
			$uprawnienia=null;
			$_SESSION['e_uprawnienia']="Podaj poprawne uprawnienia";

		}


		mysqli_report(MYSQLI_REPORT_STRICT);
		try{ // używanie bloczków try catch do obsługiwania wyjątków
			$polaczenie=new mysqli($servername, $username, $password, $database);
			if ($polaczenie->connect_errno!=0){
				throw new Exception(mysqli_connect_errno());
			}
			else{
				//czy login juz istnieje
				$rezultat=$polaczenie->query("SELECT `users`.`user_id` FROM `users` WHERE `users`.`login`='$nick'");
				if (!$rezultat)	throw new Exception($polaczenie->error);
				$ile_nickow=$rezultat->num_rows;
				if ($ile_nickow>0){
					$sprawdzanie=false;
					$_SESSION['e_login']="Instnieje już konto o takiej podanej nazwie";
				}

				if (!$rezultat)	throw new Exception($polaczenie->error);

				if ($sprawdzanie==true){
					//sprawdzano, dodawania konta do bazy

					 if($polaczenie->query("INSERT INTO `users` (`user_id`, `imie`, `nazwisko`, `login`, `password`, `uprawnienia`, `data_dolaczenia`) VALUES (NULL,'$imie','$nazwisko','$nick','$haslo_hash','$uprawnienia',CURRENT_DATE());")){

						//unsetowane niepotrzebnych zmiennych sesyjnych
						if(isset($_SESSION['fr_login'])) unset($_SESSION['fr_login']);
						if(isset($_SESSION['fr_imie'])) unset($_SESSION['fr_imie']);
						if(isset($_SESSION['fr_nazwisko'])) unset($_SESSION['fr_nazwisko']);
						if(isset($_SESSION['fr_uprawnienia'])) unset($_SESSION['fr_uprawnienia']);
						//wiadomość i przekierowanie
						$_SESSION['udana_rejestracja']="Utworzono konto firmowe, można się zalogować";
						header('Location: ./home.php');
						
					}else{
						throw new Exception($polaczenie->error);
					}
				}else {
					header("Location: ./add.php");
				}
		
				$polaczenie->close();
			}
		}
		catch(Exception $e){
			$_SESSION['error_msg']='<span style="color:red"> Błąd serwera </span>';
			//echo '<br/>dev info: '.$e; //odkomentuj tutaj jeśli cos nie działa
			header('Location: ./add.php');

		}
	
	}