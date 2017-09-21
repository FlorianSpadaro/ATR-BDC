<?php
	require_once("fonctions.php");
	echo mailNotifNouveauProjet($_POST["projet_id"], $_POST["utilisateurs"]);
?>