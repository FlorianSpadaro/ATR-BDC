<?php
	require_once("fonctions.php");
	echo getProjetsByNum($_POST["nb_projets"], $_POST["debut"]);
?>