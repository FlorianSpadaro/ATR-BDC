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
	$params->filtre->contrats = array(1, 2);
	$params->filtre->secteurs = array(1, 2);
	$params->filtre->domaines = array(1, 2, 3);
	$params->filtre->sousDomaines = array(1, 2, 3);
	echo getNbProjets(null);*/
?>