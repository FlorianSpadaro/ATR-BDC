<?php
	require_once("fonctions.php");
	echo getNbProjets($_POST["params"]);
	
	/*$params = (object)[];
	$params->texte = (object)[];
	$params->texte->titre = true;
	$params->texte->description = true;
	$params->texte->contenu = true;
	$params->texte->texte = "";
	
	$params->filtre = (object)[];
	$params->filtre->contrats = array();
	$params->filtre->secteurs = array(1, 2);
	$params->filtre->domaines = array(1, 2, 3, 4);
	$params->filtre->sousDomaines = array(1, 2, 3, 5, 7, 10, 12, 14, 15, 19, 20, 21, 16, 24);
	echo getNbProjets($params);*/
?>