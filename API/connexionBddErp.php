<?php
	try{
		$bdd = new PDO('pgsql:host=bi.ambition.local;dbname=ambigroup_dev','admambigroup','13jkgaUM8Um');
	}
	catch (Exception $e){
		die('Erreur : '.$e->getMessage());
	}
?>
