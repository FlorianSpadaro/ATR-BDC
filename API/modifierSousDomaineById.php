<?php
	require_once("fonctions.php");
	
	if(!isset($_POST["description"]) || $_POST["description"] == "")
	{
		$_POST["description"] = null;
	}
	
	echo modifierSousDomaineById($_POST["sous_domaine_id"], $_POST["libelle"], $_POST["description"], $_POST["domaine_id"]);
?>