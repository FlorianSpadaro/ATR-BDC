<?php
	require_once("fonctions.php");
	echo getNotificationsByUtilisateurId($_POST["utilisateur_id"]);
?>