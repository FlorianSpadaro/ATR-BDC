<?php
	try{
		$bdd = new PDO('pgsql:host=192.168.56.242;dbname=bdc', 'postgres', 'postgres');
	}
	catch (Exception $e){
		die('Erreur : '.$e->getMessage());
	}
?>