<?php
	require_once("fonctions.php");
	
	if(isset($_FILES['file']))
	{
		$_FILES['pj'] = $_FILES['file'];
	}
	
	if (isset($_FILES['pj']) AND $_FILES['pj']['error'] == 0)
	{
			if ($_FILES['pj']['size'] <= 1000000)
			{
					$infosfichier = pathinfo($_FILES['pj']['name']);
					$extension_upload = $infosfichier['extension'];
					$extension_upload = strtolower(  substr(  strrchr($_FILES['pj']['name'], '.')  ,1)  );
					$destination = '../pj/projets/'.md5(uniqid(rand(), true)).".".$extension_upload;
					move_uploaded_file($_FILES['pj']['tmp_name'], $destination);
					
					echo addPjProjet($_POST["libelle"], $_POST['projet_id'], substr($destination, 3));
			}
	}
?>