<?php
	session_start();
	if((!isset($_POST['Nazw_firm']))){ //przekierowanie usera, jeśli wejdzie w niepoprawny link
		if((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany']==true)){
			header('Location: ../index.php');
			exit();
		}
	}
	if(isset($_POST['Nazw_firm'])){
		//udana walidacja
		$sprawdzanie=true;
		
		
		
		//sanityzacja emila
		$email=$_POST['Email'];
		$emailB=filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false)||($emailB!=$email)){
			$sprawdzanie=false;
			$_SESSION['e_email']="Podaj poprawny email";

		}
		
		//sprawdzanie nazwy firmy
		$comp_name=$_POST['Nazw_firm'];
		if (!preg_match('#^[a-z0-9ąćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $comp_name)){
			$sprawdzanie=false;
			$comp_name=null;
			$_SESSION['e_comp_name']="Podaj poprawną nazwę firmy";
		}
		//sprawdzanie nipu
		$nip=$_POST['NIP'];
		if (!preg_match('#^[a-z0-9]+$#i', $nip)){
			$sprawdzanie=false;
			$nip=null;
			$_SESSION['e_nip']="Podaj poprawny nip";
		}
		if (!preg_match('/..\d\d\d\d\d\d\d\d\d\d|\d\d\d\d\d\d\d\d\d\d/i', $nip)){
			$sprawdzanie=false;
			$nip=null;
			$_SESSION['e_nip']="Podaj poprawny nip2";
		}
		//sanityzacja numeru telefonu
		$phone=$_POST['Phone'];
		$phoneB=filter_var($phone, FILTER_SANITIZE_NUMBER_FLOAT);
		
		
		if ((filter_var($phone, FILTER_SANITIZE_NUMBER_FLOAT)==false)||($phoneB!=$phone)){
			$sprawdzanie=false;
			$_SESSION['e_phone']="Podaj poprawny nr telefonu";
		}
		
		//sprawdzanie nazwu kraju
		$country=$_POST['Kraj'];
		if (!preg_match('#^[a-z0-9ąćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $country)){
			$sprawdzanie=false;
			$country=null;
			$_SESSION['e_country']="Podaj poprawną nazwę kraju";
		}
		//sprawdzanie miasta
		$city=$_POST['Miasto'];
		if (!preg_match('#^[a-z0-9ąćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $city)){
			$sprawdzanie=false;
			$city=null;
			$_SESSION['e_city']="Podaj poprawną nazwę miasta";
		}
		//sprawdzanie ulicy
		$street=$_POST['Ulica'];
		if (!preg_match('#^[a-z0-9ąćęłńóśżźĄĆĘŁŃÓŚŻŹ\s]+$#i', $street)){
			$sprawdzanie=false;
			$street=null;
			$_SESSION['e_street']="Podaj poprawną nazwę ulicy";
		}
		$id_klienta=$_SESSION['id_klienta'];
		
		require_once "../DBcredentials.php"; // pobieranie zmiennych do połączzenia się z bazą danych

		
		mysqli_report(MYSQLI_REPORT_STRICT);
		try{ // używanie bloczków try catch do obsługiwania wyjątków
			$polaczenie=new mysqli($servername, $username, $password, $database);
			
			if ($polaczenie->connect_errno!=0){
				throw new Exception(mysqli_connect_errno());
			}
			else{
				//czy numer NIP juz istnieje
				$rezultat=$polaczenie->query("SELECT `klienci`.`ID_klienta` FROM `klienci` WHERE `klienci`.`NIP`='$nip'");
				if (!$rezultat)	throw new Exception($polaczenie->error);
				$ile_nipow=$rezultat->num_rows; 
				$jaki_nip=$rezultat->fetch_assoc(); 
				if (($jaki_nip['ID_klienta']!=$id_klienta)&&($ile_nipow>0)){
					$sprawdzanie=false;
					$_SESSION['e_nip']="Podano nr NIP, który istnieje już w bazie";
				}
				if ($sprawdzanie==true){
					//sprawdzano, dodawania konta do bazy
					 if($polaczenie->query("UPDATE `klienci` SET `email` = '$emailB', `nazwa_firmy` = '$comp_name', `NIP` = '$nip', `kraj` = '$country', `miasto` = '$city', `ulica` = '$street', `tel` = '$phoneB' WHERE `klienci`.`ID_klienta` = '$id_klienta=';")){
						
						//ustawianie zmiennych sesyjnych na nowo
						$_SESSION['email']=$emailB;
						$_SESSION['nazwa_firmy']=$comp_name;
						$_SESSION['NIP']=$nip;
						$_SESSION['kraj']=$country;
						$_SESSION['miasto']=$city;
						$_SESSION['ulica']=$street;
						$_SESSION['tel']=$phoneB;

						//wiadomość i przekierowanie
						$_SESSION['udany_update_danych']="Poprawnie zaaktualizowano dane";
						header('Location: ../profil.php');
						
						
						
					}else{
						throw new Exception($polaczenie->error);
					}
				}else {
					header("Location: ../profil.php");
				}
		
				$polaczenie->close();
			}
		}
		catch(Exception $e){
			$_SESSION['error_msg']='<span style="color:red"> Błąd serwera </span>';
			// echo '<br/>dev info: '.$e; //odkomentuj tutaj jeśli cos nie działa
			header('Location: ../profil.php');

		}
	
	}
	
?>