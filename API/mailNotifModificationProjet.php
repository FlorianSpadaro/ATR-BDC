<?php
	require_once("fonctions.php");
	echo mailNotifModificationProjet($_POST["projet_id"], $_POST["utilisateurs"]);
?>