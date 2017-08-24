<?php
	require_once("fonctions.php");
	
	echo getMessagesByUtilisateurId($_POST["utilisateur_id"]);
?>