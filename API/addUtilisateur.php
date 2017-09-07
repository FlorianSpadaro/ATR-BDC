<?php
	require_once("fonctions.php");
	echo addUtilisateur($_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["fonction_id"]);
?>