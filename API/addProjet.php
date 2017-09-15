<?php
	require_once("fonctions.php");
	
	if(!isset($_POST["description"]) ||$_POST["description"] == "")
	{
		$_POST["description"] = null;
	}
	
	echo addProjet($_POST["titre"], $_POST["description"], $_POST["contenu"], $_POST["utilisateur_id"]);
?>