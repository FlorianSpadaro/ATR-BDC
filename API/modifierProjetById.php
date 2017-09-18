<?php
	require_once("fonctions.php");
	if(!isset($_POST["description"]) || $_POST["description"] == "")
	{
		$_POST["description"] = null;
	}
	echo modifierProjetById($_POST["projet_id"], $_POST["titre"], $_POST["description"], $_POST["contenu"]);
?>