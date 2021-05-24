<?php
//wylogowanie poprzez unsetowanie sesji
	session_start();
	session_unset();
	header('Location: ./home.php');
