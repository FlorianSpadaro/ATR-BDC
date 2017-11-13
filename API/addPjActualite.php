<?php
	require_once("fonctions.php");
	
	if (isset($_FILES['file']) AND $_FILES['file']['error'] == 0)
	{
			if ($_FILES['file']['size'] <= 10000000)
			{
				$name = $_FILES['file']['name'];
				$infosfichier = pathinfo($_FILES['file']['name']);
				$extension_upload = $infosfichier['extension'];
				$extension_upload = strtolower(  substr(  strrchr($_FILES['file']['name'], '.')  ,1)  );
				$destination = '../pj/actualites/'.md5(uniqid(rand(), true)).".".$extension_upload;
				move_uploaded_file($_FILES['file']['tmp_name'], $destination);
				
				echo addPjActualite($name, substr($destination, 3), $_POST["actualite_id"]);
			}
	}
?>