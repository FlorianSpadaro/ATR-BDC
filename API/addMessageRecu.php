<?php
	require_once("fonctions.php");
	
	echo addMessageRecu($_POST["message_id"], $_POST["utilisateur_id"], $_POST["correspondant_id"]);
?>