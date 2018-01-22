<?php
	require_once("fonctions.php");
	//echo getSousDomainesByDomaineId(1);
	echo getSousDomainesByDomaineId($_POST["domaine_id"]);
?>