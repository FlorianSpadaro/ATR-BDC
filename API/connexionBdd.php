<?php
	try{
		$bdd = new PDO('pgsql:host=127.0.0.1;dbname=bdc', 'postgres', 'postgres');
	}
	catch (Exception $e){
		die('Erreur : '.$e->getMessage());
	}
?>
