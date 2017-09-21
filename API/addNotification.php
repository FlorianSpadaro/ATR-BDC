<?php
	require_once("fonctions.php");
	echo addNotification($_POST["titre"], $_POST["description"], $_POST["lien"]);
?>