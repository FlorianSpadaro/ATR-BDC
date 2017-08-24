<?php
	require_once("fonctions.php");
	
	echo connexion($_POST["login"], $_POST["mdp"]);
?>