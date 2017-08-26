<?php
	require_once("fonctions.php");

	echo addReponseMessage($_POST["message_id"], $_POST["utilisateur_id"], $_POST["reponse"]);
?>