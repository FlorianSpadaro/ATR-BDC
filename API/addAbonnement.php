<?php
	require_once("fonctions.php");
	echo addAbonnement($_POST["utilisateur_id"], $_POST["secteur_id"], $_POST["domaine_id"], $_POST["sous_domaine_id"], $_POST["projet_id"], $_POST["contrat_id"]);
?>