<?php
	require_once("fonctions.php");
	echo removeAbonnementById($_POST["abonnement_id"], $_POST["utilisateur_id"]);
	//echo removeAbonnementById("secteur-1", 1);
?>