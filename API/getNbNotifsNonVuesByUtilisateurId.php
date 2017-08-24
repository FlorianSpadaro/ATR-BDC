<?php
	require_once("fonctions.php");
	
	echo getNbNotifsNonVuesByUtilisateurId($_POST["utilisateur_id"]);
?>