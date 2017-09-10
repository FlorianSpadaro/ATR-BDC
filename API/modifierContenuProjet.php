<?php
	require_once("fonctions.php");
	
	if (isset($_FILES['contenu']) AND $_FILES['contenu']['error'] == 0)
	{
			if ($_FILES['contenu']['size'] <= 1000000)
			{
					$infosfichier = pathinfo($_FILES['contenu']['name']);
					$extension_upload = $infosfichier['extension'];
					$extensions_autorisees = array('html', 'htm');
					if (in_array($extension_upload, $extensions_autorisees))
					{
							$extension_upload = strtolower(  substr(  strrchr($_FILES['contenu']['name'], '.')  ,1)  );
							$destination = '../projets/'.md5(uniqid(rand(), true)).".".$extension_upload;
							move_uploaded_file($_FILES['contenu']['tmp_name'], $destination);
							
							echo modifierContenuProjet($_POST['projet_id'], substr($destination, 3));
					}
			}
	}
?>