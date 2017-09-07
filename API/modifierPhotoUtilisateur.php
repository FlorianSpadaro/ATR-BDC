<?php
	require_once("fonctions.php");
	
	if (isset($_FILES['photo']) AND $_FILES['photo']['error'] == 0)
	{
			if ($_FILES['photo']['size'] <= 1000000)
			{
					$infosfichier = pathinfo($_FILES['photo']['name']);
					$extension_upload = $infosfichier['extension'];
					$extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
					if (in_array($extension_upload, $extensions_autorisees))
					{
							$extension_upload = strtolower(  substr(  strrchr($_FILES['photo']['name'], '.')  ,1)  );
							$destination = '../images/photosUtilisateurs/'.md5(uniqid(rand(), true)).".".$extension_upload;
							move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
							
							echo modifierPhotoUtilisateur($_POST['utilisateur_id'], substr($destination, 3));
					}
			}
	}
?>