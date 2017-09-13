<?php
	try{
		$bdd = new PDO('pgsql:host=192.168.30.218;dbname=bdc_flo', 'CYRRIC', 'cyril');
	}
	catch (Exception $e){
		die('Erreur : '.$e->getMessage());
	}
?>