<?php
	session_start();
	require_once "./dataBase.php";
	if((!isset($_POST['Email']))){ //przekierowanie usera, jeśli wejdzie w niepoprawny link
		if((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany']==true)){
			header('Location: ../index.php');
			exit();
		}
	}
	if(isset($_POST['Email'])){
		//udana walidacja
		$sprawdzanie=true;
		//sprawdzanie nicku
		$nick=$_POST['Username'];
		$_SESSION['fr_nick']=$nick;
		
		//sprawdzanie nick - długość
		if((strlen($nick)<3)||(strlen($nick)>20)){
			$sprawdzanie=false;
			$_SESSION['e_nick']="Nick musi posiadać od 3 do 20 znaków";
		}
		if (ctype_alnum($nick)==false){
			$sprawdzanie=false;
			$_SESSION['e_nick']="Nick nie może posiadać polskich i specjalnych znaków ";
		}
		
		//sprawdzanie hasła
		$haslo1=$_POST['Password'];
		if ((strlen($haslo1)<8) || (strlen($haslo1)>30)){
			$sprawdzanie=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 30 znaków";
		}
		// haszowanie hasła
		$haslo_hash = password_hash($haslo1,PASSWORD_DEFAULT);
		
		//sanityzacja emila
		$email=$_POST['Email'];
		$emailB=filter_var($email, FILTER_SANITIZE_EMAIL);
		$_SESSION['fr_email']=$email;
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false)||($emailB!=$email)){
			$sprawdzanie=false;
			$_SESSION['e_email']="Podaj poprawny email";
		}
		
		//sanityzacja numeru telefonu
		$phone=$_POST['Phone'];
		$phoneB=filter_var($phone, FILTER_SANITIZE_NUMBER_FLOAT);
		$_SESSION['fr_phone']=$phone;
		
		
		if ((filter_var($phone, FILTER_SANITIZE_NUMBER_FLOAT)==false)||($phoneB!=$phone)){
			$sprawdzanie=false;
			$_SESSION['e_phone']="Podaj poprawny nr telefonu";
		}
		//sprawdzanie nazwy firmy
		$comp_name=$_POST['Nazw_firm'];
		$_SESSION['fr_comp_name']=$comp_name;
		if (!preg_match('#^[a-z0-9ąćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $comp_name)){
			$sprawdzanie=false;
			$comp_name=null;
			$_SESSION['e_comp_name']="Podaj poprawną nazwę firmy";
		}
		//sprawdzanie nipu
		$nip=$_POST['NIP'];
		$_SESSION['fr_nip']=$nip;
		if (!preg_match('#^[a-z0-9]+$#i', $nip)){
			$sprawdzanie=false;
			$nip=null;
			$_SESSION['e_nip']="Podaj poprawny nip";
		}
		if (!preg_match('/..\d\d\d\d\d\d\d\d\d\d|\d\d\d\d\d\d\d\d\d\d/i', $nip)){
			$sprawdzanie=false;
			$nip=null;
			$_SESSION['e_nip']="Podaj poprawny nip";
		}
		
		//sprawdzanie nazwu kraju
		$country=$_POST['Kraj'];
		$_SESSION['fr_country']=$country;
		if (!preg_match('#^[a-z0-9ąćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $country)){
			$sprawdzanie=false;
			$country=null;
			$_SESSION['e_country']="Podaj poprawną nazwę kraju";
		}
		//sprawdzanie miasta
		$city=$_POST['Miasto'];
		$_SESSION['fr_city']=$city;
		if (!preg_match('#^[a-z0-9ąćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $city)){
			$sprawdzanie=false;
			$city=null;
			$_SESSION['e_city']="Podaj poprawną nazwę miasta";
		}
		//sprawdzanie ulicy
		$street=$_POST['Ulica'];
		$_SESSION['fr_street']=$street;
		if (!preg_match('#^[a-z0-9ąćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $street)){
			$sprawdzanie=false;
			$street=null;
			$_SESSION['e_street']="Podaj poprawną nazwę ulicy";
		}
			
		
		require_once "../DBcredentials.php"; // tutaj pobierane są dane z bazy 

		
		mysqli_report(MYSQLI_REPORT_STRICT);
		try{ // używanie bloczków try catch do obsługiwania wyjątków
			$polaczenie=new mysqli($servername, $username, $password, $database);
			
			if ($polaczenie->connect_errno!=0){
				throw new Exception(mysqli_connect_errno());
			}
			else{
				//czy email juz istnieje
				$rezultat=$polaczenie->query("SELECT `klienci`.`ID_klienta` FROM `klienci` WHERE `klienci`.`email`='$email'");
				if (!$rezultat)	throw new Exception($polaczenie->error);		
				$ile_maili=$rezultat->num_rows; 
				if ($ile_maili>0){
					$sprawdzanie=false;
					$_SESSION['e_email']="Instnieje już konto o takim podanym mailu";
				}
				//czy nick juz istnieje
				$rezultat=$polaczenie->query("SELECT `klienci`.`ID_klienta` FROM `klienci` WHERE `klienci`.`login`='$nick'");
				if (!$rezultat)	throw new Exception($polaczenie->error);
				$ile_nickow=$rezultat->num_rows; 
				if ($ile_nickow>0){
					$sprawdzanie=false;
					$_SESSION['e_nick']="Instnieje już konto o takiej podanej nazwie";
				}
				
				//czy numer NIP juz istnieje
				$rezultat=$polaczenie->query("SELECT `klienci`.`ID_klienta` FROM `klienci` WHERE `klienci`.`NIP`='$nip'");
				
				if (!$rezultat)	throw new Exception($polaczenie->error);
				
				$ile_nipow=$rezultat->num_rows; 
				if ($ile_nipow>0){
					$sprawdzanie=false;
					$_SESSION['e_nip']="Podano nr NIP, który istnieje już w bazie";
				}
				if ($sprawdzanie==true){
					//sprawdzano, dodawania konta do bazy
					 if($polaczenie->query("INSERT INTO `klienci` (`ID_klienta`, `nazwa_firmy`, `NIP`, `kraj`, `miasto`, `ulica`, `email`, `tel`, `login`, `haslo`) VALUES (NULL, '$comp_name', '$nip', '$country', '$city', '$street', '$emailB', '$phoneB', '$nick', '$haslo_hash');")){
						
						//unsetowane niepotrzebnych zmiennych sesyjnych
						if(isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
						if(isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
						if(isset($_SESSION['fr_phone'])) unset($_SESSION['fr_phone']);
						if(isset($_SESSION['fr_comp_name'])) unset($_SESSION['fr_comp_name']);
						if(isset($_SESSION['fr_nip'])) unset($_SESSION['fr_nip']);
						if(isset($_SESSION['fr_country'])) unset($_SESSION['fr_country']);
						if(isset($_SESSION['fr_city'])) unset($_SESSION['fr_city']);
						if(isset($_SESSION['fr_street'])) unset($_SESSION['fr_street']);
						//wiadomość i przekierowanie
						$_SESSION['udana_rejestracja']="Utworzono konto firmowe, można się zalogować";
						header('Location: ../login.php');
						
					}else{
						throw new Exception($polaczenie->error);
					}
				}else {
					header("Location: ../login.php");
				}
		
				$polaczenie->close();
			}
		}
		catch(Exception $e){
			$_SESSION['error_msg']='<span style="color:red"> Błąd serwera </span>';
			//echo '<br/>dev info: '.$e; //odkomentuj tutaj jeśli cos nie działa
			header('Location: ../login.php');

		}
	
	}