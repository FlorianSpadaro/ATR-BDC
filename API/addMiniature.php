<?php
	require_once("fonctions.php");
	
	if (isset($_FILES['miniature']) AND $_FILES['miniature']['error'] == 0)
	{
			if ($_FILES['miniature']['size'] <= 1000000)
			{
					$infosfichier = pathinfo($_FILES['miniature']['name']);
					$extension_upload = $infosfichier['extension'];
					$extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
					if (in_array($extension_upload, $extensions_autorisees))
					{
							$extension_upload = strtolower(  substr(  strrchr($_FILES['miniature']['name'], '.')  ,1)  );
							$destination = '../images/miniaturesContrats/'.md5(uniqid(rand(), true)).".".$extension_upload;
							move_uploaded_file($_FILES['miniature']['tmp_name'], $destination);
							
							echo addMiniature($_POST['nom'], substr($destination, 3));
					}
			}
	}
?>