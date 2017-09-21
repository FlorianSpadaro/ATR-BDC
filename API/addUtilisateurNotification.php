<?php
	require_once("fonctions.php");
	echo addUtilisateurNotification($_POST["utilisateur_id"], $_POST["notification_id"]);
?>