<?php
	require_once("fonctions.php");
	
	if(!isset($_POST["description"]) || $_POST["description"] == "")
	{
		$_POST["description"] = null;
	}
	
	echo addSousDomaine($_POST["domaine_id"], $_POST["libelle"], $_POST["description"], $_POST["utilisateur_id"]);
?>