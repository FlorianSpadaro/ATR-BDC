<?php
	require_once("fonctions.php");
	
	echo getNbMessagesNonLuByUtilisateurId($_POST["utilisateur_id"]);
?>