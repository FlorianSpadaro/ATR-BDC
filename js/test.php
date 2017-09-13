<?php
	if (isset($_FILES['file']) AND $_FILES['file']['error'] == 0)
	{
			if ($_FILES['file']['size'] <= 1000000)
			{
					$infosfichier = pathinfo($_FILES['file']['name']);
					$extension_upload = $infosfichier['extension'];
					$extension_upload = strtolower(  substr(  strrchr($_FILES['file']['name'], '.')  ,1)  );
					$destination = md5(uniqid(rand(), true)).".".$extension_upload;
					move_uploaded_file($_FILES['file']['tmp_name'], $destination);
			}
	}
?>