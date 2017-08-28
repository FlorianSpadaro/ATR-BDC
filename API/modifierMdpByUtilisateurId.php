<?php
	require_once("fonctions.php");
	echo modifierMdpByUtilisateurId($_POST["utilisateur_id"], $_POST["mdp"]);
?>