<?php
	require_once("fonctions.php");
	echo modifierActualiteById($_POST["actualite_id"], $_POST["titre"], $_POST["contenu"], $_POST["description"]);
?>