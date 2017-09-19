<?php
	require_once("fonctions.php");
	
	if(!isset($_POST["params"]))
	{
		$_POST["params"] = null;
	}

if(!isset($_POST["search"]))
	{
		$_POST["search"] = null;
	}
	
	echo getProjetsByNum($_POST["nb_projets"], $_POST["debut"], $_POST["params"], $_POST["search"]);
	
	/*$params = (object)[];
	$params->texte = (object)[];
	$params->texte->titre = true;
	$params->texte->description = true;
	$params->texte->contenu = true;
	$params->texte->texte = "";
	
	$params->filtre = (object)[];
	$params->filtre->contrats = array(1, 2);
	$params->filtre->secteurs = array(1, 2);
	$params->filtre->domaines = array(1, 2, 3);
	$params->filtre->sousDomaines = array(1, 2, 3, 4);
	echo getProjetsByNum(10, 0, $params);*/
?>