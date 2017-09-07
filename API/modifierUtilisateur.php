<?php
	require_once("fonctions.php");
	echo modifierUtilisateur($_POST["utilisateur_id"], $_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["fonction_id"]);
?>