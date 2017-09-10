<?php
	require_once("fonctions.php");
	
	if(!isset($_POST["description"]) || $_POST["description"] == "")
	{
		$_POST["description"] = null;
	}
	
	echo addDomaine($_POST["libelle"], $_POST["secteur_id"], $_POST["utilisateur_id"], $_POST["description"]);
?>