<?php
	require_once("fonctions.php");
	
	if(!isset($_POST["description"]) || $_POST["description"] == "")
	{
		$_POST["description"] = null;
	}
	echo modifierDomaineById($_POST["domaine_id"], $_POST["libelle"], $_POST["secteur_id"], $_POST["description"]);
?>