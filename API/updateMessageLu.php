<?php
	require_once("fonctions.php");
	
	echo updateMessageLu($_POST["message_id"], $_POST["utilisateur_id"]);
?>