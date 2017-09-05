<?php
	require_once("fonctions.php");
	echo addContrat($_POST["libelle"], $_POST["miniature_id"], $_POST["utilisateur_id"]);
?>