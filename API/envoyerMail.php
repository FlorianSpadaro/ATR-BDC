<?php
	require_once("fonctions.php");
	echo envoyerMail($_POST["emails"], $_POST["titre"], $_POST["contenu"]);
	
?>