<?php
	require_once("fonctions.php");
	echo modifierContrat($_POST["contrat_id"], $_POST["libelle"], $_POST["miniature_id"]);
?>