<?php
	require_once("fonctions.php");
	
	if (isset($_FILES['image']) AND $_FILES['image']['error'] == 0)
	{
			if ($_FILES['image']['size'] <= 1000000)
			{
					$infosfichier = pathinfo($_FILES['image']['name']);
					$extension_upload = $infosfichier['extension'];
					$extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
					if (in_array($extension_upload, $extensions_autorisees))
					{
							$extension_upload = strtolower(  substr(  strrchr($_FILES['image']['name'], '.')  ,1)  );
							$destination = '../images/entetesProjets/'.md5(uniqid(rand(), true)).".".$extension_upload;
							move_uploaded_file($_FILES['image']['tmp_name'], $destination);
							
							echo modifierImageEnteteProjet($_POST['projet_id'], substr($destination, 3));
					}
			}
	}
?>