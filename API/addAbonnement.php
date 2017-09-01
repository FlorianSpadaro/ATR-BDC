<?php
	require_once("fonctions.php");
	
	if(!isset($_POST["secteur_id"]) || ($_POST["secteur_id"] == null) || ($_POST["secteur_id"] == "null") || ($_POST["secteur_id"] == ""))
	{
		$_POST["secteur_id"] = null;
	}
	
	if(!isset($_POST["domaine_id"]) || ($_POST["domaine_id"] == null) || ($_POST["domaine_id"] == "null") || ($_POST["domaine_id"] == ""))
	{
		$_POST["domaine_id"] = null;
	}
	
	if(!isset($_POST["sous_domaine_id"]) || ($_POST["sous_domaine_id"] == null) || ($_POST["sous_domaine_id"] == "null") || ($_POST["sous_domaine_id"] == ""))
	{
		$_POST["sous_domaine_id"] = null;
	}
	
	if(!isset($_POST["projet_id"]) || ($_POST["projet_id"] == null) || ($_POST["projet_id"] == "null") || ($_POST["projet_id"] == ""))
	{
		$_POST["projet_id"] = null;
	}
	
	if(!isset($_POST["contrat_id"]) || ($_POST["contrat_id"] == null) || ($_POST["contrat_id"] == "null") || ($_POST["contrat_id"] == ""))
	{
		$_POST["contrat_id"] = null;
	}
	
	
	
	echo addAbonnement($_POST["utilisateur_id"], $_POST["secteur_id"], $_POST["domaine_id"], $_POST["sous_domaine_id"], $_POST["projet_id"], $_POST["contrat_id"]);

?>