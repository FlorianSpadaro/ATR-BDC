<?php
	require_once("fonctions.php");
	
	if(!isset($_POST["description"]) || $_POST["description"] == "")
	{
		$_POST["description"] = null;
	}
	
	echo addActualite($_POST["titre"], $_POST["contenu"], $_POST["utilisateur_id"], $_POST["description"]);
?>