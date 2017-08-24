<?php
	require_once("fonctions.php");
	
	echo addMessage($_POST["utilisateur_id"], $_POST["sujet"], $_POST["message"], $_POST["correspondant_id"]);
?>