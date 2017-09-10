<?php
	require_once("fonctions.php");
	
	if(!isset($_POST["description"]) ||$_POST["description"] = "")
	{
		$_POST["description"] = null;
	}
	
	if(!isset($_POST["contrat_id"]) ||$_POST["contrat_id"] = "")
	{
		$_POST["contrat_id"] = null;
	}
	
	echo addProjet($_POST["titre"], $_POST["description"], $_POST["sous_domaine_id"], $_POST["contrat_id"], $_POST["utilisateur_id"]);
?>