<?php
	include("connexionBdd.php");
	echo getUtilisateursAbonnesByProjetId($_POST["projet_id"]);
?>