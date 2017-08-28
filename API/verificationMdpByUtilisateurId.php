<?php
	require_once("fonctions.php");
	
	echo verificationMdpByUtilisateurId($_POST["utilisateur_id"], $_POST["mdp"]);
?>