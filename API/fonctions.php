<?php
	function modifierNiveauFonction($idFonction, $idNiveau)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("UPDATE fonction SET niveau_id = ? WHERE id = ?");
			$reponse = $req->execute(array($idNiveau, $idFonction));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function getNiveauByFonctionId($id)
	{
		include("connexionBdd.php");
		
		$niveau = null;
		$req = $bdd->prepare("SELECT niveau_id FROM fonction WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$niveau = json_decode(getNiveauById($data["niveau_id"]));
		}
		return json_encode($niveau);
	}

	function getContratsIdByMiniatureId($id)
	{
		include("connexionBdd.php");
		
		$listeIdContrats = array();
		$req = $bdd->prepare("SELECT id FROM contrat WHERE miniature_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			array_push($listeIdContrats, $data["id"]);
		}
		return json_encode($listeIdContrats);
	}

	function removeMiniatureById($id)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("SELECT url FROM miniature WHERE id = ?");
			$req->execute(array($id));
			if($data = $req->fetch())
			{
				$reponse = unlink("../".$data["url"]);
				if($reponse)
				{
					$req = $bdd->prepare("DELETE FROM miniature WHERE id = ?");
					$reponse = $req->execute(array($id));
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function getNbProjetsGeneriquesBySousDomaineId($idSousDomaine)
	{
		include("connexionBdd.php");
		
		$projets = null;
		$i = 0;
		$req = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
		$req->execute(array($idSousDomaine));
		if($data = $req->fetch())
		{
			$projets = json_decode(getProjetsGeneriquesByDomaineId($data["domaine_id"]));
			if($projets != null)
			{
				foreach($projets as $projet)
				{
					$i++;
				}
			}
		}
		return json_encode($i);
	}

	function getProjetsGeneriquesBySecteurId($idSecteur)
	{
		include("connexionBdd.php");
		
		$projets = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
		$req->execute(array($idSecteur));
		while($data = $req->fetch())
		{
			$projetsDomaine = json_decode(getProjetsGeneriquesByDomaineId($data["id"]));
			if($projetsDomaine != null)
			{
				foreach($projetsDomaine as $proDom)
				{
					if($projets != null)
					{
						if(!array_search($proDom, $projets))
						{
							$projets[$i] = $proDom;
							$i++;
						}
					}
					else{
						$projets[$i] = $proDom;
						$i++;
					}
				}
			}
		}
		return json_encode($projets);
	}

	function getProjetsGeneriquesByDomaineId($idDomaine)
	{
		include("connexionBdd.php");
		
		$projets = null;
		$i = 0;
		$req = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
		$req->execute(array($idDomaine));
		while($data = $req->fetch())
		{
			$projets[$i] = json_decode(getProjetById($data["projet_id"]));
			$i++;
		}
		return json_encode($projets);
	}

	function getIdElementByAbonnementId($idAbonnement)
	{
		include("connexionBdd.php");
		
		$id = null;
		$req = $bdd->prepare("SELECT * FROM abonnement WHERE id = ?");
		$req->execute(array($idAbonnement));
		if($data = $req->fetch())
		{
			if($data["secteur_id"] != null)
			{
				$id = $data["secteur_id"];
			}
			elseif($data["domaine_id"] != null)
			{
				$id = $data["domaine_id"];
			}
			elseif($data["sous_domaine_id"] != null)
			{
				$id = $data["sous_domaine_id"];
			}
			elseif($data["projet_id"] != null)
			{
				$id = $data["projet_id"];
			}
			elseif($data["contrat_id"] != null)
			{
				$id = $data["contrat_id"];
			}
		}
		return json_encode($id);
	}
	
	
	function mailNotifModificationActualite($idActu)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$actu = json_decode(getActualiteById($idActu));
			
			$utilisateurs = json_decode(getUtilisateurs());
			$tabEmails = array();
			foreach($utilisateurs as $user)
			{
				array_push($tabEmails, $user->email);
			}
			$emails = implode(",", $tabEmails);
			
			$lien = "actualite.php?id=" + $idActu;
			$titreMail = "Une actualité a été modifiée";
			$contenuMail = "Bonjour,<br/><br/>L'actualité '".$actu->titre."' a été modifiée.<br/>Pour la consulter vous pouvez cliquer <a href='".$lien."'>ICI</a>";
			$reponse = json_decode(envoyerMail($emails, $titreMail, $contenuMail));
			if($reponse)
			{
				$titreNotif = $titreMail;
				$contenuNotif = $contenuMail;
				$lienNotif = $lien;
				$idNotif = json_decode(addNotification($titreNotif, $contenuNotif, $lienNotif));
				if($idNotif != null)
				{
					foreach($utilisateurs as $user)
					{
						if($reponse)
						{
							$reponse = json_decode(addUtilisateurNotification($user->id, $idNotif));
						}
					}
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}
	
	function mailNotifNouvelleActualite($idActu)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$utilisateurs = json_decode(getUtilisateurs());
			$tabEmails = array();
			foreach($utilisateurs as $user)
			{
				array_push($tabEmails, $user->email);
			}
			$emails = implode(",", $tabEmails);
			
			$lien = "actualite.php?id=" + $idActu;
			$titreMail = "Une nouvelle actualité a été créée";
			$contenuMail = "Bonjour,<br/><br/>Une nouvelle actualité vient d'être créée.<br/>Pour la consulter vous pouvez cliquer <a href='".$lien."'>ICI</a>";
			$reponse = json_decode(envoyerMail($emails, $titreMail, $contenuMail));
			if($reponse)
			{
				$titreNotif = $titreMail;
				$contenuNotif = $contenuMail;
				$lienNotif = $lien;
				$idNotif = json_decode(addNotification($titreNotif, $contenuNotif, $lienNotif));
				if($idNotif != null)
				{
					foreach($utilisateurs as $user)
					{
						if($reponse)
						{
							$reponse = json_decode(addUtilisateurNotification($user->id, $idNotif));
						}
					}
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function mailNotifModificationProjet($idProjet, $users)//$users = format json
	{
		include("connexionBdd.php");
		
		$reponse = false;
		
		try{
			$users = json_decode($users);
			$emails = array();
			foreach($users as $user)
			{
				array_push($emails, $user->email);
			}
			$emails = implode(", ", $emails);
			$titreProjet = "";
			
			$req = $bdd->prepare("SELECT titre, sous_domaine_id FROM projet WHERE id = ?");
			$req->execute(array($idProjet));
			if($data = $req->fetch())
			{
				$titreProjet = $data["titre"];
				if($data["sous_domaine_id"] != null)
				{
					$typeProjet = "spécifique";
					$sousDomaine = json_decode(getSousDomaineById($data["sous_domaine_id"]));
					$domSd = $sousDomaine->libelle;
				}
				else{
					$typeProjet = "générique";
					$domaines = array();
					$req = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
					$req->execute(array($idProjet));
					while($data = $req->fetch())
					{
						array_push($domaines, $data["domaine_id"]);
					}
					$domSd = implode(", ", $domaines);
				}
				$sect = json_decode(getSecteurById(json_decode(getSecteurIdByProjetId($idProjet))));
				$secteur = $sect->libelle;
				
				$lien = "projet.php?id=".$idProjet;
				$titre = "Un projet a été modifié";
				$contenu = "Bonjour<br/><br/>Le projet \"".$titreProjet."\" du secteur ".$secteur." a été modifié.<br/>Il s'agit d'un projet ".$typeProjet." (".$domSd .")<br/>Pour y accéder, cliquez <a href='".$lien."'>ICI</a>";
				
				$reponse = json_decode(envoyerMail($emails, $titre, $contenu));
				if($reponse)
				{
					$titreNotif = $titre;
					$descriptionNotif = $contenu;
					$lienNotif = $lien;
					$idNotif = json_decode(addNotification($titreNotif, $descriptionNotif, $lienNotif));
					if($idNotif != null)
					{
						foreach($users as $usr)
						{
							if($reponse)
							{
								$reponse = json_decode(addUtilisateurNotification($usr->id, $idNotif));
							}
						}
					}
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}
	
	function mailNotifNouveauProjet($idProjet, $users) //$users = format json
	{
		include("connexionBdd.php");
		
		$reponse = false;
		
		try{
			$users = json_decode($users);
			$emails = array();
			foreach($users as $user)
			{
				array_push($emails, $user->email);
			}
			$emails = implode(", ", $emails);
			
			$req = $bdd->prepare("SELECT sous_domaine_id FROM projet WHERE id = ?");
			$req->execute(array($idProjet));
			if($data = $req->fetch())
			{
				if($data["sous_domaine_id"] != null)
				{
					$typeProjet = "spécifique";
					$sousDomaine = json_decode(getSousDomaineById($data["sous_domaine_id"]));
					$domSd = $sousDomaine->libelle;
				}
				else{
					$typeProjet = "générique";
					$domaines = array();
					$req = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
					$req->execute(array($idProjet));
					while($data = $req->fetch())
					{
						array_push($domaines, $data["domaine_id"]);
					}
					$domSd = implode(", ", $domaines);
				}
				$sect = json_decode(getSecteurById(json_decode(getSecteurIdByProjetId($idProjet))));
				$secteur = $sect->libelle;
				
				$lien = "projet.php?id=".$idProjet;
				$titre = "Un nouveau projet a été créé";
				$contenu = "Bonjour<br/><br/>Un projet du secteur ".$secteur." a été créé.<br/>Il s'agit d'un projet ".$typeProjet." (".$domSd.")<br/>Pour y accéder, cliquez <a href='".$lien."'>ICI</a>";
				
				$reponse = json_decode(envoyerMail($emails, $titre, $contenu));
				if($reponse)
				{
					$titreNotif = $titre;
					$descriptionNotif = $contenu;
					$lienNotif = $lien;
					$idNotif = json_decode(addNotification($titreNotif, $descriptionNotif, $lienNotif));
					if($idNotif != null)
					{
						foreach($users as $usr)
						{
							if($reponse)
							{
								$reponse = json_decode(addUtilisateurNotification($usr->id, $idNotif));
							}
						}
					}
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function addNotification($titre, $description, $lien)
	{
		include("connexionBdd.php");
		$id = null;
		$req = $bdd->prepare("INSERT INTO notification(titre, description, lien, date) VALUES(?, ?, ?, NOW()) RETURNING id");
		$req->execute(array($titre, $description, $lien));
		if($data = $req->fetch())
		{
			$id = $data["id"];
		}
		return json_encode($id);
	}
	
	function addUtilisateurNotification($idUser, $idNotif)
	{
		include("connexionBdd.php");
		$reponse = false;
		try{
			$req = $bdd->prepare("INSERT INTO notification_utilisateur(utilisateur_id, notification_id) VALUES(?, ?)");
			$reponse = $req->execute(array($idUser, $idNotif));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function envoyerMail($emails, $titre, $contenu)
	{
		$headers = "";
		//$headers .= "From: " . strip_tags($_POST['req-email']) . "\r\n";
		//$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
		//$headers .= "CC: susan@example.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=utf-8\r\n";
		
		$reponse = mail($emails, $titre, $contenu, $headers);
		return json_encode($reponse);
	}

	function getUtilisateursAbonnesByProjetId($idProjet)
	{
		include("connexionBdd.php");
		$users = null;
		$i = 0;
		$req = $bdd->prepare("SELECT sous_domaine_id FROM projet WHERE id = ?");
		$req->execute(array($idProjet));
		if($data = $req->fetch())
		{
			if($data["sous_domaine_id"] != null)
			{
				$idSd = $data["sous_domaine_id"];
				$idDomaine = json_decode(getDomaineIdByProjetId($idProjet));
				$idSecteur = json_decode(getSecteurIdByProjetId($idProjet));
				$req2 = $bdd->prepare("SELECT DISTINCT utilisateur_id FROM (SELECT * FROM abonnement WHERE projet_id = ? OR sous_domaine_id = ? OR domaine_id = ? OR secteur_id = ?) select_user");
				$req2->execute(array($idProjet, $idSd, $idDomaine, $idSecteur));
				while($data2 = $req2->fetch())
				{
					$users[$i] = json_decode(getUtilisateurById($data2["utilisateur_id"]));
					$i++;
				}
			}
			else{
				$idsDomaines = [];
				$req2 = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
				$req2->execute(array($idProjet));
				while($data2 = $req2->fetch())
				{
					array_push($idsDomaines, $data2["domaine_id"]);
				}
				if(sizeof($idsDomaines) > 0)
				{
					$idSecteur = json_decode(getSecteurIdByDomaineId($idsDomaines[0]));
				}
				else{
					$idSecteur = null;
				}
				$reqDom = "";
				foreach($idsDomaines as $idDom)
				{
					$reqDom = $reqDom." OR domaine_id = ".$idDom;
				}
				$req3 = $bdd->prepare("SELECT DISTINCT utilisateur_id FROM (SELECT * FROM abonnement WHERE projet_id = ?".$reqDom." OR secteur_id = ?) select_user");
				$req3->execute(array($idProjet, $idSecteur));
				while($data3 = $req3->fetch())
				{
					$users[$i] = json_decode(getUtilisateurById($data3["utilisateur_id"]));
					$i++;
				}
			}
		}
		return json_encode($users);
	}

	function removePieceJointeProjetById($idProjet, $idPj)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("SELECT url FROM piece_jointe WHERE id = ?");
			$req->execute(array($idPj));
			if($data = $req->fetch())
			{
				$suppr = unlink("../".$data["url"]);
				if($suppr)
				{
					$req2 = $bdd->prepare("DELETE FROM projet_pj WHERE projet_id = ? AND piece_jointe_id = ?");
					$req2->execute(array($idProjet, $idPj));
					if($data2 = $req2->fetch())
					{
						$req3 = $bdd->prepare("DELETE FROM piece_jointe WHERE id = ?");
						$reponse = $req3->execute(array($idPj));
					}
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function modifierProjetById($idProjet, $titre, $description, $contenu)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			if($description != null)
			{
				$req = $bdd->prepare("UPDATE projet SET titre = ?, description = ?, contenu = ? WHERE id = ?");
				$reponse = $req->execute(array($titre, $description, $contenu, $idProjet));
			}
			else{
				$req = $bdd->prepare("UPDATE projet SET titre = ?, contenu = ? WHERE id = ?");
				$reponse = $req->execute(array($titre, $contenu, $idProjet));
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function removeDomaineById($idDomaine)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
			$req->execute(array($idDomaine));
			while($data = $req->fetch())
			{
				removeSousDomaineById($data["id"]);
			}
			$req = $bdd->prepare("DELETE FROM domaine WHERE id = ?");
			$reponse = $req->execute(array($idDomaine));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function removeSousDomaineById($idSousDomaine)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("DELETE FROM sous_domaine WHERE id = ?");
			$reponse = $req->execute(array($idSousDomaine));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function removePiecesJointesProjet($idProjet)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("SELECT piece_jointe_id FROM projet_pj WHERE projet_id = ?");
			$req->execute(array($idProjet));
			while($data = $req->fetch())
			{
				$req2 = $bdd->prepare("SELECT url FROM piece_jointe WHERE id = ?");
				$req2->execute(array($data["piece_jointe_id"]));
				if($data2 = $req2->fetch())
				{
					$reponse = unlink("../".$data2["url"]);
					if($reponse)
					{
						$req3 = $bdd->prepare("DELETE FROM projet_pj WHERE piece_jointe_id = ? AND projet_id  = ?");
						$req3->execute(array($data["piece_jointe_id"], $idProjet));
						$req3 = $bdd->prepare("DELETE FROM piece_jointe WHERE id = ?");
						$reponse = $req3->execute(array($data["piece_jointe_id"]));
					}
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function removeImageEnteteProjet($idProjet)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("SELECT image_entete FROM projet WHERE id = ?");
			$req->execute(array($idProjet));
			if($data = $req->fetch())
			{
				if($data["image_entete"] != "img/imageEnteteProjetDefault.jpg")
					{
						$reponse = unlink("../".$data["image_entete"]);
						if($reponse)
						{
							$req2 = $bdd->prepare("UPDATE projet SET image_entete = DEFAULT WHERE id = ?");
							$reponse = $req2->execute(array($idProjet));
						}
					}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function modifierSousDomaineProjet($idProjet, $idSousDomaine)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("DELETE FROM projet_domaine WHERE projet_id = ?");
			$reponse = $req->execute(array($idProjet));
			
			if($reponse)
			{
				$req = $bdd->prepare("UPDATE projet SET sous_domaine_id = ? WHERE id = ?");
				$reponse = $req->execute(array($idSousDomaine, $idProjet));
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}
	
	function removeDomainesProjet($idProjet)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		
		try{
			$req = $bdd->prepare("DELETE FROM projet_domaine WHERE projet_id = ?");
			$reponse = $req->execute(array($idProjet));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function addDomaineProjet($idProjet, $idDomaine)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		
		$req = $bdd->prepare("UPDATE projet SET sous_domaine_id = NULL WHERE id = ?");
		$reponse = $req->execute(array($idProjet));
		
		try{
			if($reponse)
			{	
				$req = $bdd->prepare("INSERT INTO projet_domaine(projet_id, domaine_id) VALUES(?, ?)");
				$reponse = $req->execute(array($idProjet, $idDomaine));
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function getDomainesBySecteurId($idSecteur)
	{
		include("connexionBdd.php");
		
		$domaines = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
		$req->execute(array($idSecteur));
		while($data = $req->fetch())
		{
			$domaines[$i] = json_decode(getDomaineById($data["id"]));
			$i++;
		}
		
		return json_encode($domaines);
	}

	function actualiserPiecesJointesActualite($idActu, $idsPj)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("DELETE FROM actualite_pj WHERE actualite_id = ?");
			$req->execute(array($idActu));
			for($i = 0; $i < sizeof($idsPj); $i++)
			{
				if($i == 0 || $reponse == true)
				{
					$req2 = $bdd->prepare("INSERT INTO actualite_pj(actualite_id, piece_jointe_id) VALUES(?, ?)");
					$reponse = $req2->execute(array($idActu, $idsPj[$i]));
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function removeImageEnteteActualite($idActu)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("SELECT image_entete FROM actualite WHERE id = ?");
			$req->execute(array($idActu));
			if($data = $req->fetch())
			{
				if($data["image_entete"] != "imageEnteteProjetDefault.jpg")
				{
					$reponse = unlink("../".$data["image_entete"]);
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function modifierActualiteById($idActu, $titre, $contenu, $description)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("UPDATE actualite SET titre = ?, contenu = ?, description = ?, date_derniere_maj = NOW() WHERE id = ?");
			$reponse = $req->execute(array($titre, $contenu, $description, $idActu));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function removePieceJointeActualite($idPj, $idActu)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("SELECT url FROM piece_jointe WHERE id = ?");
			$req->execute(array($idPj));
			if($data = $req->fetch())
			{
				$efface = unlink("../".$data["url"]);
				if($efface)
				{
					$req2 = $bdd->prepare("DELETE FROM actualite_pj WHERE piece_jointe_id = ? AND actualite_id = ?");
					$rep = $req2->execute(array($idPj, $idActu));
					if($rep)
					{
						$req3 = $bdd->prepare("DELETE FROM piece_jointe WHERE id = ?");
						$reponse = $req3->execute(array($idPj));
					}
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function removeActualiteById($idActu)
	{
		include("connexionBdd.php");
		$rep = true;
		$reponse = false;
		try{
			$req = $bdd->prepare("SELECT piece_jointe_id FROM actualite_pj WHERE actualite_id = ?");
			$req->execute(array($idActu));
			while($data = $req->fetch())
			{
				$req2 = $bdd->prepare("SELECT url FROM piece_jointe WHERE id = ?");
				$req2->execute(array($data["piece_jointe_id"]));
				if($data2 = $req2->fetch())
				{
					$effacement = unlink("../".$data2["url"]);
					if($effacement)
					{
						$req3 = $bdd->prepare("DELETE FROM actualite_pj WHERE actualite_id = ? AND piece_jointe_id = ?");
						$req3->execute(array($idActu, $data["piece_jointe_id"]));
						
						$req3 = $bdd->prepare("DELETE FROM piece_jointe WHERE id = ?");
						$rep = $req3->execute(array($data["piece_jointe_id"]));
					}
				}
			}
			
			if($rep)
			{
				$req4 = $bdd->prepare("SELECT image_entete FROM actualite WHERE id = ?");
				$req4->execute(array($idActu));
				if($data4 = $req4->fetch())
				{
					if($data4["image_entete"] != "img/imageEnteteProjetDefault.jpg")
					{
						unlink("../".$data4["image_entete"]);
					}
					$req5 = $bdd->prepare("DELETE FROM actualite WHERE id = ?");
					$reponse = $req5->execute(array($idActu));
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function modifierImageEnteteActualite($idActu, $url)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("UPDATE actualite SET image_entete = ? WHERE id = ?");
			$reponse = $req->execute(array($url, $idActu));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function addPjActualite($libelle, $url, $idActu)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("INSERT INTO piece_jointe(libelle, url) VALUES(?, ?) RETURNING id");
			$req->execute(array($libelle, $url));
			if($data = $req->fetch())
			{
				$req2 = $bdd->prepare("INSERT INTO actualite_pj(actualite_id, piece_jointe_id) VALUES(?, ?)");
				$reponse = $req2->execute(array($idActu, $data["id"]));
			}
			
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function addActualite($titre, $contenu, $idUser, $description)
	{
		include("connexionBdd.php");
		
		$id = null;
		if($description != null)
		{
			$req = $bdd->prepare("INSERT INTO actualite(titre, contenu, utilisateur_id, description, date_creation, date_derniere_maj) VALUES(?, ?, ?, ?, NOW(), NOW()) RETURNING id");
			$req->execute(array($titre, $contenu, $idUser, $description));
			
		}
		else{
			$req = $bdd->prepare("INSERT INTO actualite(titre, contenu, utilisateur_id, date_creation, date_derniere_maj) VALUES(?, ?, ?, NOW(), NOW()) RETURNING id");
			$req->execute(array($titre, $contenu, $idUser));
		}
		if($data = $req->fetch())
		{
			$id = $data["id"];
		}
		return json_encode($id);
	}

	function modifierSousDomaineById($idSousDomaine, $libelle, $description, $idDomaine, $idContrat)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("SELECT libelle FROM contrat WHERE id = ?");
			$req->execute(array($idContrat));
			if($data = $req->fetch())
			{
				$libelle = $data["libelle"]." - ".$libelle;
			}
			
			if($description != null)
			{
				$req = $bdd->prepare("UPDATE sous_domaine SET libelle = ?, description = ?, domaine_id = ?, contrat_id = ? WHERE id = ?");
				$reponse = $req->execute(array($libelle, $description, $idDomaine, $idContrat, $idSousDomaine));
			}
			else{
				$req = $bdd->prepare("UPDATE sous_domaine SET libelle = ?, description = NULL, domaine_id = ?, contrat_id = ? WHERE id = ?");
				$reponse = $req->execute(array($libelle, $idDomaine, $idContrat, $idSousDomaine));
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function getDomaines()
	{
		include("connexionBdd.php");
		
		$domaines = null;
		$i = 0;
		$req = $bdd->query("SELECT id FROM domaine");
		while($data = $req->fetch())
		{
			$domaines[$i] = json_decode(getDomaineById($data["id"]));
			$i++;
		}
		return json_encode($domaines);
	}

	function addSousDomaine($idDomaine, $libelle, $description, $idUser, $idContrat)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("SELECT libelle FROM contrat WHERE id = ?");
			$req->execute(array($idContrat));
			if($data = $req->fetch())
			{
				$libelle = $data["libelle"]." - ".$libelle;
			}
			
			if($description != null)
			{
				$req = $bdd->prepare("INSERT INTO sous_domaine(domaine_id, libelle, description, utilisateur_id, contrat_id) VALUES(?, ?, ?, ?, ?)");
				$reponse = $req->execute(array($idDomaine, $libelle, $description, $idUser, $idContrat));
			}
			else{
				$req = $bdd->prepare("INSERT INTO sous_domaine(domaine_id, libelle, description, utilisateur_id, contrat_id) VALUES(?, ?, NULL, ?, ?)");
				$reponse = $req->execute(array($idDomaine, $libelle, $idUser, $idContrat));
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function modifierDomaineById($idDomaine, $libelle, $secteur_id, $description)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			if($description != null)
			{
				$req = $bdd->prepare("UPDATE domaine SET libelle = ?, secteur_id = ?, description = ? WHERE id = ?");
				$reponse = $req->execute(array($libelle, $secteur_id, $description, $idDomaine));
			}
			else{
				$req = $bdd->prepare("UPDATE domaine SET libelle = ?, secteur_id = ?, description = NULL WHERE id = ?");
				$reponse = $req->execute(array($libelle, $secteur_id, $idDomaine));
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function getSecteurs()
	{
		include("connexionBdd.php");
		
		$secteurs = null;
		$i = 0;
		$req = $bdd->query("SELECT id FROM secteur");
		while($data = $req->fetch())
		{
			$secteurs[$i] = json_decode(getSecteurById($data["id"]));
			$i++;
		}
		return json_encode($secteurs);
	}

	function removeProjetById($id)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			removePiecesJointesProjet($id);
			removeImageEnteteProjet($id);
			$req = $bdd->prepare("DELETE FROM projet_domaine WHERE projet_id = ?");
			$req->execute(array($id));
			$req = $bdd->prepare("DELETE FROM projet WHERE id = ?");
			$reponse = $req->execute(array($id));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function modifierImageEnteteProjet($idProjet, $url)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("UPDATE projet SET image_entete = ? WHERE id = ?");
			$reponse = $req->execute(array($url, $idProjet));
		}catch(Exception $e){
			$reponse = false;
		}
		json_encode($reponse);
	}

	function modifierContenuProjet($idProjet, $url)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("UPDATE projet SET contenu = ? WHERE id = ?");
			$reponse = $req->execute(array($url, $idProjet));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function addPjProjet($libelle, $idProjet, $url)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("INSERT INTO piece_jointe(libelle, url) VALUES(?, ?) RETURNING id");
			$req->execute(array($libelle, $url));
			if($data = $req->fetch())
			{
				$req2 = $bdd->prepare("INSERT INTO projet_pj(projet_id, piece_jointe_id) VALUES(?, ?)");
				$reponse = $req2->execute(array($idProjet, $data["id"]));
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function addProjet($titre, $description, $contenu, $idUser)
	{
		include("connexionBdd.php");
		
		$id = null;
		if($description == null)
		{
			$req = $bdd->prepare("INSERT INTO projet(titre, date_creation, date_derniere_maj, contenu, utilisateur_id) VALUES(?, NOW(), NOW(), ?, ?) RETURNING id");
			$req->execute(array($titre, $contenu, $idUser));
		}
		else{
			$req = $bdd->prepare("INSERT INTO projet(titre, date_creation, date_derniere_maj, description, contenu, utilisateur_id) VALUES (?, NOW(), NOW(), ?, ?, ?) RETURNING id");
			$req->execute(array($titre, $description, $contenu, $idUser));
		}
		if($data = $req->fetch())
		{
			$id = $data["id"];
		}
		return json_encode($id);
	}

	function addDomaine($libelle, $idSecteur, $idUser, $description)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			if($description != null)
			{
				$req = $bdd->prepare("INSERT INTO domaine(libelle, secteur_id, utilisateur_id, description) VALUES(?, ?, ?, ?)");
				$reponse = $req->execute(array($libelle, $idSecteur, $idUser, $description));
			}
			else{
				$req = $bdd->prepare("INSERT INTO domaine(libelle, secteur_id, utilisateur_id) VALUES(?, ?, ?)");
				$reponse = $req->execute(array($libelle, $idSecteur, $idUser));
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function removeUtilisateurById($id)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("DELETE FROM connexion WHERE utilisateur_id = ?");
			$reponse = $req->execute(array($id));
			if($reponse)
			{
				$req2 = $bdd->prepare("DELETE FROM utilisateur WHERE id = ?");
				$reponse = $req2->execute(array($id));
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function motDePasseAleatoire($nbr) 
	{
		$str = "";
		$chaine = "abcdefghijklmnpqrstuvwxyABCDEFGHIJKLMNOPQRSUTVWXYZ0123456789";
		$nb_chars = strlen($chaine);

		for($i=0; $i<$nbr; $i++)
		{
			$str .= $chaine[ rand(0, ($nb_chars-1)) ];
		}

		return json_encode($str);
	}

	function addUtilisateur($nom, $prenom, $email, $idFonction) //login = prenom.nom (tout en minuscules) et mdp = (chaine de 10 caractères aléatoires) -> envoyé par mail à l'utilisateur
	{
		include("connexionBdd.php");
		
		$id = null;
		$req = $bdd->prepare("INSERT INTO utilisateur(nom, prenom, email, fonction_id, photo) VALUES(?, ?, ?, ?, 'images/photosUtilisateurs/inconnu.jpg') RETURNING id");
		$req->execute(array($nom, $prenom, $email, $idFonction));
		if($data = $req->fetch())
		{
			$id = $data["id"];
			$login = strtolower($prenom).".".strtolower($nom);
			$mdp = json_decode(motDePasseAleatoire(10));
			$headers = "";
			//$headers .= "From: " . strip_tags($_POST['req-email']) . "\r\n";
			//$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
			//$headers .= "CC: susan@example.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=utf-8\r\n";
			mail($email, "Création de compte", "Bonjour,\n\nPour accéder à votre compte, merci d'utiliser ces identifiants:\n\nLogin: ".$login."\nMot de passe: ".$mdp."\n\nIl est conseillé de changer rapidement ce mot de passe en cliquant sur \"Mon Compte\" (tout en haut à gauche), puis \"Informations Personnelles\", et enfin cliquer sur \"Modifier mot de passe\"\n\nCeci est un mail automatique, merci de ne pas y répondre. ", $headers);
			$mdpHasher = json_decode(hashage($mdp));
			$req2 = $bdd->prepare("INSERT INTO connexion(login, mdp, utilisateur_id) VALUES(?, ?, ?)");
			$req2->execute(array($login, $mdpHasher, $id));
		}
		return json_encode($id);
	}

	function modifierPhotoUtilisateur($idUser, $url)
	{
		include("connexionBdd.php");
		$reponse = false;
		try{
			$req = $bdd->prepare("UPDATE utilisateur SET photo = ? WHERE id = ?");
			$reponse = $req->execute(array($url, $idUser));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function modifierUtilisateur($id, $nom, $prenom, $email, $fonction_id)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, fonction_id = ? WHERE id = ?");
			$reponse = $req->execute(array($nom, $prenom, $email, $fonction_id, $id));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function addFonction($libelle, $niveau_id) //retourne l'id de la nouvelle fonction
	{
		include("connexionBdd.php");
		$idFonction = null;
		$req = $bdd->prepare("INSERT INTO fonction(libelle, niveau_id) VALUES(?, ?) RETURNING id");
		$req->execute(array($libelle, $niveau_id));
		if($data = $req->fetch())
		{
			$idFonction = $data["id"];
		}
		return json_encode($idFonction);
	}

	function getNiveaux()
	{
		include("connexionBdd.php");
		$niveaux = null;
		$i = 0;
		$req = $bdd->query("SELECT id FROM niveau ORDER BY niveau");
		while($data = $req->fetch())
		{
			$niveaux[$i] = json_decode(getNiveauById($data["id"]));
			$i++;
		}
		return json_encode($niveaux);
	}

	function getFonctions()
	{
		include("connexionBdd.php");
		
		$fonctions = null;
		$i = 0;
		$req = $bdd->query("SELECT id FROM fonction ORDER BY libelle");
		while($data = $req->fetch())
		{
			$fonctions[$i] = json_decode(getFonctionById($data["id"]));
			$i++;
		}
		return json_encode($fonctions);
	}

	function getUtilisateurs()
	{
		include("connexionBdd.php");
		 $utilisateurs = null;
		 $i = 0;
		 $req = $bdd->query("SELECT id FROM utilisateur ORDER BY nom, prenom");
		 while($data = $req->fetch())
		 {
			 $utilisateurs[$i] = json_decode(getUtilisateurById($data["id"]));
			 $i++;
		 }
		 return json_encode($utilisateurs);
	}

	function addMiniature($nom, $url)
	{
		include("connexionBdd.php");
		$reponse = false;
		try{
			$req = $bdd->prepare("INSERT INTO miniature(nom, url) VALUES(?, ?)");
			$reponse = $req->execute(array($nom, $url));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}
	
	function addContrat($libelle, $idMiniature, $idUser)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("INSERT INTO contrat(libelle, miniature_id, utilisateur_id) VALUES(?, ?, ?)");
			$reponse = $req->execute(array($libelle, $idMiniature, $idUser));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function modifierContrat($idContrat, $libelle, $idMiniature)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("UPDATE contrat SET libelle = ?, miniature_id = ? WHERE id = ?");
			$reponse = $req->execute(array($libelle, $idMiniature, $idContrat));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function getMiniatures()
	{
		include("connexionBdd.php");
		
		$miniatures = null;
		$i = 0;
		$req = $bdd->query("SELECT id FROM miniature");
		while($data = $req->fetch())
		{
			$miniatures[$i] = json_decode(getMiniatureById($data["id"]));
			$i++;
		}
		return json_encode($miniatures);
	}

	function removeContratById($id)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("DELETE FROM contrat WHERE id = ?");
			$reponse = $req->execute(array($id));
			
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function getNiveauByUtilisateurId($id)
	{
		include("connexionBdd.php");
		$niveau = null;
		$req = $bdd->prepare("SELECT fonction_id FROM utilisateur WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$req2 = $bdd->prepare("SELECT niveau_id FROM fonction WHERE id = ?");
			$req2->execute(array($data["fonction_id"]));
			if($data2 = $req2->fetch())
			{
				$niveau = json_decode(getNiveauById($data2["niveau_id"]));
			}
		}
		return json_encode($niveau);
	}

	function getNomPrenomUtilisateurBySearch($search)
	{
		include("connexionBdd.php");
		
		$search = strtolower($search);
		$users = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id, nom, prenom FROM utilisateur WHERE LOWER(prenom) LIKE ? OR LOWER(nom) LIKE ? ORDER BY nom, prenom LIMIT 5 OFFSET 0");
		$req->execute(array($search."%", $search."%"));
		while($data = $req->fetch())
		{
			$users[$i]["id"] = $data["id"];
			$users[$i]["nom"] = strtolower($data["nom"]);
			$users[$i]["prenom"] = ucfirst(strtolower($data["prenom"]));
			$i++;
		}
		return json_encode($users);
	}

	function getSecteursDomainesSousdomainesContrats()
	{
		include("connexionBdd.php");
		
		$tab = (object)[];
		$tab->secteurs = array();
		$tab->domaines = array();
		$tab->sousDomaines = array();
		$tab->contrats = array();
		
		$req = $bdd->query("SELECT id FROM secteur");
		while($data = $req->fetch())
		{
			$secteur = json_decode(getSecteurById($data["id"]));
			
			array_push($tab->secteurs, $secteur);
		}
		
		$req = $bdd->query("SELECT id FROM domaine");
		while($data = $req->fetch())
		{
			$domaine = json_decode(getDomaineById($data["id"]));
			
			array_push($tab->domaines, $domaine);
		}
		
		$req = $bdd->query("SELECT id FROM sous_domaine");
		while($data = $req->fetch())
		{
			$sousDomaine = json_decode(getSousDomaineById($data["id"]));
			
			array_push($tab->sousDomaines, $sousDomaine);
		}
		$req = $bdd->query("SELECT id FROM contrat");
		while($data = $req->fetch())
		{
			$contrat = json_decode(getContratById($data["id"]));
			
			array_push($tab->contrats, $contrat);
		}
		
		return json_encode($tab);
	}

	function getNbProjets($params)
	{
		include("connexionBdd.php");
		$nb = 0;
		
		if($params == null)
		{
			$req = $bdd->query("SELECT COUNT(*) nb FROM projet");
			if($data = $req->fetch())
			{
				$nb = $data["nb"];
			}
		}
		else{
		$tabTexte = array();
			$txt = strtolower($params->texte->texte);
			if($params->texte->titre)
			{
				array_push($tabTexte, "LOWER(titre) LIKE '%".$txt."%'");
			}
			if($params->texte->description)
			{
				array_push($tabTexte, "LOWER(description) LIKE '%".$txt."%'");
			}
			if($params->texte->contenu)
			{
				array_push($tabTexte, "LOWER(contenu) LIKE '%".$txt."%'");
			}
			
			$requete = "SELECT COUNT(*) nb FROM projet WHERE";
			if(sizeof($tabTexte) > 0)
			{
				$i = 0;
				foreach($tabTexte as $ajout)
				{
					if($i == 0)
					{
						$requete = $requete." (".$ajout;
					}
					else{
						$requete = $requete." OR ".$ajout;
					}
					if($i == (sizeof($tabTexte)-1))
					{
						$requete = $requete.")";
					}
					$i++;
				}
			}
			
			if(isset($params->filtre->contrats) && ($params->filtre->contrats != null) && (sizeof($params->filtre->contrats) > 0))
			{
				$i = 0;
				foreach($params->filtre->contrats as $contrat)
				{
					if($i == 0)
					{
						$requete = $requete." AND (";
					}
					if($i != 0)
					{
						$requete = $requete." OR ";
					}
					$requete = $requete."contrat_id = ".$contrat;
					
					$i++;
					if($i == sizeof($params->filtre->contrats))
					{
						$requete = $requete.")";
					}
				}
			}
			
			$tabFiltre = array();
			/*if(isset($params->filtre->secteurs) && ($params->filtre->secteurs != null) && (sizeof($params->filtre->secteurs) > 0))
			{
				$requeteSecteurs = "";
				$i = 0;
				foreach($params->filtre->secteurs as $secteur)
				{
					if($i != 0)
					{
						$requeteSecteurs = $requeteSecteurs." OR ";
					}
					$requeteSecteurs = $requeteSecteurs."s.id = ".$secteur;
					
					$i++;
				}
				array_push($tabFiltre, $requeteSecteurs);
			}
			if(isset($params->filtre->domaines) && ($params->filtre->domaines != null) && (sizeof($params->filtre->domaines) > 0))
			{
				$requeteDomaines = "";
				$i = 0;
				foreach($params->filtre->domaines as $domaine)
				{
					if($i != 0)
					{
						$requeteDomaines = $requeteDomaines." OR ";
					}
					$requeteDomaines = $requeteDomaines."d.id = ".$domaine;
					
					$i++;
				}
				array_push($tabFiltre, $requeteDomaines);
			}*/
			if(isset($params->filtre->sousDomaines) && ($params->filtre->sousDomaines != null) && (sizeof($params->filtre->sousDomaines) > 0))
			{
				if(isset($params->filtre->type) && ($params->filtre->type != null) && (sizeof($params->filtre->type) > 0))
				{
					$generique = false;
					$specifique = false;
					foreach($params->filtre->type as $type)
					{
						if($type == "generique")
						{
							$generique = true;
						}
						elseif($type == "specifique"){
							$specifique = true;
						}
					}
					if($generique == true && $specifique == true)
					{
						$requeteSousDomaines = "";
						$i = 0;
						foreach($params->filtre->sousDomaines as $sd)
						{
							if($i != 0)
							{
								$requeteSousDomaines = $requeteSousDomaines." OR ";
							}
							$requeteSousDomaines = $requeteSousDomaines."sous_domaine_id = ".$sd;
							
							$i++;
						}
						$requeteSousDomaines = $requeteSousDomaines." OR sous_domaine_id IS NULL";
					}
					elseif($generique)
					{
						$requeteSousDomaines = "sous_domaine_id IS NULL";
					}
					elseif($specifique)
					{
						$requeteSousDomaines = "";
						$i = 0;
						foreach($params->filtre->sousDomaines as $sd)
						{
							if($i != 0)
							{
								$requeteSousDomaines = $requeteSousDomaines." OR ";
							}
							$requeteSousDomaines = $requeteSousDomaines."sous_domaine_id = ".$sd;
							
							$i++;
						}
					}
					else{
						$requeteSousDomaines = "sous_domaine_id = 0";
					}
				}
				else{
					$requeteSousDomaines = "";
					$i = 0;
					foreach($params->filtre->sousDomaines as $sd)
					{
						if($i != 0)
						{
							$requeteSousDomaines = $requeteSousDomaines." OR ";
						}
						$requeteSousDomaines = $requeteSousDomaines."sous_domaine_id = ".$sd;
						
						$i++;
					}
					//return json_encode("PAS ENTRER");
				}
				array_push($tabFiltre, $requeteSousDomaines);
			}
			if(sizeof($tabFiltre) > 0)
			{
				$i = 0;
				$requete = $requete." AND (";
				foreach($tabFiltre as $filtre)
				{
					if($i != 0)
					{
						$requete = $requete." OR ";
					}
					$requete = $requete.$filtre;
					if($i == (sizeof($tabFiltre)-1))
					{
						$requete = $requete.")";
					}
					$i++;
				}
			}
			
			
			$req = $bdd->query($requete);
			while($data = $req->fetch())
			{
				$nb = $data["nb"];
			}
		
		}
		return json_encode($nb);
	}

	function getProjetsByNum($nb, $debut, $params, $search)
	{
		include("connexionBdd.php");
		
		$projets = null;
		$i = 0;
		
		if($params == null && $search == null)
		{
			$req = $bdd->prepare("SELECT id, titre, description, date_creation, date_derniere_maj, sous_domaine_id FROM projet ORDER BY date_creation DESC LIMIT ? OFFSET ?");
			$req->execute(array($nb, $debut));
			while($data = $req->fetch())
			{
				$projets[$i]["id"] = $data["id"];
				$projets[$i]["titre"] = $data["titre"];
				$projets[$i]["description"] = $data["description"];
				$projets[$i]["date_creation"] = json_decode(modifierDate($data["date_creation"]));
				$projets[$i]["date_derniere_maj"] = json_decode(modifierDate($data["date_derniere_maj"]));
				$projets[$i]["sous_domaine_id"] = $data["sous_domaine_id"];
				
				$i++;
			}
		}elseif($search != null){
            $idsProjets = json_decode(getSearchProjetByProjectSearch($search));
            $tabIdsProjets = array();
            if($idsProjets != null)
            {
                foreach($idsProjets as $idProjet)
                {
                    array_push($tabIdsProjets, $idProjet->id);
                }
            }
            $resultIds = "'".implode("','",$tabIdsProjets)."'";
      
            
            if(sizeof($tabIdsProjets) > 0)
			{
				$req = $bdd->query("SELECT id, titre, description, date_creation, date_derniere_maj, sous_domaine_id FROM projet WHERE id IN (".implode(',',$tabIdsProjets).") ORDER BY date_creation DESC");
				while($data = $req->fetch())
				{
					$projets[$i]["id"] = $data["id"];
					$projets[$i]["titre"] = $data["titre"];
					$projets[$i]["description"] = $data["description"];
					$projets[$i]["date_creation"] = json_decode(modifierDate($data["date_creation"]));
					$projets[$i]["date_derniere_maj"] = json_decode(modifierDate($data["date_derniere_maj"]));
					$projets[$i]["sous_domaine_id"] = $data["sous_domaine_id"];
					
					$i++;
				}
			}
        }
		else{
			$tabTexte = array();
			$txt = strtolower($params->texte->texte);
			if($params->texte->titre)
			{
				array_push($tabTexte, "LOWER(titre) LIKE '%".$txt."%'");
			}
			if($params->texte->description)
			{
				array_push($tabTexte, "LOWER(description) LIKE '%".$txt."%'");
			}
			if($params->texte->contenu)
			{
				array_push($tabTexte, "LOWER(contenu) LIKE '%".$txt."%'");
			}
			
			$requete = "SELECT id projet_id, titre projet_titre, description projet_description, date_creation projet_date_creation, date_derniere_maj projet_date_derniere_maj, contrat_id, sous_domaine_id FROM projet  WHERE";
			if(sizeof($tabTexte) > 0)
			{
				$i = 0;
				foreach($tabTexte as $ajout)
				{
					if($i == 0)
					{
						$requete = $requete." (".$ajout;
					}
					else{
						$requete = $requete." OR ".$ajout;
					}
					if($i == (sizeof($tabTexte)-1))
					{
						$requete = $requete.")";
					}
					$i++;
				}
			}
			
			if(isset($params->filtre->contrats) && ($params->filtre->contrats != null) && (sizeof($params->filtre->contrats) > 0))
			{
				$i = 0;
				foreach($params->filtre->contrats as $contrat)
				{
					if($i == 0)
					{
						$requete = $requete." AND (";
					}
					if($i != 0)
					{
						$requete = $requete." OR ";
					}
					$requete = $requete."contrat_id = ".$contrat;
					
					$i++;
					if($i == sizeof($params->filtre->contrats))
					{
						$requete = $requete.")";
					}
				}
			}
			
			$tabFiltre = array();
			/*if(isset($params->filtre->secteurs) && ($params->filtre->secteurs != null) && (sizeof($params->filtre->secteurs) > 0))
			{
				$requeteSecteurs = "";
				$i = 0;
				foreach($params->filtre->secteurs as $secteur)
				{
					if($i != 0)
					{
						$requeteSecteurs = $requeteSecteurs." OR ";
					}
					$requeteSecteurs = $requeteSecteurs."s.id = ".$secteur;
					
					$i++;
				}
				array_push($tabFiltre, $requeteSecteurs);
			}
			if(isset($params->filtre->domaines) && ($params->filtre->domaines != null) && (sizeof($params->filtre->domaines) > 0))
			{
				$requeteDomaines = "";
				$i = 0;
				foreach($params->filtre->domaines as $domaine)
				{
					if($i != 0)
					{
						$requeteDomaines = $requeteDomaines." OR ";
					}
					$requeteDomaines = $requeteDomaines."d.id = ".$domaine;
					
					$i++;
				}
				array_push($tabFiltre, $requeteDomaines);
			}*/
			if(isset($params->filtre->sousDomaines) && ($params->filtre->sousDomaines != null) && (sizeof($params->filtre->sousDomaines) > 0))
			{
				
				if(isset($params->filtre->type) && ($params->filtre->type != null) && (sizeof($params->filtre->type) > 0))
				{
					$generique = false;
					$specifique = false;
					foreach($params->filtre->type as $type)
					{
						if($type == "generique")
						{
							$generique = true;
						}
						elseif($type == "specifique"){
							$specifique = true;
						}
					}
					if($generique == true && $specifique == true)
					{
						$requeteSousDomaines = "";
						$i = 0;
						foreach($params->filtre->sousDomaines as $sd)
						{
							if($i != 0)
							{
								$requeteSousDomaines = $requeteSousDomaines." OR ";
							}
							$requeteSousDomaines = $requeteSousDomaines."sous_domaine_id = ".$sd;
							
							$i++;
						}
						$requeteSousDomaines = $requeteSousDomaines." OR sous_domaine_id IS NULL";
					}
					elseif($generique)
					{
						$requeteSousDomaines = "sous_domaine_id IS NULL";
					}
					elseif($specifique)
					{
						$requeteSousDomaines = "";
						$i = 0;
						foreach($params->filtre->sousDomaines as $sd)
						{
							if($i != 0)
							{
								$requeteSousDomaines = $requeteSousDomaines." OR ";
							}
							$requeteSousDomaines = $requeteSousDomaines."sous_domaine_id = ".$sd;
							
							$i++;
						}
					}
					else{
						$requeteSousDomaines = "sous_domaine_id = 0";
					}
				}
				else{
					$requeteSousDomaines = "";
					$i = 0;
					foreach($params->filtre->sousDomaines as $sd)
					{
						if($i != 0)
						{
							$requeteSousDomaines = $requeteSousDomaines." OR ";
						}
						$requeteSousDomaines = $requeteSousDomaines."sous_domaine_id = ".$sd;
						
						$i++;
					}
				}
				array_push($tabFiltre, $requeteSousDomaines);
			}
			
			if(sizeof($tabFiltre) > 0)
			{
				$i = 0;
				$requete = $requete." AND (";
				foreach($tabFiltre as $filtre)
				{
					if($i != 0)
					{
						$requete = $requete." OR ";
					}
					$requete = $requete.$filtre;
					if($i == (sizeof($tabFiltre)-1))
					{
						$requete = $requete.")";
					}
					$i++;
				}
			}
			$requete = $requete." ORDER BY date_creation DESC LIMIT ? OFFSET ?";
			$req = $bdd->prepare($requete);
			$req->execute(array($nb, $debut));
			$i = 0;
			while($data = $req->fetch())
			{
				$projets[$i]["id"] = $data["projet_id"];
				$projets[$i]["titre"] = $data["projet_titre"];
				$projets[$i]["description"] = $data["projet_description"];
				$projets[$i]["date_creation"] = json_decode(modifierDate($data["projet_date_creation"]));
				$projets[$i]["date_derniere_maj"] = json_decode(modifierDate($data["projet_date_derniere_maj"]));
				$projets[$i]["sous_domaine_id"] = $data["sous_domaine_id"];
				$projets[$i]["contrat"] = json_decode(getContratById($data["contrat_id"]));
				
				$i++;
			}
		}
		return json_encode($projets);
	}


	function getSousDomaineIdByProjetId($id)
	{
		include("connexionBdd.php");
		
		$idSousDomaine = null;
		$req = $bdd->prepare("SELECT sous_domaine_id id FROM projet WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$idSousDomaine = $data["id"];
		}
		return json_encode($idSousDomaine);
	}

	function getDomaineIdByProjetId($id)
	{
		include("connexionBdd.php");
		
		$idProjet = null;
		$req = $bdd->prepare("SELECT sous_domaine_id id FROM projet WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$idProjet = json_decode(getDomaineIdBySousDomaineId($data["id"]));
		}
		
		return json_encode($idProjet);
	}

	function getDomaineIdBySousDomaineId($id)
	{
		include("connexionBdd.php");
		
		$idDomaine = null;
		$req = $bdd->prepare("SELECT domaine_id id FROM sous_domaine WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$idDomaine = $data["id"];
		}
		return json_encode($idDomaine);
	}

	function getSecteurIdByProjetId($id)
	{
		include("connexionBdd.php");
		
		$idProjet = null;
		
		$req = $bdd->prepare("SELECT sous_domaine_id id FROM projet WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			if($data["id"] != null)
			{
				$idProjet = json_decode(getSecteurIdBySousDomaineId($data["id"]));
			}
			else{
				$req2 = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
				$req2->execute(array($id));
				if($data2 = $req2->fetch())
				{
					$idProjet = json_decode(getSecteurIdByDomaineId($data2["domaine_id"]));
				}
			}
		}
		
		return json_encode($idProjet);
	}

	function getSecteurIdBySousDomaineId($id)
	{
		include("connexionBdd.php");
		
		$idSecteur = null;
		$req = $bdd->prepare("SELECT domaine_id id FROM sous_domaine WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$req2 = $bdd->prepare("SELECT secteur_id id FROM domaine WHERE id = ?");
			$req2->execute(array($data["id"]));
			if($data2 = $req2->fetch())
			{
				$idSecteur = $data2["id"];
			}
		}
		return json_encode($idSecteur);
	}
	
	function getSecteurIdByDomaineId($id)
	{
		include("connexionBdd.php");
		
		$idSecteur = null;
		$req = $bdd->prepare("SELECT secteur_id id FROM domaine WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$idSecteur = $data["id"];
		}
		return json_encode($idSecteur);
	}

	function removeAbonnementById($element, $idUser)
	{
		include("connexionBdd.php");
		try{
			$elem = "";
			$tab = explode("-", $element);
			if($tab[0] == "sousDomaine")
			{
				$elem = "sous_domaine_id";
			}
			else{
				$elem = $tab[0]."_id";
			}
			$reponse = false;
			
			$req = $bdd->prepare("SELECT * FROM abonnement WHERE utilisateur_id = ? AND ".$elem." = ?");
			$req->execute(array($idUser, $tab[1]));
			if($data = $req->fetch())
			{
				if($data["domaine_id"] != null)
				{
					$req2 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
					$req2->execute(array($data["domaine_id"]));
					if($data2 = $req2->fetch())
					{
						$req3 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND secteur_id = ?");
						$req3->execute(array($idUser, $data2["secteur_id"]));
						if($data3 = $req3->fetch())
						{
							$req4 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
							$req4->execute(array($data2["secteur_id"]));
							while($data4 = $req4->fetch())
							{
								addAbonnement($idUser, null, $data4["id"], null, null, null);
							}
							$req4 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND secteur_id = ?");
							$req4->execute(array($idUser, $data2["secteur_id"]));
						}
					}
				}
				elseif($data["sous_domaine_id"] != null)
				{
					$req2 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
					$req2->execute(array($data["sous_domaine_id"]));
					if($data2 = $req2->fetch())
					{
						$req3 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
						$req3->execute(array($idUser, $data2["domaine_id"]));
						if($data3 = $req3->fetch())
						{
							$req4 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
							$req4->execute(array($data2["domaine_id"]));
							while($data4 = $req4->fetch())
							{
								addAbonnement($idUser, null, null, $data4["id"], null, null);
							}
							$req4 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
							$req4->execute(array($idUser, $data2["domaine_id"]));
						}
						else{
							$req3 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
							$req3->execute(array($data2["domaine_id"]));
							if($data3 = $req3->fetch())
							{
								$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND secteur_id = ?");
								$req4->execute(array($idUser, $data3["secteur_id"]));
								if($data4 = $req4->fetch())
								{
									$req5 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
									while($data5 = $req5->fetch())
									{
										addAbonnement($idUser, null, $data5["id"], null, null, null);
									}
									$req5 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND secteur_id = ?");
									$req5->execute(array($idUser, $data3["secteur_id"]));
								}
							}
						}
					}
				}
				elseif($data["projet_id"] != null)
				{
					$req2 = $bdd->prepare("SELECT sous_domaine_id FROM projet WHERE id = ?");
					$req2->execute(array($data["projet_id"]));
					if($data2 = $req2->fetch())
					{
						if($data2["sous_domaine_id"] != null) //Si le projet est spécifique
						{
							$req3 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
							$req3->execute(array($idUser, $data2["sous_domaine_id"]));
							if($data3 = $req3->fetch())
							{
								$req4 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
								$req4->execute(array($data2["sous_domaine_id"]));
								while($data4 = $req4->fetch())
								{
									addAbonnement($idUser, null, null, null, $data4["id"], null);
								}
								$req4 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
								$req4->execute(array($idUser, $data2["sous_domaine_id"]));
							}
							else{
								$req3 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
								$req3->execute(array($data2["sous_domaine_id"]));
								if($data3 = $req3->fetch())
								{
									$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
									$req4->execute(array($idUser, $data3["domaine_id"]));
									if($data4 = $req4->fetch())
									{
										$req5 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
										$req5->execute(array($data3["domaine_id"]));
										while($data5 = $req5->fetch())
										{
											addAbonnement($idUser, null, null, $data5["id"], null, null);
										}
										$req5 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
										$req5->execute(array($idUser, $data3["domaine_id"]));
									}
								}
								else{
									$req3 = $bdd->prepare("SELECT s.id sid FROM sous_domaine sd JOIN domaine d ON d.id = sd.domaine_id JOIN secteur s ON d.secteur_id = s.id WHERE sd.id = ?");
									$req3->execute(array($data2["sous_domaine_id"]));
									if($data3 = $req3->fetch())
									{
										$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND secteur_id = ?");
										$req4->execute(array($idUser, $data3["sid"]));
										if($data4 = $req4->fetch())
										{
											$req5 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
											$req5->execute(array($data3["sid"]));
											while($data5 = $req5->fetch())
											{
												addAbonnement($idUser, null, $data5["id"], null, null, null);
											}
											$req5 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND secteur_id = ?");
											$req5->execute(array($idUser, $data3["sid"]));
										}
									}
								}
							}
						}
						else{
							/*$req3 = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
							$req3->execute(array($data["projet_id"]));
							while($data3 = $req3->fetch())
							{
								$req4 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
								$req4->execute(array($data3["domaine_id"]));
								while($data4 = $req4->fetch())
								{
									
									$req5 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
									$req5->execute(array($data4["id"]));
									while($data5 = $req5->fetch())
									{
										$abo = addAbonnement($idUser, null, null, null, $data5["id"], null);
										return json_encode("TEST-".$abo);
									}
									$req5 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
									$req5->execute(array($data3["domaine_id"]));
									while($data5 = $req5->fetch())
									{
										//return json_encode("TEST");
										addAbonnement($idUser, null, null, null, $data5["projet_id"], null);
									}
									$req5 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
									$req5->execute(array($idUser, $data4["id"]));
								}
								
								$req5 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
								$req5->execute(array($data3["domaine_id"]));
								if($data5 = $req5->fetch())
								{
									$req6 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
									$req6->execute(array($data5["secteur_id"]));
									while($data6 = $req6->fetch())
									{
										addAbonnement($idUser, null, $data6["id"], null, null, null);
									}
									$req6 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND secteur_id = ?");
									$req6->execute(array($idUser, $data5["secteur_id"]));
								}
								
								$req5 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
								$req5->execute(array($idUser, $data3["domaine_id"]));
							}*/
						}
					}
				}
				$req = $bdd->prepare("DELETE FROM abonnement WHERE ".$elem." = ? AND utilisateur_id = ?");
				$reponse = $req->execute(array($tab[1], $idUser));
			}
			else{
				switch($tab[0])
				{
					case "projet": $req = $bdd->prepare("SELECT sous_domaine_id FROM projet WHERE id = ?");
					$req->execute(array($tab[1]));
					if($data = $req->fetch())
					{
						if($data["sous_domaine_id"] != null)
						{
							$req2 = $bdd->prepare("SELECT id FROM abonnement WHERE sous_domaine_id = ? AND utilisateur_id = ?");
							$req2->execute(array($data["sous_domaine_id"], $idUser));
							if($data2 = $req2->fetch())
							{
								$req3 = $bdd->prepare("DELETE FROM abonnement WHERE sous_domaine_id = ? AND utilisateur_id = ?");
								$rep = $req3->execute(array($data["sous_domaine_id"], $idUser));
								if($rep)
								{
									$req4 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
									$req4->execute(array($data["sous_domaine_id"]));
									while($data4 = $req4->fetch())
									{
										if($data4["id"] != $tab[1])
										{
											addAbonnement($idUser, null, null, null, $data4["id"], null);
										}
									}
									
									$req4 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
									$req4->execute(array($data["sous_domaine_id"]));
									if($data4 = $req4->fetch())
									{
										$req5 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
										$req5->execute(array($data4["domaine_id"]));
										while($data5 = $req5->fetch())
										{
											if($data5["projet_id"] != $tab[1])
											{
												addAbonnement($idUser, null, null, null, $data5["projet_id"], null);
											}
											$reponse = true;
										}
									}
								}
							}
							else{
								$req3 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
								$req3->execute(array($data["sous_domaine_id"]));
								if($data3 = $req3->fetch())
								{
									$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE domaine_id = ? AND utilisateur_id = ?");
									$req4->execute(array($data3["domaine_id"], $idUser));
									if($data4 = $req4->fetch())
									{
										$req5 = $bdd->prepare("DELETE FROM abonnement WHERE domaine_id = ? AND utilisateur_id = ?");
										$rep = $req5->execute(array($data3["domaine_id"], $idUser));
										if($rep)
										{
											$req6 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
											$req6->execute(array($data3["domaine_id"]));
											while($data6 = $req6->fetch())
											{
												if($data["sous_domaine_id"] != $data6["id"])
												{
													addAbonnement($idUser, null, null, $data6["id"], null, null);
												}
												$reponse = true;
											}
											
											$req6 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
											$req6->execute(array($data["sous_domaine_id"]));
											while($data6 = $req6->fetch())
											{
												if($data6["id"] != $tab[1])
												{
													addAbonnement($idUser, null, null, null, $data6["id"], null);
												}
											}
											
											$req6 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
											$req6->execute(array($data["sous_domaine_id"]));
											if($data6 = $req6->fetch())
											{
												$req7 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
												$req7->execute(array($data3["domaine_id"]));
												while($data7 = $req7->fetch())
												{
													if($data7["projet_id"] != $tab[1])
													{
														addAbonnement($idUser, null, null, null, $data7["projet_id"], null);
													}
													$reponse = true;
												}
											}
										}
									}
									else{
										$req5 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
										$req5->execute(array($data3["domaine_id"]));
										if($data5 = $req5->fetch())
										{
											$req6 = $bdd->prepare("SELECT id FROM abonnement WHERE secteur_id = ? AND utilisateur_id = ?");
											$req6->execute(array($data5["secteur_id"], $idUser));
											if($data6 = $req6->fetch())
											{
												$req7 = $bdd->prepare("DELETE FROM abonnement WHERE secteur_id = ? AND utilisateur_id = ?");
												$rep = $req7->execute(array($data5["secteur_id"], $idUser));
												if($rep)
												{
													$req8 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
													$req8->execute(array($data5["secteur_id"]));
													while($data8 = $req8->fetch())
													{
														if($data8["id"] != $data3["domaine_id"])
														{
															addAbonnement($idUser, null, $data8["id"], null, null, null);
														}
														$reponse = true;
													}
													
													$req8 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
													$req8->execute(array($data3["domaine_id"]));
													while($data8 = $req8->fetch())
													{
														if($data8["id"] != $data["sous_domaine_id"])
														{
															addAbonnement($idUser, null, null, $data8["id"], null, null);
														}
														$reponse = true;
													}
													
													$req8 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
													$req8->execute(array($data["sous_domaine_id"]));
													while($data8 = $req8->fetch())
													{
														if($data8["id"] != $tab[1])
														{
															addAbonnement($idUser, null, null, null, $data8["id"], null);
														}
														$reponse = true;
													}
													
													$req8 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
													$req8->execute(array($data["sous_domaine_id"]));
													if($data8 = $req8->fetch())
													{
														$req9 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
														$req9->execute(array($data8["domaine_id"]));
														while($data9 = $req9->fetch())
														{
															if($data9["projet_id"] != $tab[1])
															{
																addAbonnement($idUser, null, null, null, $data9["projet_id"], null);
															}
															$reponse = true;
														}
													}
												}
											}
										}
									}
								}
							}
						}
						else{
							$req2 = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
							$req2->execute(array($tab[1]));
							while($data2 = $req2->fetch())
							{
								$req3 = $bdd->prepare("SELECT id FROM abonnement WHERE domaine_id = ? AND utilisateur_id = ?");
								$req3->execute(array($data2["domaine_id"], $idUser));
								if($data3 = $req3->fetch())
								{
									$req4 = $bdd->prepare("DELETE FROM abonnement WHERE domaine_id = ? AND utilisateur_id = ?");
									$rep = $req4->execute(array($data2["domaine_id"], $idUser));
									if($rep)
									{
										$req5 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
										$req5->execute(array($data2["domaine_id"]));
										while($data5 = $req5->fetch())
										{
											if($data5["projet_id"] != $tab[1])
											{
												addAbonnement($idUser, null, null, null, $data5["projet_id"], null);
											}
											$reponse = true;
										}
										
										$listeSousDomaines = array();
										$req5 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
										$req5->execute(array($data2["domaine_id"]));
										while($data5 = $req5->fetch())
										{
											array_push($listeSousDomaines, $data5["id"]);
										}
										
										foreach($listeSousDomaines as $sdom)
										{
											$req5 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
											$req5->execute(array($sdom));
											while($data5 = $req5->fetch())
											{
												if($data5["id"] != $tab[1])
												{
													addAbonnement($idUser, null, null, null, $data5["id"], null);
												}
												$reponse = true;
											}
										}
									}
								}
								else{
									$req4 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
									$req4->execute(array($data2["domaine_id"]));
									if($data4 = $req4->fetch())
									{
										$req5 = $bdd->prepare("SELECT id FROM abonnement WHERE secteur_id = ? AND utilisateur_id = ?");
										$req5->execute(array($data4["secteur_id"], $idUser));
										if($data5 = $req5->fetch())
										{
											$req6 = $bdd->prepare("DELETE FROM abonnement WHERE secteur_id = ? AND utilisateur_id = ?");
											$rep = $req6->execute(array($data4["secteur_id"], $idUser));
											if($rep)
											{
												$listeDomaines = array(); //Pour connaitre tous les domaines liés à ce projet générique
												$req7 = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
												$req7->execute(array($tab[1]));
												while($data7 = $req7->fetch())
												{
													array_push($listeDomaines, $data7["domaine_id"]);
												}
												
												$req7 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
												$req7->execute(array($data4["secteur_id"]));
												while($data7 = $req7->fetch())
												{
													if(!in_array($data7["id"], $listeDomaines))
													{
														addAbonnement($idUser, null, $data7["id"], null, null, null);
													}
													$reponse = true;
												}
												
												foreach($listeDomaines as $dom)
												{
													$req7 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
													$req7->execute(array($dom));
													while($data7 = $req7->fetch())
													{
														$req8 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
														$req8->execute(array($data7["id"]));
														while($data8 = $req8->fetch())
														{
															if($data8["id"] != $tab[1])
															{
																addAbonnement($idUser, null, null, null, $data8["id"], null);
															}
															$reponse = true;
														}
													}
												}
												
												foreach($listeDomaines as $dom)
												{
													$req7 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
													$req7->execute(array($dom));
													while($data7 = $req7->fetch())
													{
														if($data7["projet_id"] != $tab[1])
														{
															addAbonnement($idUser, null, null, null, $data7["projet_id"], null);
														}
														$reponse = true;
													}
												}
											}
										}
										else{ //sous-domaine
											$listeSousDomaines = array();
											$req6 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
											$req6->execute(array($data2["domaine_id"]));
											while($data6 = $req6->fetch())
											{
												array_push($listeSousDomaines, $data6["id"]);
											}
											
											foreach($listeSousDomaines as $sdom)
											{
												$req9 = $bdd->prepare("SELECT id FROM abonnement WHERE sous_domaine_id = ? AND utilisateur_id = ?");
												$req9->execute(array($sdom, $idUser));
												if($data9 = $req9->fetch())
												{
													$req6 = $bdd->prepare("DELETE FROM abonnement WHERE sous_domaine_id = ? AND utilisateur_id = ?");
													$rep = $req6->execute(array($sdom, $idUser));
													if($rep)
													{
														$req7 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
														$req7->execute(array($sdom));
														while($data7 = $req7->fetch())
														{
															if($data7["id"] != $tab[1])
															{
																addAbonnement($idUser, null, null, null, $data7["id"], null);
															}
														}
														
														$req7 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
														$req7->execute(array($sdom));
														if($data7 = $req7->fetch())
														{
															$req8 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
															$req8->execute(array($data7["domaine_id"]));
															while($data8 = $req8->fetch())
															{
																if($data8["projet_id"] != $tab[1])
																{
																	addAbonnement($idUser, null, null, null, $data8["projet_id"], null);
																}
																$reponse = true;
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
					break;
					case "sousDomaine": $req = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
					$req->execute(array($tab[1]));
					if($data = $req->fetch())
					{
						$req2 = $bdd->prepare("SELECT id FROM abonnement WHERE domaine_id = ? AND utilisateur_id = ?");
						$req2->execute(array($data["domaine_id"], $idUser));
						if($data2 = $req2->fetch())
						{
							$req2 = $bdd->prepare("DELETE FROM abonnement WHERE domaine_id = ? AND utilisateur_id = ?");
							$rep = $req2->execute(array($data["domaine_id"], $idUser));
							if($rep)
							{
								$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
								$req3->execute(array($data["domaine_id"]));
								while($data3 = $req3->fetch())
								{
									if($data3["id"] != $tab[1])
									{
										addAbonnement($idUser, null, null, $data3["id"], null, null);
									}
									$reponse = true;
								}
							}
						}
						else{
							$req2 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
							$req2->execute(array($data["domaine_id"]));
							if($data2 = $req2->fetch())
							{
								$req3 = $bdd->prepare("DELETE FROM abonnement WHERE secteur_id = ? AND utilisateur_id = ?");
								$rep = $req3->execute(array($data2["secteur_id"], $idUser));
								if($rep)
								{
									$req3 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
									$req3->execute(array($data2["secteur_id"]));
									while($data3 = $req3->fetch())
									{
										if($data3["id"] != $data["domaine_id"])
										{
											addAbonnement($idUser, null, $data3["id"], null, null, null);
										}
									}
									
									$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
									$req3->execute(array($data["domaine_id"]));
									while($data3 = $req3->fetch())
									{
										if($data3["id"] != $tab[1])
										{
											addAbonnement($idUser, null, null, $data3["id"], null, null);
										}
										$reponse = true;
									}
								}
							}
						}
					}
					break;
					case "domaine":
					$req = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
					$req->execute(array($tab[1]));
					if($data = $req->fetch())
					{
						$req2 = $bdd->prepare("DELETE FROM abonnement WHERE secteur_id = ? AND utilisateur_id = ?");
						$rep = $req2->execute(array($data["secteur_id"], $idUser));
						if($rep)
						{
							$req2 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
							$req2->execute(array($data["secteur_id"]));
							while($data2 = $req2->fetch())
							{
								if($data2["id"] != $tab[1])
								{
									addAbonnement($idUser, null, $data2["id"], null, null, null);
								}
							}
							$reponse = true;
						}
					}
					break;
					default: $reponse = false;
					break;
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function addAbonnement($idUser, $idSecteur, $idDomaine, $idSousDomaine, $idProjet, $idContrat)
	{
		include("connexionBdd.php");
		
		try{
			if($idSecteur != null)
			{
				$req = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
				$req->execute(array($idSecteur));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
					$req2->execute(array($idUser, $data["id"]));
				}
				
				$req = $bdd->prepare("SELECT sd.id idsd FROM sous_domaine sd JOIN domaine d ON sd.domaine_id = d.id JOIN secteur s ON d.secteur_id = s.id WHERE s.id = ?");
				$req->execute(array($idSecteur));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
					$req2->execute(array($idUser, $data["idsd"]));
				}
				
				$req = $bdd->prepare("SELECT p.id pid FROM projet p JOIN sous_domaine sd ON p.sous_domaine_id = sd.id JOIN domaine d ON sd.domaine_id = d.id JOIN secteur s ON s.id = d.secteur_id WHERE s.id = ?");
				$req->execute(array($idSecteur));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
					$req2->execute(array($idUser, $data["pid"]));
				}
				
				$req = $bdd->prepare("SELECT prdm.projet_id idprdm FROM projet_domaine prdm JOIN domaine d ON d.id = prdm.domaine_id JOIN secteur s ON s.id = d.secteur_id WHERE s.id = ?");
				$req->execute(array($idSecteur));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
					$req2->execute(array($idUser, $data["idprdm"]));
				}
				
				$req = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, secteur_id) VALUES(?, ?)");
				$reponse = $req->execute(array($idUser, $idSecteur));
				
				/*$req = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
				$req->execute(array($idSecteur));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, domaine_id) VALUES(?, ?)");
					$req2->execute(array($idUser, $data["id"]));
					$req2 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
					$req2->execute(array($data["id"]));
					while($data2 = $req2->fetch())
					{
						$req3 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, sous_domaine_id) VALUES(?, ?)");
						$req3->execute(array($idUser, $data2["id"]));
						$req3 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
						$req3->execute(array($data2["id"]));
						while($data3 = $req3->fetch())
						{
							$req4 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, projet_id) VALUES(?, ?)");
							$req4->execute(array($idUser, $data3["id"]));
						}
					}
				}*/
			}
			elseif($idDomaine != null){
				$req = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
				$req->execute(array($idDomaine));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("DELETE FROM abonnement WHERE sous_domaine_id = ? AND utilisateur_id = ?");
					$req2->execute(array($data["id"], $idUser));
				}
				
				$req = $bdd->prepare("SELECT p.id pid FROM projet p JOIN sous_domaine sd ON sd.id = p.sous_domaine_id JOIN domaine d ON d.id = sd.domaine_id WHERE d.id = ?");
				$req->execute(array($idDomaine));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
					$req2->execute(array($idUser, $data["pid"]));
				}
				
				$req = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
				$req->execute(array($idDomaine));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
					$req2->execute(array($idUser, $data["projet_id"]));
				}
				
				$req = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, domaine_id) VALUES(?, ?)");
				$reponse = $req->execute(array($idUser, $idDomaine));
				
				//////////
				$req = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
				$req->execute(array($idDomaine));
				if($data = $req->fetch())
				{
					$aboTousDomaines = true;
					$req2 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
					$req2->execute(array($data["secteur_id"]));
					while($data2 = $req2->fetch())
					{
						$req3 = $bdd->prepare("SELECT id FROM abonnement WHERE domaine_id = ? AND utilisateur_id = ?");
						$req3->execute(array($data2["id"], $idUser));
						if(!$data3 = $req3->fetch())
						{
							$aboTousDomaines = false;
						}
					}
					
					if($aboTousDomaines)
					{
						$req2 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
						$req2->execute(array($data["secteur_id"]));
						while($data2 = $req2->fetch())
						{
							$req3 = $bdd->prepare("DELETE FROM abonnement WHERE domaine_id = ? AND utilisateur_id = ?");
							$req3->execute(array($data2["id"], $idUser));
						}
						
						$req2 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, secteur_id) VALUES(?, ?)");
						$req2->execute(array($idUser, $data["secteur_id"]));
					}
				}
				/*$req = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
				$req->execute(array($idDomaine));
				if($data = $req->fetch())
				{
					
					$verif = true;
					$req2 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
					$req2->execute(array($data["secteur_id"]));
					while($data2 = $req2->fetch())
					{
						if($data2["id"] != $idDomaine)
						{
							$req3 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
							$req3->execute(array($idUser, $data2["id"]));
							if(!$data3 = $req3->fetch())
							{
								$verif = false;
							}
						}
					}
					
					if($verif)
					{
						$req2 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, secteur_id) VALUES(?, ?)");
						$req2->execute(array($idUser, $data["secteur_id"]));
					}
				}
				
				$req = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
				$req->execute(array($idDomaine));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, sous_domaine_id) VALUES(?, ?)");
					$req2->execute(array($idUser, $data["id"]));
					$req2 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
					$req2->execute(array($data["id"]));
					while($data2 = $req2->fetch())
					{
						$req3 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, projet_id) VALUES(?, ?)");
						$req3->execute(array($idUser, $data2["id"]));
					}
				}*/
			}
			elseif($idSousDomaine != null){
				$req = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
				$req->execute(array($idSousDomaine));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
					$req2->execute(array($idUser, $data["id"]));
				}
				
				$req = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, sous_domaine_id) VALUES(?, ?)");
				$reponse = $req->execute(array($idUser, $idSousDomaine));
				
				$req = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
				$req->execute(array($idSousDomaine));
				if($data = $req->fetch())
				{
					$aboTousSousDomaines = true;
					$req2 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
					$req2->execute(array($data["domaine_id"]));
					while($data2 = $req2->fetch())
					{
						$req3 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
						$req3->execute(array($idUser, $data2["id"]));
						if(!$data3 = $req3->fetch())
						{
							$aboTousSousDomaines = false;
						}
					}
					
					if($aboTousSousDomaines)
					{
						$req2 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
						$req2->execute(array($data["domaine_id"]));
						while($data2 = $req2->fetch())
						{
							$req3 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
							$req3->execute(array($idUser, $data2["id"]));
						}
						$req2 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
						$req2->execute(array($data["domaine_id"]));
						while($data2 = $req2->fetch())
						{
							$req3 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
							$req3->execute(array($idUser, $data2["projet_id"]));
						}
						$req3 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, domaine_id) VALUES(?, ?)");
						$req3->execute(array($idUser, $data["domaine_id"]));
						
						$req3 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
						$req3->execute(array($data["domaine_id"]));
						if($data3 = $req3->fetch())
						{
							$aboTousDomaines = true;
							$req4 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
							$req4->execute(array($data3["secteur_id"]));
							while($data4 = $req4->fetch())
							{
								$req5 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
								$req5->execute(array($idUser, $data4["id"]));
								if(!$data5 = $req5->fetch())
								{
									$aboTousDomaines = false;
								}
							}
							
							if($aboTousDomaines)
							{
								$req4 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
								$req4->execute(array($data3["secteur_id"]));
								while($data4 = $req4->fetch())
								{
									$req5 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
									$req5->execute(array($idUser, $data4["id"]));
								}
								
								$req4 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, secteur_id) VALUES(?, ?)");
								$req4->execute(array($idUser, $data3["secteur_id"]));
							}
						}
					}
				}
				
				/*$req = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
				$req->execute(array($idSousDomaine));
				if($data = $req->fetch())
				{
					$verif = true;
					$req2 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
					$req2->execute(array($data["domaine_id"]));
					while($data2 = $req2->fetch())
					{
						if($data2["id"] != $idSousDomaine)
						{
							$req3 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
							$req3->execute(array($idUser, $data2["id"]));
							if(!$data3 = $req3->fetch())
							{
								$verif = false;
							}
						}
					}
					if($verif)
					{
						$req2 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, domaine_id) VALUES(?, ?)");
						$req2->execute(array($idUser, $data["domaine_id"]));
						
						$req2 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
						$req2->execute(array($data["domaine_id"]));
						if($data2 = $req2->fetch())
						{
							$verif2 = true;
							$req3 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
							$req3->execute(array($data2["secteur_id"]));
							while($data3 = $req3->fetch())
							{
								$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
								$req4->execute(array($idUser, $data3["id"]));
								if(!$data4 = $req4->fetch())
								{
									$verif2 = false;
								}
							}
							if($verif2)
							{
								$req3 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, secteur_id) VALUES(?, ?)");
								$req3->execute(array($idUser, $data2["secteur_id"]));
							}
						}
					}
				}
				
				$req = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
				$req->execute(array($idSousDomaine));
				while($data = $req->fetch())
				{
					$req2 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, projet_id) VALUES(?, ?)");
					$req2->execute(array($idUser, $data["id"]));
				}*/
			}
			elseif($idProjet != null)
			{
				$req = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, projet_id) VALUES(?, ?)");
				$reponse = $req->execute(array($idUser, $idProjet));
				
				$req = $bdd->prepare("SELECT sous_domaine_id FROM projet WHERE id = ?");
				$req->execute(array($idProjet));
				if($data = $req->fetch())
				{
					if($data["sous_domaine_id"] != null)
					{
						//return json_encode("NULL");
						$aboTousProjets = true;
						$req2 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
						$req2->execute(array($data["sous_domaine_id"]));
						while($data2 = $req2->fetch())
						{
							$req3 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
							$req3->execute(array($idUser, $data2["id"]));
							if(!$data3 = $req3->fetch())
							{
								$aboTousProjets = false;
							}
						}
						
						if($aboTousProjets)
						{
							$req2 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
							$req2->execute(array($data["sous_domaine_id"]));
							
							/*$req2 = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
							$req2->execute(array($idProjet));*/
							if($data2 = $req2->fetch())
							{
								$req3 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
								$req3->execute(array($data2["domaine_id"]));
								while($data3 = $req3->fetch())
								{
									$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE projet_id = ? AND utilisateur_id = ?");
									$req4->execute(array($data3["projet_id"], $idUser));
									if(!$data4 = $req4->fetch())
									{
										$aboTousProjets = false;
									}
								}
							}
							
							if($aboTousProjets)
							{
								$req2 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
								$req2->execute(array($data["sous_domaine_id"]));
								while($data2 = $req2->fetch())
								{
									$req3 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
									$req3->execute(array($idUser, $data2["id"]));
								}
								$req2 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, sous_domaine_id) VALUES(?, ?)");
								$req2->execute(array($idUser, $data["sous_domaine_id"]));
								
								$aboTousSd = true;
								$req2 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
								$req2->execute(array($data["sous_domaine_id"]));
								if($data2 = $req2->fetch())
								{
									$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
									$req3->execute(array($data2["domaine_id"]));
									while($data3 = $req3->fetch())
									{
										$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
										$req4->execute(array($idUser, $data3["id"]));
										if(!$data4 = $req4->fetch())
										{
											$aboTousSd = false;
										}
									}
									if($aboTousSd)
									{
										$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
										$req3->execute(array($data2["domaine_id"]));
										while($data3 = $req3->fetch())
										{
											$req4 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
											$req4->execute(array($idUser, $data3["id"]));
										}
										$req3 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
										$req3->execute(array($data2["domaine_id"]));
										while($data3 = $req3->fetch())
										{
											$req4 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
											$req4->execute(array($idUser, $data3["projet_id"]));
										}
										$req3 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, domaine_id) VALUES(?, ?)");
										$req3->execute(array($idUser, $data2["domaine_id"]));
										
										$aboTousDomaines = true;
										$req3 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
										$req3->execute(array($data2["domaine_id"]));
										if($data3 = $req3->fetch())
										{
											$req4 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
											$req4->execute(array($data3["secteur_id"]));
											while($data4 = $req4->fetch())
											{
												$req5 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
												$req5->execute(array($idUser, $data4["id"]));
												if(!$data5 = $req5->fetch())
												{
													$aboTousDomaines = false;
												}
											}
											if($aboTousDomaines)
											{
												$req4 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
												$req4->execute(array($data3["secteur_id"]));
												while($data4 = $req4->fetch())
												{
													$req5 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
													$req5->execute(array($idUser, $data4["id"]));
												}
												$req4 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, secteur_id) VALUES(?, ?)");
												$req4->execute(array($idUser, $data3["secteur_id"]));
											}
										}
									}
								}
							}
						}
					}
					else{
						$req2 = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
						$req2->execute(array($idProjet));
						while($data2 = $req2->fetch())
						{
							$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
							$req3->execute(array($data2["domaine_id"]));
							while($data3 = $req3->fetch())
							{
								$aboTousProjets = true;
								$req4 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
								$req4->execute(array($data3["id"]));
								while($data4 = $req4->fetch())
								{
									$req5 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
									$req5->execute(array($idUser, $data4["id"]));
									if(!$data5 = $req5->fetch())
									{
										$aboTousProjets = false;
									}
								}
								if($aboTousProjets)
								{
									$req4 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
									$req4->execute(array($data2["domaine_id"]));
									while($data4 = $req4->fetch())
									{
										
										$req5 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
										$req5->execute(array($idUser, $data4["projet_id"]));
										if(!$data5 = $req5->fetch())
										{
											$aboTousProjets = false;
										}
									}
									if($aboTousProjets)
									{
										$req4 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
										$req4->execute(array($data2["domaine_id"]));
										while($data4 = $req4->fetch())
										{
											$req5 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
											$req5->execute(array($data4["id"]));
											while($data5 = $req5->fetch())
											{
												$req6 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
												$req6->execute(array($idUser, $data5["id"]));
											}
											$req5 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, sous_domaine_id) VALUES(?, ?)");
											$req5->execute(array($idUser, $data4["id"]));
										}
									}
								}
							}
							
							$aboTousSd = true;
							$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
							$req3->execute(array($data2["domaine_id"]));
							while($data3 = $req3->fetch())
							{
								$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
								$req4->execute(array($idUser, $data3["id"]));
								if(!$data4 = $req4->fetch())
								{
									$aboTousSd = false;
								}
							}
							if($aboTousSd)
							{
								$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
								$req3->execute(array($data2["domaine_id"]));
								while($data3 = $req3->fetch())
								{
									$req4 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
									$req4->execute(array($idUser, $data3["id"]));
								}
								$req3 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, domaine_id) VALUES(?, ?)");
								$req3->execute(array($idUser, $data2["domaine_id"]));
								
								$aboTousDomaines = true;
								$req3 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
								$req3->execute(array($data2["domaine_id"]));
								if($data3 = $req3->fetch())
								{
									$req4 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
									$req4->execute(array($data3["secteur_id"]));
									while($data4 = $req4->fetch())
									{
										$req5 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
										$req5->execute(array($idUser, $data4["id"]));
										if(!$data5 = $req5->fetch())
										{
											$aboTousDomaines = false;
										}
									}
									
									if($aboTousDomaines)
									{
										$req4 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
										$req4->execute(array($data3["secteur_id"]));
										while($data4 = $req4->fetch())
										{
											$req5 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
											$req5->execute(array($data4["id"]));
											while($data5 = $req5->fetch())
											{
												$req6 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
												$req6->execute(array($idUser, $data5["projet_id"]));
											}
											$req5 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
											$req5->execute(array($data3["secteur_id"]));
											while($data5 = $req5->fetch())
											{
												
												$req6 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
												$req6->execute(array($idUser, $data5["id"]));
											}
											$req5 = $bdd->prepare("INSERT INTO abonnement(secteur_id, utilisateur_id) VALUES(?, ?)");
											$req5->execute(array($data3["secteur_id"], $idUser));
										}
									}
								}
							}
							
						}
						/*$aboTousProjets = true;
						$req2 = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
						$req2->execute(array($idProjet));
						while($data2 = $req2->fetch())
						{
							$req3 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
							$req3->execute(array($data2["domaine_id"]));
							while($data3 = $req3->fetch())
							{
								$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
								$req4->execute(array($idUser, $data3["projet_id"]));
								if(!$data4 = $req4->fetch())
								{
									$aboTousProjets = false;
								}
							}
							
							if($aboTousProjets)
							{
								$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
								$req3->execute(array($data2["domaine_id"]));
								while($data3 = $req3->fetch())
								{
									$aboTousProjets = true;
									$req4 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine = ?");
									$req4->execute(array($data3["id"]));
									while($data4 = $req4->fetch())
									{
										$req5 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
										$req5->execute(array($idUser, $data4["id"]));
										if(!$data5 = $req5->fetch())
										{
											$aboTousProjets = false;
										}
									}
									if($aboTousProjets)
									{
										$req4 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine = ?");
										$req4->execute(array($data3["id"]));
										while($data4 = $req4->fetch())
										{
											$req5 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
											$req5->execute(array($idUser, $data4["id"]));
										}
										$req4 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, sous_domaine_id) VALUES(?, ?)");
										$req4->execute(array($idUser, $data3["id"]));
									}
								}
								if($aboTousProjets)
								{
									$aboTousSd = true;
									$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
									$req3->execute(array($data2["domaine_id"]));
									while($data3 = $req3->fetch())
									{
										$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
										$req4->execute(array($idUser, $data3["id"]));
										if(!$data4 = $req4->fetch())
										{
											$aboTousSd = false;
										}
									}
									if($aboTousSd)
									{
										$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
										$req3->execute(array($data2["domaine_id"]));
										while($data3 = $req3->fetch())
										{
											$req4 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
											$req4->execute(array($idUser, $data3["id"]));
										}
										$req3 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
										$req3->execute(array($data2["domaine_id"]));
										while($data3 = $req3->fetch())
										{
											$req4 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
											$req4->execute(array($idUser, $data3["projet_id"]));
										}
										
										$req4 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, domaine_id) VALUES(?, ?)");
										$req4->execute(array($idUser, $data2["domaine_id"]));
										
										$aboTousDomaines = true;
										$req4 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
										$req4->execute(array($data2["domaine_id"]));
										if($data4 = $req4->fetch())
										{
											$req5 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
											$req5->execute(array($data4["secteur_id"]));
											while($data5 = $req5->fetch())
											{
												$req6 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
												$req6->execute(array($idUser, $data5["id"]));
												if(!$data6 = $req6->fetch())
												{
													$aboTousDomaines = false;
												}
											}
											if($aboTousDomaines)
											{
												$req5 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
												$req5->execute(array($data4["secteur_id"]));
												while($data5 = $req5->fetch())
												{
													$req6 = $bdd->prepare("DELETE FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
													$req6->execute(array($idUser, $data5["id"]));
												}
												$req5 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, secteur_id) VALUES(?, ?)");
												$req5->execute(array($idUser, $data4["secteur_id"]));
											}
										}
									}
								}
							}
						}*/
					}
				}
				
				/*$req = $bdd->prepare("SELECT sous_domaine_id FROM projet WHERE id = ?");
				$req->execute(array($idProjet));
				if($data = $req->fetch())
				{
					$verif = true;
					$req2 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
					$req2->execute(array($data["sous_domaine_id"]));
					while($data2 = $req2->fetch())
					{
						if($idProjet != $data2["id"])
						{
							$req3 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND projet_id = ?");
							$req3->execute(array($idUser, $data2["id"]));
							if(!$data3 = $req3->fetch())
							{
								$verif = false;
							}
						}
					}
					if($verif)
					{
						$req2 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, sous_domaine_id) VALUES(?, ?)");
						$req2->execute(array($idUser, $data["sous_domaine_id"]));
						
						$req2 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
						$req2->execute(array($data["sous_domaine_id"]));
						if($data2 = $req2->fetch())
						{
							$verif2 = true;
							$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
							$req3->execute(array($data2["domaine_id"]));
							while($data3 = $req3->fetch())
							{
								$req4 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND sous_domaine_id = ?");
								$req4->execute(array($idUser, $data3["id"]));
								if(!$data4 = $req4->fetch())
								{
									$verif2 = false;
								}
							}
							if($verif2)
							{
								$req3 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, domaine_id) VALUES(?, ?)");
								$req3->execute(array($idUser, $data2["domaine_id"]));
								
								$req3 = $bdd->prepare("SELECT secteur_id FROM domaine WHERE id = ?");
								$req3->execute(array($data2["domaine_id"]));
								if($data3 = $req3->fetch())
								{
									$verif3 = true;
									$req4 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
									$req4->execute(array($data3["secteur_id"]));
									while($data4 = $req4->fetch())
									{
										$req5 = $bdd->prepare("SELECT id FROM abonnement WHERE utilisateur_id = ? AND domaine_id = ?");
										$req5->execute(array($idUser, $data4["id"]));
										if(!$data5 = $req5->fetch())
										{
											$verif3 = false;
										}
									}
									if($verif3)
									{
										$req4 = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, secteur_id) VALUES(?, ?)");
										$req4->execute(array($idUser, $data3["secteur_id"]));
									}
								}
							}
						}
					}
				}*/
			}
			elseif($idContrat != null)
			{
				$req = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, contrat_id) VALUES(?, ?)");
				$reponse = $req->execute(array($idUser, $idContrat));
			}
			/*$req = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, secteur_id, domaine_id, sous_domaine_id, projet_id, contrat_id) VALUES(?, ?, ?, ?, ?, ?)");
			$reponse = $req->execute(array($idUser, $idSecteur, $idDomaine, $idSousDomaine, $idProjet, $idContrat));*/
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function getAbonnementsByUtilisateurId($id)
	{
		include("connexionBdd.php");
		
		$listeProjetsGeneriquesAbo = array(); //Pour ne pas avoir 2 fois le même projet générique
		$abonnements = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id, secteur_id, domaine_id, sous_domaine_id, projet_id, contrat_id FROM abonnement WHERE utilisateur_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			if($data["secteur_id"] != null)
			{
				$abonnements[$i]["secteur_id"] = $data["secteur_id"];
				$i++;
				
				//$tabDomaine = array(); //Pour la requête des projets génériques
				
				$req2 = $bdd->prepare("SELECT id FROM domaine WHERE secteur_id = ?");
				$req2->execute(array($data["secteur_id"]));
				while($data2 = $req2->fetch())
				{
					$abonnements[$i]["domaine_id"] = $data2["id"];
					$i++;
					
					//array_push($tabDomaine, "'".$data2["id"]."'");
					
					$req3 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
					$req3->execute(array($data2["id"]));
					while($data3 = $req3->fetch())
					{
						$abonnements[$i]["sous_domaine_id"] = $data3["id"];
						$i++;
						
						$req4 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
						$req4->execute(array($data3["id"]));
						while($data4 = $req4->fetch())
						{
							$abonnements[$i]["projet_id"] = $data4["id"];
							$i++;
						}
						
						/*$req4 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
						$req4->execute(array($data2["id"]));
						while($data4 = $req4->fetch())
						{
							$abonnements[$i]["projet_id"] = $data4["projet_id"];
							$i++;
						}*/
					}
				}
				
				//$tabDomaine = implode(", ", $tabDomaine);
				
				$req2 = $bdd->prepare("SELECT DISTINCT prdm.projet_id FROM projet_domaine prdm JOIN domaine d ON d.id = prdm.domaine_id WHERE d.secteur_id = ?");
				//$req2 = $bdd->query("SELECT DISTINCT projet_id FROM projet_domaine WHERE domaine_id IN(".$tabDomaine.")");
				$req2->execute(array($data["secteur_id"]));
				while($data2 = $req2->fetch())
				{
					if(!array_search($data2["projet_id"], $listeProjetsGeneriquesAbo))
					{
						$abonnements[$i]["projet_id"] = $data2["projet_id"];
						$i++;
					}
				}
			}
			elseif($data["domaine_id"] != null)
			{
				$abonnements[$i]["domaine_id"] = $data["domaine_id"];
				$i++;
				
				$req2 = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
				$req2->execute(array($data["domaine_id"]));
				while($data2 = $req2->fetch())
				{
					$abonnements[$i]["sous_domaine_id"] = $data2["id"];
					$i++;
					
					$req3 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
					$req3->execute(array($data2["id"]));
					while($data3 = $req3->fetch())
					{
						$abonnements[$i]["projet_id"] = $data3["id"];
						$i++;
					}
				}
				
				$req3 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
				$req3->execute(array($data["domaine_id"]));
				while($data3 = $req3->fetch())
				{
					if(!array_search($data3["projet_id"], $listeProjetsGeneriquesAbo))
					{
						$abonnements[$i]["projet_id"] = $data3["projet_id"];
						$i++;
					}
				}
			}
			elseif($data["sous_domaine_id"] != null)
			{
				$abonnements[$i]["sous_domaine_id"] = $data["sous_domaine_id"];
				$i++;
				
				$req2 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
				$req2->execute(array($data["sous_domaine_id"]));
				while($data2 = $req2->fetch())
				{
					$abonnements[$i]["projet_id"] = $data2["id"];
					$i++;
				}
				
				$req2 = $bdd->prepare("SELECT domaine_id FROM sous_domaine WHERE id = ?");
				$req2->execute(array($data["sous_domaine_id"]));
				if($data2 = $req2->fetch())
				{
					$req3 = $bdd->prepare("SELECT projet_id FROM projet_domaine WHERE domaine_id = ?");
					$req3->execute(array($data2["domaine_id"]));
					while($data3 = $req3->fetch())
					{
						if(!array_search($data3["projet_id"], $listeProjetsGeneriquesAbo))
						{
							$abonnements[$i]["projet_id"] = $data3["projet_id"];
							$i++;
						}
					}
				}
			}
			elseif($data["projet_id"] != null)
			{
				$abonnements[$i]["projet_id"] = $data["projet_id"];
				$i++;
			}
			elseif($data["contrat_id"] != null)
			{
				$abonnements[$i]["contrat_id"] = $data["contrat_id"];
				$i++;
			}
			
			/*$abonnements[$i]["id"] = $data["id"];
			$abonnements[$i]["secteur_id"] = $data["secteur_id"];
			$abonnements[$i]["domaine_id"] = $data["domaine_id"];
			$abonnements[$i]["sous_domaine_id"] = $data["sous_domaine_id"];
			$abonnements[$i]["projet_id"] = $data["projet_id"];
			$abonnements[$i]["contrat_id"] = $data["contrat_id"];
			
			$i++;*/
		}
		
		return json_encode($abonnements);
	}

	function getContrats()
	{
		include("connexionBdd.php");
		$contrats = null;
		$i = 0;
		$req = $bdd->query("SELECT id FROM contrat ORDER BY libelle");
		while($data = $req->fetch())
		{
			$contrats[$i] = json_decode(getContratById($data["id"]));
			$i++;
		}
		return json_encode($contrats);
	}
	

	function getSecteursDomainesSousDomainesProjets($idUser)
	{
		include("connexionBdd.php");
		
		$abonnements = json_decode(getAbonnementsByUtilisateurId($idUser));
		$secteursAbo = array(); 
		$domainesAbo = array();
		$sousDomainesAbo = array();
		$projetsAbo = array();
		if($abonnements != null)
		{
			foreach($abonnements as $abo)
			{
				if(isset($abo->secteur_id) && ($abo->secteur_id != null))
				{
					array_push($secteursAbo, $abo->secteur_id);
				}
				if(isset($abo->domaine_id) && ($abo->domaine_id != null))
				{
					$domaine = (object)[];
					$domaine->id = $abo->domaine_id;
					$domaine->secteur_id = json_decode(getSecteurIdByDomaineId($domaine->id));
					array_push($domainesAbo, $domaine);
				}
				if(isset($abo->sous_domaine_id) && ($abo->sous_domaine_id != null))
				{
					$sousDomaine = (object)[];
					$sousDomaine->id = $abo->sous_domaine_id;
					$sousDomaine->domaine_id = json_decode(getDomaineIdBySousDomaineId($sousDomaine->id));
					$sousDomaine->secteur_id = json_decode(getSecteurIdBySousDomaineId($sousDomaine->id));
					array_push($sousDomainesAbo, $sousDomaine);
				}
				if(isset($abo->projet_id) && ($abo->projet_id != null))
				{
					//$projet = (object)[];
					//$projet->id = $abo->projet_id;
					//$projet->sous_domaine_id = json_decode(getSousDomaineIdByProjetId($projet->id));
					//$projet->domaine_id = json_decode(getDomaineIdByProjetId($projet->id));
					//$projet->secteur_id = json_decode(getSecteurIdByProjetId($projet->id));
					
					$req = $bdd->prepare("SELECT * FROM projet WHERE id = ?");
					$req->execute(array($abo->projet_id));
					if($data = $req->fetch())
					{
						if($data["sous_domaine_id"] == null)
						{
							$projet = (object)[];
							$projet->id = $data["id"];
							$projet->sous_domaine_id = json_decode(getSousDomaineIdByProjetId($data["id"]));
							$projet->domaine_id = json_decode(getDomaineIdByProjetId($data["id"]));
							$projet->secteur_id = json_decode(getSecteurIdByProjetId($data["id"]));
							array_push($projetsAbo, $projet);
						}
						else{
							$projet = (object)[];
							$projet->id = $data["id"];
							$projet->sous_domaine_id = null;
							//$projet->domaine_id = json_decode(getDomaineIdByProjetId($data["id"]));
							$projet->domaine_id = array();
							$projet->secteur_id = json_decode(getSecteurIdByProjetId($data["id"]));
							
							$req2 = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
							$req2->execute(array($abo->projet_id));
							while($data2 = $req2->fetch())
							{
								array_push($projet->domaine_id, $data2["domaine_id"]);
							}
							array_push($projetsAbo, $projet);
						}
					}
					//array_push($projetsAbo, $projet);
				}
			}
		}
		
		$tab = array();
		$req = $bdd->query("SELECT * FROM secteur");
		while($data = $req->fetch())
		{
			$nbDomainesSecteur = 0;
			$nbDomainesSecteurAbo = 0;
			$nbSousDomainesSecteur = 0;
			$nbSousDomainesSecteurAbo = 0;
			$nbProjetsSecteur = 0;
			$listeIdProjetsSecteurTotal = array();
			$nbProjetsSecteurAbo = 0;
			$listeIdProjetsSecteur = array();
			
			$secteur = (object)[];
			$secteur->id = $data["id"];
			$secteur->libelle = $data["libelle"];
			$secteur->domaine = array();
			
			$req2 = $bdd->prepare("SELECT id, libelle, description FROM domaine WHERE secteur_id = ?");
			$req2->execute(array($data["id"]));
			while($data2 = $req2->fetch())
			{
				$nbSousDomainesDomaine = 0;
				$nbSousDomainesDomaineAbo = 0;
				$nbProjetsDomaine = 0;
				$listeIdProjetsDomaineTotal = array();
				$nbProjetsDomaineAbo = 0;
				$listeIdProjetsDomaine = array();
				
				$nbDomainesSecteur++;
				
				if(sizeof($domainesAbo) > 0)
				{
					foreach($domainesAbo as $domAbo)
					{
						if($domAbo->id == $data2["id"])
						{
							$nbDomainesSecteurAbo++;
						}
					}
				}
				
				$domaine = (object)[];
				$domaine->id = $data2["id"];
				$domaine->libelle = $data2["libelle"];
				$domaine->description = $data2["description"];
				$domaine->sous_domaine = array();
				
				$req3 = $bdd->prepare("SELECT id, libelle, description FROM sous_domaine WHERE domaine_id = ?");
				$req3->execute(array($data2["id"]));
				while($data3 = $req3->fetch())
				{
					$nbProjetsSousDomaine = 0;
					$nbProjetsSousDomaineAbo = 0;
					
					$nbSousDomainesSecteur++;
					$nbSousDomainesDomaine++;
					
					if(sizeof($sousDomainesAbo) > 0)
					{
						foreach($sousDomainesAbo as $sdAbo)
						{
							if($sdAbo->id == $data3["id"])
							{
								$nbSousDomainesDomaineAbo++;
								$nbSousDomainesSecteurAbo++;
							}
						}
					}
					
					$sous_domaine = (object)[];
					$sous_domaine->id = $data3["id"];
					$sous_domaine->libelle = $data3["libelle"];
					$sous_domaine->description = $data3["description"];
					$sous_domaine->projet = array();
					
					$req4 = $bdd->prepare("SELECT id, titre, description, date_creation, date_derniere_maj FROM projet WHERE sous_domaine_id = ?");
					$req4->execute(array($data3["id"]));
					while($data4 = $req4->fetch())
					{
						if(!in_array($data4["id"], $listeIdProjetsSecteurTotal))
						{
							$nbProjetsSecteur++;
							array_push($listeIdProjetsSecteurTotal, $data4["id"]);
						}
						if(!in_array($data4["id"], $listeIdProjetsDomaineTotal))
						{
							$nbProjetsDomaine++;
							array_push($listeIdProjetsDomaineTotal, $data4["id"]);
						}
						$nbProjetsSousDomaine++;
						
						if($projetsAbo != null)
						{
							foreach($projetsAbo as $proAbo)
							{
								if($proAbo->id == $data4["id"])
								{
									if(!in_array($proAbo->id, $listeIdProjetsSecteur))
									{
										$nbProjetsSecteurAbo++;
										array_push($listeIdProjetsSecteur, $proAbo->id);
									}
									if(!in_array($proAbo->id, $listeIdProjetsDomaine))
									{
										$nbProjetsDomaineAbo++;
										array_push($listeIdProjetsDomaine, $proAbo->id);
									}
									$nbProjetsSousDomaineAbo++;
								}
							}
						}
						
						$projet = (object)[];
						$projet->id = $data4["id"];
						$projet->titre = $data4["titre"];
						$projet->description = $data4["description"];
						$projet->type = "specifique";
						$projet->date_creation = json_decode(modifierDate($data4["date_creation"]));
						$projet->date_derniere_maj = json_decode(modifierDate($data4["date_derniere_maj"]));
						
						array_push($sous_domaine->projet, $projet);
					}
					
					$req4 = $bdd->prepare("SELECT DISTINCT prdm.projet_id pid FROM projet_domaine prdm JOIN sous_domaine sd ON sd.domaine_id = prdm.domaine_id WHERE sd.id = ?");
					$req4->execute(array($data3["id"]));
					while($data4 = $req4->fetch())
					{
						if(!in_array($data4["pid"], $listeIdProjetsSecteurTotal))
						{
							$nbProjetsSecteur++;
							array_push($listeIdProjetsSecteurTotal, $data4["pid"]);
						}
						if(!in_array($data4["pid"], $listeIdProjetsDomaineTotal))
						{
							$nbProjetsDomaine++;
							array_push($listeIdProjetsDomaineTotal, $data4["pid"]);
						}
						$nbProjetsSousDomaine++;
						
						if($projetsAbo != null)
						{
							foreach($projetsAbo as $proAbo)
							{
								if($proAbo->id == $data4["pid"])
								{
									if(!in_array($proAbo->id, $listeIdProjetsSecteur))
									{
										$nbProjetsSecteurAbo++;
										array_push($listeIdProjetsSecteur, $proAbo->id);
									}
									if(!in_array($proAbo->id, $listeIdProjetsDomaine))
									{
										$nbProjetsDomaineAbo++;
										array_push($listeIdProjetsDomaine, $proAbo->id);
									}
									$nbProjetsSousDomaineAbo++;
								}
							}
						}
						
						$req5 = $bdd->prepare("SELECT id, titre, description, date_creation, date_derniere_maj FROM projet WHERE id = ?");
						$req5->execute(array($data4["pid"]));
						if($data5 = $req5->fetch())
						{
							$projet = (object)[];
							$projet->id = $data5["id"];
							$projet->titre = $data5["titre"];
							$projet->type = "generique";
							$projet->description = $data5["description"];
							$projet->date_creation = json_decode(modifierDate($data5["date_creation"]));
							$projet->date_derniere_maj = json_decode(modifierDate($data5["date_derniere_maj"]));
							
							array_push($sous_domaine->projet, $projet);
						}
					}
					
					/*$req4 = $bdd->prepare("SELECT p.id pid, p.titre ptitre, p.description pdesc, p.date_creation pdatecrea, p.date_derniere_maj pdatemaj FROM projet p JOIN projet_domaine prdm ON p.id = prdm.projet_id WHERE prdm.domaine_id = ?");
					$req4->execute(array($data2["id"]));
					while($data4 = $req4->fetch())
					{
						//$nbProjetsSecteur++;
						//$nbProjetsDomaine++;
						//$nbProjetsSousDomaine++;
						$abonner = false;
						
						if($projetsAbo != null)
						{
							foreach($projetsAbo as $proAbo)
							{
								if($proAbo->id == $data4["pid"])
								{
									$abonner = true;
								}
							}
						}
						if($abonner)
						{
							$nbProjetsSecteurAbo++;
							$nbProjetsDomaineAbo++;
							$nbProjetsSousDomaineAbo++;
						}
						
						$projet = (object)[];
						$projet->id = $data4["pid"];
						$projet->titre = $data4["ptitre"];
						$projet->description = $data4["pdesc"];
						$projet->date_creation = json_decode(modifierDate($data4["pdatecrea"]));
						$projet->date_derniere_maj = json_decode(modifierDate($data4["pdatemaj"]));
						
						array_push($sous_domaine->projet, $projet);
					}*/
					
					$sous_domaine->nbProjets = $nbProjetsSousDomaine;
					$sous_domaine->nbProjetsAbo = $nbProjetsSousDomaineAbo;
					
					array_push($domaine->sous_domaine, $sous_domaine);
				}
				$domaine->nbSousDomaines = $nbSousDomainesDomaine;
				$domaine->nbSousDomainesAbo = $nbSousDomainesDomaineAbo;
				$domaine->nbProjets = $nbProjetsDomaine;
				$domaine->nbProjetsAbo = $nbProjetsDomaineAbo;
				
				array_push($secteur->domaine, $domaine);
			}
			$secteur->nbDomaines = $nbDomainesSecteur;
			$secteur->nbDomainesAbo = $nbDomainesSecteurAbo;
			$secteur->nbSousDomaines = $nbSousDomainesSecteur;
			$secteur->nbSousDomainesAbo = $nbSousDomainesSecteurAbo;
			$secteur->nbProjets = $nbProjetsSecteur;
			$secteur->nbProjetsAbo = $nbProjetsSecteurAbo;
			
			array_push($tab, $secteur);
		}
		
		return json_encode($tab);
	}

	function modifierMdpByUtilisateurId($idUser, $motDePasse)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		
		$mdp = json_decode(hashage($motDePasse));
		try{
			$req3 = $bdd->prepare("SELECT email FROM utilisateur WHERE id = ?");
			$req3->execute(array($idUser));
			if($data3 = $req3->fetch())
			{
				$email = $data3["email"];
				
				$req2 = $bdd->prepare("SELECT login FROM connexion WHERE utilisateur_id = ?");
				$req2->execute(array($idUser));
				if($data2 = $req2->fetch())
				{
					$login = $data2["login"];
					$req = $bdd->prepare("UPDATE connexion SET mdp = ? WHERE utilisateur_id = ?");
					$reponse = $req->execute(array($mdp, $idUser));
					if($reponse)
					{
						$headers = "";
						//$headers .= "From: " . strip_tags($_POST['req-email']) . "\r\n";
						//$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
						//$headers .= "CC: susan@example.com\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/html; charset=utf-8\r\n";
						mail($email, "Modification de mot de passe", "Bonjour,\n\nPour accéder à votre compte, merci d'utiliser ces identifiants:\n\nLogin: ".$login."\nMot de passe: ".$motDePasse."\n\nIl est conseillé de changer rapidement ce mot de passe en cliquant sur \"Mon Compte\" (tout en haut à gauche), puis \"Informations Personnelles\", et enfin cliquer sur \"Modifier mot de passe\"\n\nCeci est un mail automatique, merci de ne pas y répondre. ", $headers);
					}
				}
			}
			
		}catch(Exception $e){
			$reponse = false;
		}
		
		return json_encode($reponse);
	}

	function verificationMdpByUtilisateurId($idUser, $mdp)
	{
		include("connexionBdd.php");
		$mdp = json_decode(hashage($mdp));
		$reponse = false;
		$req = $bdd->prepare("SELECT mdp FROM connexion WHERE utilisateur_id = ?");
		$req->execute(array($idUser));
		if($data = $req->fetch())
		{
			if($mdp == $data["mdp"])
			{
				$reponse = true;
			}
		}
		return json_encode($reponse);
	}

	function getPiecesJointesByProjetId($id)
	{
		include("connexionBdd.php");
		
		$pj = null;
		$i = 0;
		$req = $bdd->prepare("SELECT piece_jointe_id FROM projet_pj WHERE projet_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$pj[$i] = json_decode(getPieceJointeById($data["piece_jointe_id"]));
			$i++;
		}
		
		return json_encode($pj);
	}

	function getSousDomainesDomainesSecteursByProjetId($id)
	{
		include("connexionBdd.php");
		
		$parents = null;
		$req = $bdd->prepare("SELECT s.id secteur_id, s.libelle secteur_libelle, d.id domaine_id, d.libelle domaine_libelle, d.description domaine_description, sd.id sous_domaine_id, sd.libelle sous_domaine_libelle, sd.description sous_domaine_description FROM projet p JOIN sous_domaine sd ON sd.id = p.sous_domaine_id JOIN domaine d ON d.id = sd.domaine_id JOIN secteur s ON s.id = d.secteur_id WHERE p.id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$parents["sousDomaine"]["id"] = $data["sous_domaine_id"];
			$parents["sousDomaine"]["libelle"] = $data["sous_domaine_libelle"];
			$parents["sousDomaine"]["description"] = $data["sous_domaine_description"];
			
			$parents["domaine"]["id"] = $data["domaine_id"];
			$parents["domaine"]["libelle"] = $data["domaine_libelle"];
			$parents["domaine"]["description"] = $data["domaine_description"];
			
			$parents["secteur"]["id"] = $data["secteur_id"];
			$parents["secteur"]["libelle"] = $data["secteur_libelle"];
		}
		
		return json_encode($parents);
	}

	function getProjetsBySousDomaineByDomaineId($id)
	{
		include("connexionBdd.php");
		
		$sousDomaines = null;
		$i = 0;
		$j = 0;
		$req = $bdd->prepare("SELECT id, contrat_id, libelle, description FROM sous_domaine WHERE domaine_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$sousDomaines[$i]["id"] = $data["id"];
			$sousDomaines[$i]["libelle"] = $data["libelle"];
			$sousDomaines[$i]["description"] = $data["description"];
			$sousDomaines[$i]["contrat_id"] = $data["contrat_id"];
			$req2 = $bdd->prepare("SELECT id, titre, description, contenu, date_creation, date_derniere_maj FROM projet WHERE sous_domaine_id = ?");
			$req2->execute(array($data["id"]));
			while($data2 = $req2->fetch())
			{
				$sousDomaines[$i]["projets"][$j]["id"] = $data2["id"];
				$sousDomaines[$i]["projets"][$j]["titre"] = $data2["titre"];
				$sousDomaines[$i]["projets"][$j]["description"] = $data2["description"];
				$sousDomaines[$i]["projets"][$j]["contenu"] = $data2["contenu"];
				$sousDomaines[$i]["projets"][$j]["date_creation"] = json_decode(modifierDate($data2["date_creation"]));
				$sousDomaines[$i]["projets"][$j]["date_derniere_maj"] = json_decode(modifierDate($data2["date_derniere_maj"]));
				
				$j++;
			}
			$i++;
		}
		
		return json_encode($sousDomaines);
	}

	/*function getProjetsBySousDomaineByDomaineId($id)
	{
		include("connexionBdd.php");
		
		$sousDomaines = null;
		$i = 0;
		$j = 0;
		$req = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$sousDomaines[$i] = json_decode(getSousDomaineById($data["id"]));
			$req2 = $bdd->prepare("SELECT id FROM projet WHERE sous_domaine_id = ?");
			$req2->execute(array($data["id"]));
			while($data2 = $req2->fetch())
			{
				$sousDomaines[$i]->projets[$j] = json_decode(getProjetById($data2["id"]));
				$j++;
			}
			$i++;
		}
		
		return json_encode($sousDomaines);
	}*/

	function getSousDomainesByDomaineId($id)
	{
		include("connexionBdd.php");
		
		$sousDomaines = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id FROM sous_domaine WHERE domaine_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$sousDomaines[$i] = json_decode(getSousDomaineById($data["id"]));
			$i++;
		}
		return json_encode($sousDomaines);
	}

	function deleteAllAnciennesNotificationsUtilisateur($idUser)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("DELETE FROM notification_utilisateur WHERE utilisateur_id = ? AND vu = TRUE");
			$reponse = $req->execute(array($idUser));
		}catch(Exception $e){
			$reponse = false;
		}
		
		return json_encode($reponse);
	}

	function deleteNotificationUtilisateurById($id)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("DELETE FROM notification_utilisateur WHERE id = ?");
			$reponse = $req->execute(array($id));
		}catch(Exception $e){
			$reponse = false;
		}
		
		return json_encode($reponse);
	}

	function updateAllNotificationsVues($idUser)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("UPDATE notification_utilisateur SET vu = TRUE WHERE utilisateur_id = ?");
			$reponse = $req->execute(array($idUser));
		}catch(Exception $e){
			$reponse = false;
		}
		
		return json_encode($reponse);
	}

	function getNotificationsByUtilisateurId($id)
	{
		include("connexionBdd.php");
		
		$i = 0;
		$mesNotifs = null;
		
		$req = $bdd->prepare("SELECT nu.id, nu.vu, nu.notification_id FROM notification_utilisateur nu JOIN notification n ON n.id = nu.notification_id WHERE utilisateur_id = ? ORDER BY nu.vu, n.date DESC");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$mesNotifs[$i]["id"] = $data["id"];
			$mesNotifs[$i]["vu"] = $data["vu"];
			$mesNotifs[$i]["notification"] = json_decode(getNotificationById($data["notification_id"]));
			
			$i++;
		}
		return json_encode($mesNotifs);
	}
	
	function getNotificationById($id)
	{
		include("connexionBdd.php");
		
		$notif = null;
		$req = $bdd->prepare("SELECT * FROM notification WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$notif["id"] = $data["id"];
			$notif["titre"] = $data["titre"];
			$notif["description"] = $data["description"];
			$notif["lien"] = $data["lien"];
			$notif["date"] = json_decode(modifierDate($data["date"]));
		}
		
		return json_encode($notif);
	}

	function addMessageRecu($idMessage, $idUser, $idCorrespondant)
	{
		include("connexionBdd.php");
		
		try{
			$req2 = $bdd->prepare("SELECT id FROM utilisateur_messages_recus WHERE message_id = ? AND utilisateur_id = ?");
			$req2->execute(array($idMessage, $idUser));
			if($data = $req2->fetch())
			{
				$reponse = true;
			}
			else{
				$req = $bdd->prepare("INSERT INTO utilisateur_messages_recus(utilisateur_id, correspondant_id, message_id) VALUES(?, ?, ?)");
				$reponse = $req->execute(array($idUser, $idCorrespondant, $idMessage));
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function updateMessageLu($id, $idUser)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("UPDATE utilisateur_messages_recus SET lu = TRUE WHERE message_id = ? AND utilisateur_id = ?");
			$reponse = $req->execute(array($id, $idUser));
		}catch(Exception $e){
			$reponse = false;
		}
		
		return json_encode($reponse);
	}

	function addReponseMessage($idMessage, $idUser, $reponse)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("INSERT INTO message_reponse(message_id, reponse, date, utilisateur_id) VALUES(?, ?, NOW(), ?) RETURNING date");
			$req->execute(array($idMessage, $reponse, $idUser));
			if($data = $req->fetch())
			{
				$req2 = $bdd->prepare("UPDATE message SET date_derniere_reponse = ? WHERE id = ?");
				$rep = $req2->execute(array($data["date"], $idMessage));
				if($rep)
				{
					$req3 = $bdd->prepare("UPDATE utilisateur_messages_recus SET lu = FALSE WHERE message_id = ? AND utilisateur_id != ?");
					$reponse = $req3->execute(array($idMessage, $idUser));
				}
			}
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function deleteMessageEnvoyeById($id)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("DELETE FROM utilisateur_messages_envoyes WHERE id = ?");
			$data = $req->execute(array($id));
		}catch(Exception $e){
			$data = false;
		}
		
		return json_encode($data);
	}

	function deleteMessageRecuById($id)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("DELETE FROM utilisateur_messages_recus WHERE id = ?");
			$data = $req->execute(array($id));
		}catch(Exception $e){
			$data = false;
		}
		
		return json_encode($data);
	}
	
	
	function getMessageById($id)
	{
		include("connexionBdd.php");
		
		$message = null;
		$req = $bdd->prepare("SELECT * FROM message WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$message["id"] = $data["id"];
			$message["sujet"] = $data["sujet"];
			$message["message"] = $data["message"];
			$message["date"] = json_decode(modifierDate($data["date"]));
			if($data["date_derniere_reponse"] !== null)
			{
				$message["date_derniere_reponse"] = json_decode(modifierDate($data["date_derniere_reponse"]));
			}
			else{
				$message["date_derniere_reponse"] = null;
			}
			
			$req2 = $bdd->prepare("SELECT utilisateur_id envoyeur, correspondant_id receveur FROM utilisateur_messages_envoyes WHERE message_id = ?");
			$req2->execute(array($id));
			if($data2 = $req2->fetch())
			{
				$message["envoyeur"] = json_decode(getUtilisateurById($data2["envoyeur"]));
				$message["receveur"] = json_decode(getUtilisateurById($data2["receveur"]));
			}
			
			$i = 0;
			$req3 = $bdd->prepare("SELECT * FROM message_reponse WHERE message_id = ? ORDER BY date");
			$req3->execute(array($id));
			while($data3 = $req3->fetch())
			{
				$message["reponse"][$i]["id"] = $data3["id"];
				$message["reponse"][$i]["reponse"] = $data3["reponse"];
				$message["reponse"][$i]["date"] = json_decode(modifierDate($data3["date"]));
				$message["reponse"][$i]["utilisateur"] = json_decode(getUtilisateurById($data3["utilisateur_id"]));
				
				$i++;
			}
		}
		
		return json_encode($message);
	}

	function getMessagesByUtilisateurId($id)
	{
		include("connexionBdd.php");
		
		$messages = null;
		$i = 0;
		$j = 0;
		$req = $bdd->prepare("SELECT umr.id, umr.message_id, umr.utilisateur_id, umr.lu, umr.correspondant_id FROM utilisateur_messages_recus umr JOIN message m ON m.id = umr.message_id WHERE umr.utilisateur_id = ? ORDER BY umr.lu, m.date_derniere_reponse DESC");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$messages["recus"][$i]["message"] = json_decode(getMessageById($data["message_id"]));
			$messages["recus"][$i]["utilisateur"] = json_decode(getUtilisateurById($data["utilisateur_id"]));
			$messages["recus"][$i]["lu"] = $data["lu"];
			$messages["recus"][$i]["correspondant"] = json_decode(getUtilisateurById($data["correspondant_id"]));
			$messages["recus"][$i]["id"] = $data["id"];
			
			$i++;
		}
		
		$req2 = $bdd->prepare("SELECT ume.id, ume.message_id, ume.utilisateur_id, ume.correspondant_id FROM utilisateur_messages_envoyes ume JOIN message m ON m.id = ume.message_id WHERE ume.utilisateur_id = ? ORDER BY m.date DESC");
		$req2->execute(array($id));
		while($data2 = $req2->fetch())
		{
			$messages["envoyes"][$j]["id"] = $data2["id"];
			$messages["envoyes"][$j]["message"] = json_decode(getMessageById($data2["message_id"]));
			$messages["envoyes"][$j]["utilisateur"] = json_decode(getUtilisateurById($data2["utilisateur_id"]));
			$messages["envoyes"][$j]["correspondant"] = json_decode(getUtilisateurById($data2["correspondant_id"]));
			
			$j++;
		}
		
		return json_encode($messages);
	}

	function getNbNotifsNonVuesByUtilisateurId($id)
	{
		include("connexionBdd.php");
		
		$nbNotifs = 0;
		$req = $bdd->prepare("SELECT COUNT(*) nb FROM notification_utilisateur WHERE vu = 'FALSE' AND utilisateur_id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$nbNotifs = $data["nb"];
		}
		return json_encode($nbNotifs);
	}

	function getNbMessagesNonLuByUtilisateurId($id)
	{
		include("connexionBdd.php");
		
		$nbMessages = 0;
		$req = $bdd->prepare("SELECT COUNT(*) nb FROM utilisateur_messages_recus WHERE lu = 'FALSE' AND utilisateur_id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$nbMessages = $data["nb"];
		}
		
		return json_encode($nbMessages);
	}

	function addMessage($idUser, $sujet, $message, $idCorrespondant)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("INSERT INTO message(sujet, message, date, date_derniere_reponse) VALUES(?, ?, NOW(), NOW()) RETURNING id");
			$req->execute(array($sujet, $message));
			if($data = $req->fetch())
			{
				$req2 = $bdd->prepare("INSERT INTO utilisateur_messages_envoyes(utilisateur_id, correspondant_id, message_id) VALUES(?, ?, ?)");
				$req2->execute(array($idUser, $idCorrespondant, $data["id"]));
				$req3 = $bdd->prepare("INSERT INTO utilisateur_messages_recus(utilisateur_id, correspondant_id, message_id) VALUES(?, ?, ?)");
				$reponse = $req3->execute(array($idCorrespondant, $idUser, $data["id"]));
			}
		}
		catch(Exception $e)
		{
			$reponse = false;
		}
		
		return json_encode($reponse);
	}

	function getPieceJointeById($id)
	{
		include("connexionBdd.php");
		
		$pj = null;
		$req = $bdd->prepare("SELECT * FROM piece_jointe WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$pj["id"] = $data["id"];
			$pj["libelle"] = $data["libelle"];
			$pj["url"] = $data["url"];
			$pj["description"] = $data["description"];
			$pj["extension"] = strtolower( substr( strrchr($data['url'], '.'), 1));
		}
		return json_encode($pj);
	}

	function getPiecesJointesByActualiteId($id)
	{
		include("connexionBdd.php");
		
		$pj = null;
		$i = 0;
		$req = $bdd->prepare("SELECT piece_jointe_id FROM actualite_pj WHERE actualite_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$pj[$i] = json_decode(getPieceJointeById($data["piece_jointe_id"]));
			
			$i++;
		}
		
		return json_encode($pj);
	}

	function getActualiteById($id)
	{
		include("connexionBdd.php");
		
		$actualite = null;
		$req = $bdd->prepare("SELECT * FROM actualite WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$actualite["id"] = $data["id"];
			$actualite["titre"] = $data["titre"];
			$actualite["contenu"] = $data["contenu"];
			$actualite["date_creation"] = $data["date_creation"];
			$actualite["date_derniere_maj"] = $data["date_derniere_maj"];
			$actualite["secteur"] = json_decode(getSecteurById($data["secteur_id"]));
			$actualite["sous_domaine"] = json_decode(getSousDomaineById($data["sous_domaine_id"]));
			$actualite["domaine"] = json_decode(getDomaineById($data["domaine_id"]));
			$actualite["projet"] = json_decode(getProjetById($data["projet_id"]));
			$actualite["contrat"] = json_decode(getContratById($data["contrat_id"]));
			$actualite["utilisateur"] = json_decode(getUtilisateurById($data["utilisateur_id"]));
			$actualite["description"] = $data["description"];
			$actualite["image_entete"] = $data["image_entete"];
			$actualite["pieces_jointes"] = json_decode(getPiecesJointesByActualiteId($data["id"]));
		}
		
		return json_encode($actualite);
	}
	
	function getSousDomainesByDomainesBySecteurs()
	{
		include("connexionBdd.php");
		
		$secteurs = null;
		$i = 0;
		$j = 0;
		$k = 0;
		
		$req = $bdd->query("SELECT * FROM secteur");
		while($data = $req->fetch())
		{
			$secteurs[$i]["id"] = $data["id"];
			$secteurs[$i]["libelle"] = $data["libelle"];
			
			$req2 = $bdd->prepare("SELECT id, libelle FROM domaine WHERE secteur_id = ?");
			$req2->execute(array($data["id"]));
			while($data2 = $req2->fetch())
			{
				$secteurs[$i]["domaines"][$j]["id"] = $data2["id"];
				$secteurs[$i]["domaines"][$j]["libelle"] = $data2["libelle"];
				
				$req3 = $bdd->prepare("SELECT id, libelle FROM sous_domaine WHERE domaine_id = ?");
				$req3->execute(array($data2["id"]));
				while($data3 = $req3->fetch())
				{
					$secteurs[$i]["domaines"][$j]["sous_domaines"][$k]["id"] = $data3["id"];
					$secteurs[$i]["domaines"][$j]["sous_domaines"][$k]["libelle"] = $data3["libelle"];
					
					$k++;
				}
				$j++;
			}
			$i++;
		}
		
		return json_encode($secteurs);
	}

	function getMiniatureById($id)
	{
		include("connexionBdd.php");
		
		$miniature = null;
		$req = $bdd->prepare("SELECT * FROM miniature WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$miniature["id"] = $data["id"];
			$miniature["nom"] = $data["nom"];
			$miniature["url"] = $data["url"];
		}
		
		return json_encode($miniature);
	}
	
	function getContratById($id)
	{
		include("connexionBdd.php");
		
		$contrat = null;
		$req = $bdd->prepare("SELECT * FROM contrat WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$contrat["id"] = $data["id"];
			$contrat["libelle"] = $data["libelle"];
			$contrat["miniature"] = json_decode(getMiniatureById($data["miniature_id"]));
		}
		
		return json_encode($contrat);
	}

	function getProjetById($id)
	{
		include("connexionBdd.php");
		
		$projet = null;
		$req = $bdd->prepare("SELECT * FROM projet WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$projet["id"] = $data["id"];
			$projet["titre"] = $data["titre"];
			$projet["description"] = $data["description"];
			$projet["contenu"] = $data["contenu"];
			$projet["date_creation"] = $data["date_creation"];
			$projet["date_derniere_maj"] = $data["date_derniere_maj"];
			$projet["sous_domaine"] = json_decode(getSousDomaineById($data["sous_domaine_id"]));
			$projet["contrat"] = json_decode(getContratById($data["contrat_id"]));
			$projet["utilisateur"] = json_decode(getUtilisateurById($data["utilisateur_id"]));
			$projet["image_entete"] = $data["image_entete"];
			
			$i = 0;
			$req2 = $bdd->prepare("SELECT domaine_id FROM projet_domaine WHERE projet_id = ?");
			$req2->execute(array($id));
			while($data2 = $req2->fetch())
			{
				$projet["domaines"][$i] = json_decode(getDomaineById($data2["domaine_id"]));
				$i++;
			}
		}
		
		return json_encode($projet);
	}

	function getDomaineById($id)
	{
		include("connexionBdd.php");
		
		$domaine = null;
		$req = $bdd->prepare("SELECT * FROM domaine WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$domaine["id"] = $data["id"];
			$domaine["libelle"] = $data["libelle"];
			$domaine["description"] = $data["description"];
			$domaine["secteur"] = json_decode(getSecteurById($data["secteur_id"]));
		}
		
		return json_encode($domaine);
	}

	function getSousDomaineById($id)
	{
		include("connexionBdd.php");
		
		$sousDomaine = null;
		$req = $bdd->prepare("SELECT * FROM sous_domaine WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$sousDomaine["id"] = $data["id"];
			$sousDomaine["libelle"] = $data["libelle"];
			$sousDomaine["description"] = $data["description"];
			$sousDomaine["domaine"] = json_decode(getDomaineById($data["domaine_id"]));
			$sousDomaine["secteur"] = json_decode(getSecteurById(json_decode(getSecteurIdBySousDomaineId($data["id"]))));
			$sousDomaine["contrat"] = json_decode(getContratById($data["contrat_id"]));
		}
		
		return json_encode($sousDomaine);
	}

	function getSecteurById($id)
	{
		include("connexionBdd.php");
		
		$secteur = null;
		$req = $bdd->prepare("SELECT * FROM secteur WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$secteur["id"] = $data["id"];
			$secteur["libelle"] = $data["libelle"];
		}
		
		return json_encode($secteur);
	}

	function getActualitesByNum($numPremActu, $nbActus)
	{
		include("connexionBdd.php");
		
		$actus = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id, titre, date_creation, date_derniere_maj, secteur_id, sous_domaine_id, projet_id, contrat_id, utilisateur_id, domaine_id, description FROM actualite ORDER BY date_creation DESC LIMIT ? OFFSET ?");
		$req->execute(array($nbActus, $numPremActu));
		while($data = $req->fetch())
		{
			$actus[$i]["id"] = $data["id"];
			$actus[$i]["titre"] = $data["titre"];
			$actus[$i]["date_creation"] = $data["date_creation"];
			$actus[$i]["date_derniere_maj"] = $data["date_derniere_maj"];
			$actus[$i]["secteur"] = json_decode(getSecteurById($data["secteur_id"]));
			$actus[$i]["domaine"] = json_decode(getDomaineById($data["domaine_id"]));
			$actus[$i]["sous_domaine"] = json_decode(getSousDomaineById($data["sous_domaine_id"]));
			$actus[$i]["projet"] = json_decode(getProjetById($data["projet_id"]));
			$actus[$i]["utilisateur"] = json_decode(getUtilisateurById($data["utilisateur_id"]));
			$actus[$i]["contrat"] = json_decode(getContratById($data["contrat_id"]));
			$actus[$i]["description"] = $data["description"];
			
			$i++;
		}
		
		return json_encode($actus);
	}
	
	function getNiveauById($id)
	{
		include("connexionBdd.php");
		
		$niveau = null;
		$req = $bdd->prepare("SELECT * FROM niveau WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$niveau["id"] = $data["id"];
			$niveau["libelle"] = $data["libelle"];
			$niveau["niveau"] = $data["niveau"];
		}
		
		return json_encode($niveau);
	}

	function getFonctionById($id)
	{
		include("connexionBdd.php");
		
		$fonction = null;
		$req = $bdd->prepare("SELECT * FROM fonction WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$fonction["id"] = $data["id"];
			$fonction["libelle"] = $data["libelle"];
			$fonction["niveau"] = json_decode(getNiveauById($data["niveau_id"]));
		}
		
		return json_encode($fonction);
	}
	
	function getUtilisateurById($id)
	{
		include("connexionBdd.php");
		
		$user = null;
		$req = $bdd->prepare("SELECT * FROM utilisateur WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$user["id"] = $data["id"];
			$user["nom"] = $data["nom"];
			$user["prenom"] = $data["prenom"];
			$user["email"] = $data["email"];
			$user["fonction"] = json_decode(getFonctionById($data["fonction_id"]));
			$user["photo"] = $data["photo"];
			$user["actif"] = $data["actif"];
		}
		
		return json_encode($user);
	}
	
	function hashage($mot)
	{
		include("connexionBdd.php");
		
		$mot = md5($mot);
		return json_encode($mot);
	}
	
	function connexion($login, $mdp)
	{
		include("connexionBdd.php");
		include("connexionErp.php");
		
		$user = null;
		
		$req = $bddErp->prepare("SELECT id, ag_employee_id FROM res_users WHERE login = ? AND password = ?");
		$req->execute(array($login, $mdp));
		if($data = $req->fetch())
		{
			$req3 = $bddErp->prepare("SELECT e.name_related, e.work_email, e.image_medium, j.id jid, j.name jname FROM hr_employee e JOIN hr_job j ON e.job_id = j.id WHERE e.id = ?");
			$req3->execute(array($data["ag_employee_id"]));
			if($data3 = $req3->fetch())
			{
				if($data3['image_medium'] != null)
				{
					$img = "data:image/jpeg;base64, ".stream_get_contents($data3['image_medium']);
				}
				else{
					$img = null;
				}
				$email = $data3["work_email"];
				$tabNom = explode(" ", $data3["name_related"]);
				$nom = $tabNom[0];
				$tabPrenom = array();
				for($i = 1; $i < sizeof($tabNom); $i++)
				{
					array_push($tabPrenom, $tabNom[$i]);
				}
				$prenom = implode("-", $tabPrenom);
				
				$idFonction = null;
				$req2 = $bdd->prepare("SELECT id FROM fonction WHERE erp_job_id = ?");
				$req2->execute(array($data3["jid"]));
				if($data2 = $req2->fetch())
				{
					$idFonction = $data2["id"];
				}
				else{
					$req3 = $bdd->prepare("INSERT INTO fonction(libelle, niveau_id, erp_job_id) VALUES(?, ?, ?) RETURNING id");
					$req3->execute(array($data3["jname"], 1, $data3["jid"]));
					if($data3 = $req3->fetch())
					{
						$idFonction = $data3["id"];
					}
				}
				
				
				$req2 = $bdd->prepare("SELECT id, utilisateur_id FROM connexion WHERE connexion_erp_id = ?");
				$req2->execute(array($data["id"]));
				
				//Si la connexion existe dans ma bdd -> je met à jour les infos
				if($data2 = $req2->fetch())
				{
					if($img != null)
					{
						$req3 = $bdd->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, fonction_id = ?, email = ?, photo = ? WHERE id = ?");
						$req3->execute(array($nom, $prenom, $idFonction, $email, $img, $data2["id"]));
					}
					else{
						$req3 = $bdd->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, fonction_id = ?, email = ?, photo = DEFAULT WHERE id = ?");
						$req3->execute(array($nom, $prenom, $idFonction, $email, $data2["id"]));
					}
					
					$req2 = $bdd->prepare("UPDATE connexion SET login = ?, mdp = ? WHERE utilisateur_id = ?");
					$req2->execute(array($login, json_decode(hashage($mdp)), $data2["id"]));
					
					$user = json_decode(getUtilisateurById($data2["utilisateur_id"]));
					if($user->actif == false)
					{
						$req2 = $bdd->prepare("UPDATE utilisateur SET actif = TRUE WHERE id = ?");
						$req2->execute(array($user->id));
					}
				}
				//Sinon je crée un nouvel utilisateur
				else{
					if($img != null)
					{
						$req3 = $bdd->prepare("INSERT INTO utilisateur(nom, prenom, fonction_id, email, photo) VALUES(?, ?, ?, ?, ?) RETURNING id");
						$req3->execute(array($nom, $prenom, $idFonction, $email, $img));
					}
					else{
						$req3 = $bdd->prepare("INSERT INTO utilisateur(nom, prenom, fonction_id, email) VALUES(?, ?, ?, ?) RETURNING id");
						$req3->execute(array($nom, $prenom, $idFonction, $email));
					}
					if($data3 = $req3->fetch())
					{
						$req4 = $bdd->prepare("INSERT INTO connexion(login, mdp, utilisateur_id, connexion_erp_id) VALUES(?, ?, ?, ?)");
						$req4->execute(array($login, json_decode(hashage($mdp)), $data3["id"], $data["id"]));
						
						$user = json_decode(getUtilisateurById($data3["id"]));
						if($user->actif == false)
						{
							$req2 = $bdd->prepare("UPDATE utilisateur SET actif = TRUE WHERE id = ?");
							$req2->execute(array($user->id));
						}
					}
				}
			}
			
		}
		
		return json_encode($user);
		/*$user = null;
		$mdp = json_decode(hashage($mdp));
		$req = $bdd->prepare("SELECT utilisateur_id FROM connexion WHERE login = ? AND mdp = ?");
		$req->execute(array($login, $mdp));
		if($data = $req->fetch())
		{
			$user = json_decode(getUtilisateurById($data["utilisateur_id"]));
			if($user->actif == false)
			{
				$req2 = $bdd->prepare("UPDATE utilisateur SET actif = TRUE WHERE id = ?");
				$req2->execute(array($user->id));
			}
		}
		
		return json_encode($user);*/
	}
	
	function modifierDate($d) // retourne JJ/MM/AAAA + HH:mm:ss
	{
		$date["jour"] = substr($d, 8, 2)."/".substr($d, 5, 2)."/".substr($d, 0, 4);
		$date["heure"] = substr($d, 11, 8);
		
		return json_encode($date);
	}

    function getSearchProjetBySearchBar($search_text)
    {
		include("connexionBdd.php");
		$search_text = strtolower($search_text);
        $searcharray = explode(" ",$search_text);
        $countarray = count($searcharray);
        $titresearch_sql = "LOWER(titre) like ";
        $descsearch_sql = "LOWER(description) like ";
        $z = 0;
        $search = null;
        for($i = 1; $i <= $countarray; $i++)
        {
            
          
                $searcharray[$i - 1] = "%".$searcharray[$i - 1]."%";
                  
           
            
            $titresearch_sql = $titresearch_sql."'".$searcharray[$i - 1]."'";
            $descsearch_sql = $descsearch_sql."'".$searcharray[$i - 1]."'";
                    if($i != $countarray)
                    {
                        $titresearch_sql = $titresearch_sql." and LOWER(titre) like ";
                        $descsearch_sql = $descsearch_sql." and LOWER(description) like ";
                    }
            
        }
        
        $sql_return = "select id,titre,description from projet
where (".$titresearch_sql.") or (".$descsearch_sql.") limit 10";
        $req = $bdd->query($sql_return);
        while($data = $req->fetch())
        {
            $search[$z]['id'] = $data['id'];
            $search[$z]['titre'] = $data['titre'];
            $search[$z]['description'] = $data['description'];
            
            $z++;
        }
        return json_encode($search);
        
    }
	
    function getSearchProjetByProjectSearch($search_text)
    {
        include("connexionBdd.php");
        $search_text = strtolower($search_text);
        $searcharray = explode(" ",$search_text);
        $countarray = count($searcharray);
        $titresearch_sql = " LOWER(titre) like ";
        $descsearch_sql = " LOWER(description) like ";
        $contenu_sql = " LOWER(contenu) like ";
        $z = 0;
        $search = null;
        for($i = 1; $i <= $countarray; $i++)
        {
            
          
                $searcharray[$i - 1] = "%".$searcharray[$i - 1]."%";
                  
           
            
            $titresearch_sql = $titresearch_sql."'".$searcharray[$i - 1]."'";
            $descsearch_sql = $descsearch_sql."'".$searcharray[$i - 1]."'";
            $contenu_sql = $contenu_sql."'".$searcharray[$i - 1]."'";
            
                    if($i != $countarray)
                    {
                        $titresearch_sql = $titresearch_sql." and  LOWER(titre) like ";
                        $descsearch_sql = $descsearch_sql." and  LOWER(description) like ";
                        $contenu_sql = $contenu_sql." and LOWER(contenu) like";
                    }
            
        }
        
        $sql_return = "select id,titre,description from projet
where (".$titresearch_sql.") or (".$descsearch_sql.") or (".$contenu_sql.")";
        $req = $bdd->query($sql_return);
        while($data = $req->fetch())
        {
            $search[$z]['id'] = $data['id']; 
            $z++;
        }
        return json_encode($search);
        
    }
	
	function getContratByDomaineId()
	{
		include("connexionBdd.php");
		$i = 0;
		$contrat = null;
		$req = $bdd->query("select distinct domaine.id as domaine_id, contrat.id as contrat_id from domaine
		left join sous_domaine on domaine.id = sous_domaine.domaine_id
		left join contrat on sous_domaine.contrat_id = contrat.id");
		while($data = $req->fetch())
		{
			$contrat[$i]['contrat_id'] = $data['contrat_id'];
			$contrat[$i]['domaine_id'] = $data['domaine_id'];
			$i++;
		}
		return json_encode($contrat);
	}
	
	function addHabilitationElectrique($formulaire)
	{
		include("connexionBdd.php");
		/*$test = "('grz', 'rqz', 'frz', true, false, false, true, true, false, false, true, false, true, 'testtest', true, false, false, true, true, false, true, false, true, false, true, false, true, true, 1, 2, 3, 4, 5, 6, 7, false, 22, NOW())";*/
		$formulaire = json_decode($formulaire);
		
<<<<<<< HEAD
=======
		if(gettype($formulaire->q2_ans1) == "boolean")
		{
			$formulaire->q2_ans1 = boolval( $formulaire->q2_ans1) ? 'true' : 'false';
			if($formulaire->q2_ans1 != 'true')
			{
				$formulaire->q2_ans1 = "false";
			}
		}
		if(gettype($formulaire->q2_ans2) == "boolean")
		{
			$formulaire->q2_ans2 = boolval( $formulaire->q2_ans2) ? 'true' : 'false';
			if($formulaire->q2_ans2 != 'true')
			{
				$formulaire->q2_ans2 = "false";
			}
		}
		
>>>>>>> 00b6ebfca65d48959f11399a6a0ccfc2405c6b68
		if(gettype($formulaire->q7_ans1) == "boolean")
		{
			$formulaire->q7_ans1 = boolval( $formulaire->q7_ans1) ? 'true' : 'false';
			if($formulaire->q7_ans1 != 'true')
			{
				$formulaire->q7_ans1 = "false";
			}
		}
		if(gettype($formulaire->q7_ans2) == "boolean")
		{
			$formulaire->q7_ans2 = boolval( $formulaire->q7_ans2) ? 'true' : 'false';
		}
		
		if(gettype($formulaire->q8_ans1) == "boolean")
		{
			$formulaire->q8_ans1 = boolval( $formulaire->q8_ans1) ? 'true' : 'false';
		}
		if($formulaire->q8_ans2 != null)
		{
			$formulaire->q8_ans2 = boolval( $formulaire->q8_ans2) ? 'true' : 'false';
		}
		
		if(gettype($formulaire->q9_ans1) == "boolean")
		{
			$formulaire->q9_ans1 = boolval( $formulaire->q9_ans1) ? 'true' : 'false';
		}
		if(gettype($formulaire->q9_ans2) == "boolean")
		{
			$formulaire->q9_ans2 = boolval( $formulaire->q9_ans2) ? 'true' : 'false';
		}
		if(gettype($formulaire->q9_ans3) == "boolean")
		{
			$formulaire->q9_ans3 = boolval( $formulaire->q9_ans3) ? 'true' : 'false';
		}
		
		if(gettype($formulaire->q10_ans1) == "boolean")
		{
			$formulaire->q10_ans1 = boolval( $formulaire->q10_ans1) ? 'true' : 'false';
		}
		
		if(gettype($formulaire->q11_ans1) == "boolean")
		{
			$formulaire->q11_ans1 = boolval( $formulaire->q11_ans1) ? 'true' : 'false';
		}
		
		if(gettype($formulaire->q12_ans1) == "boolean")
		{
			$formulaire->q12_ans1 = boolval( $formulaire->q12_ans1) ? 'true' : 'false';
		}
		if(gettype($formulaire->q12_ans2) == "boolean")
		{
			$formulaire->q12_ans2 = boolval( $formulaire->q12_ans2) ? 'true' : 'false';
		}
		
		if(gettype($formulaire->q13_ans1) == "boolean")
		{
			$formulaire->q13_ans1 = boolval( $formulaire->q13_ans1) ? 'true' : 'false';
		}
		if(gettype($formulaire->q13_ans2) == "boolean")
		{
			$formulaire->q13_ans2 = boolval( $formulaire->q13_ans2) ? 'true' : 'false';
		}
		if(gettype($formulaire->q13_ans3) == "boolean")
		{
			$formulaire->q13_ans3 = boolval( $formulaire->q13_ans3) ? 'true' : 'false';
		}
		
		$tab = array($formulaire->q1_ans1, 
		 $formulaire->q1_ans2, 
		 $formulaire->q1_ans3, 
<<<<<<< HEAD
		(boolval($formulaire->q2_ans1) ? 'true' : 'false'), 
		(boolval( $formulaire->q2_ans2) ? 'true' : 'false'), 
=======
		$formulaire->q2_ans1, 
		$formulaire->q2_ans2, 
>>>>>>> 00b6ebfca65d48959f11399a6a0ccfc2405c6b68
		(boolval( $formulaire->q3_ans1) ? 'true' : 'false'), 
		(boolval( $formulaire->q3_ans2) ? 'true' : 'false'), 
		(boolval( $formulaire->q3_ans3) ? 'true' : 'false'), 
		(boolval( $formulaire->q4_ans1) ? 'true' : 'false'), 
		(boolval( $formulaire->q4_ans2) ? 'true' : 'false'), 
		(boolval( $formulaire->q4_ans3) ? 'true' : 'false'), 
		(boolval( $formulaire->q5_ans1) ? 'true' : 'false'), 
		(boolval( $formulaire->q5_ans2) ? 'true' : 'false'), 
		 $formulaire->q6_ans1,
		$formulaire->q7_ans1, 
		$formulaire->q7_ans2, 
		$formulaire->q8_ans1, 
		$formulaire->q8_ans2, 
		$formulaire->q9_ans1, 
		$formulaire->q9_ans2, 
		$formulaire->q9_ans3, 
		$formulaire->q10_ans1, 
		$formulaire->q11_ans1, 
		$formulaire->q12_ans1, 
		$formulaire->q12_ans2, 
		$formulaire->q13_ans1, 
		$formulaire->q13_ans2, 
		$formulaire->q13_ans3, 
		 $formulaire->q14_ans1, 
		 $formulaire->q14_ans2, 
		 $formulaire->q14_ans3, 
		 $formulaire->q14_ans4, 
		 $formulaire->q14_ans5, 
		 $formulaire->q14_ans6, 
		 $formulaire->q14_ans7, 
		(boolval( $formulaire->brouillon) ? 'true' : 'false'), 
		 $formulaire->utilisateur_id
		);

		$reponse = false;
		try{
			$req = $bdd->prepare("INSERT INTO habil_elec(q1_ans1, q1_ans2, q1_ans3, q2_ans1, q2_ans2, q3_ans1, q3_ans2, q3_ans3, q4_ans1, q4_ans2, q4_ans3, q5_ans1, q5_ans2, q6_ans1, q7_ans1, q7_ans2, q8_ans1, q8_ans2, q9_ans1, q9_ans2, q9_ans3, q10_ans1, q11_ans1, q12_ans1, q12_ans2, q13_ans1, q13_ans2, q13_ans3, q14_ans1, q14_ans2, q14_ans3, q14_ans4, q14_ans5, q14_ans6, q14_ans7, brouillon, utilisateur_id, date, date_expiration) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NULL)");
			$reponse = $req->execute($tab);
			
		}
		catch(Exception $e){
			$reponse = false;
		}
		
		return json_encode($reponse);
	}
	
	function getDernierFormulaireByUtilisateurId($user_id)
	{
		include("connexionBdd.php");
		
		$formulaire = null;
		$req = $bdd->prepare("SELECT id FROM habil_elec WHERE utilisateur_id = ? ORDER BY date DESC LIMIT 1 OFFSET 0");
		$req->execute(array($user_id));
		if($data = $req->fetch())
		{
			$formulaire = json_decode(getFormulaireHabilitationElectriqueById($data["id"]));
		}
		
		return json_encode($formulaire);
	}
	
	function getFormulaireHabilitationElectriqueById($id)
	{
		include("connexionBdd.php");
		
		$formulaire = null;
		$req = $bdd->prepare("SELECT * FROM habil_elec WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$formulaire = (object) array();
			
			$formulaire->id = $data["id"];
			$formulaire->utilisateur_id = $data["utilisateur_id"];
			$formulaire->date = $data["date"];
			$formulaire->date_expiration = $data["date_expiration"];
			$formulaire->brouillon = $data["brouillon"];
<<<<<<< HEAD
=======
			$formulaire->valider = $data["valider"];
>>>>>>> 00b6ebfca65d48959f11399a6a0ccfc2405c6b68
			
			$formulaire->q1_ans1 = $data["q1_ans1"];
			$formulaire->q1_ans2 = $data["q1_ans2"];
			$formulaire->q1_ans3 = $data["q1_ans3"];
			
			$formulaire->q2_ans1 = $data["q2_ans1"];
			$formulaire->q2_ans2 = $data["q2_ans2"];
			
			$formulaire->q3_ans1 = $data["q3_ans1"];
			$formulaire->q3_ans2 = $data["q3_ans2"];
			$formulaire->q3_ans3 = $data["q3_ans3"];
			
			$formulaire->q4_ans1 = $data["q4_ans1"];
			$formulaire->q4_ans2 = $data["q4_ans2"];
			$formulaire->q4_ans3 = $data["q4_ans3"];
			
			$formulaire->q5_ans1 = $data["q5_ans1"];
			$formulaire->q5_ans2 = $data["q5_ans2"];
			
			$formulaire->q6_ans1 = $data["q6_ans1"];
			
			$formulaire->q7_ans1 = $data["q7_ans1"];
			$formulaire->q7_ans2 = $data["q7_ans2"];
			
			$formulaire->q8_ans1 = $data["q8_ans1"];
			$formulaire->q8_ans2 = $data["q8_ans2"];
			
			$formulaire->q9_ans1 = $data["q9_ans1"];
			$formulaire->q9_ans2 = $data["q9_ans2"];
			$formulaire->q9_ans3 = $data["q9_ans3"];
			
			$formulaire->q10_ans1 = $data["q10_ans1"];
			
			$formulaire->q11_ans1 = $data["q11_ans1"];
			
			$formulaire->q12_ans1 = $data["q12_ans1"];
			$formulaire->q12_ans2 = $data["q12_ans2"];
			
			$formulaire->q13_ans1 = $data["q13_ans1"];
			$formulaire->q13_ans2 = $data["q13_ans2"];
			$formulaire->q13_ans3 = $data["q13_ans3"];
			
			$formulaire->q14_ans1 = $data["q14_ans1"];
			$formulaire->q14_ans2 = $data["q14_ans2"];
			$formulaire->q14_ans3 = $data["q14_ans3"];
			$formulaire->q14_ans4 = $data["q14_ans4"];
			$formulaire->q14_ans5 = $data["q14_ans5"];
			$formulaire->q14_ans6 = $data["q14_ans6"];
			$formulaire->q14_ans7 = $data["q14_ans7"];
		}
		
		return json_encode($formulaire);
	}
	
	function removeHabilitationElectrique($idFormulaire)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("DELETE FROM habil_elec WHERE id = ?");
			$reponse = $req->execute(array($idFormulaire));
		}catch(Exception $e){
			$reponse = false;
		}
		
		return json_encode($reponse);
	}
	
	function getFichesHabilitationsElectriquesAValider()
	{
		include("connexionBdd.php");
		
		$listesFiches = array();
		
<<<<<<< HEAD
		$req = $bdd->query("SELECT id FROM habil_elec WHERE brouillon = FALSE AND date_expiration IS NULL ORDER BY date");
=======
		$req = $bdd->query("SELECT id FROM habil_elec WHERE brouillon = FALSE AND date_expiration IS NULL AND valider IS NULL ORDER BY date DESC");
		while($data = $req->fetch())
		{
			$formulaire = json_decode(getFormulaireHabilitationElectriqueById($data["id"]));
			array_push($listesFiches, $formulaire);
		}
		
		return json_encode($listesFiches);
	}
	
	function getFichesHabilitationsElectriquesValidees()
	{
		include("connexionBdd.php");
		
		$listesFiches = array();
		
		$req = $bdd->query("SELECT id FROM habil_elec WHERE brouillon = FALSE AND date_expiration IS NOT NULL AND valider = TRUE ORDER BY date DESC");
>>>>>>> 00b6ebfca65d48959f11399a6a0ccfc2405c6b68
		while($data = $req->fetch())
		{
			$formulaire = json_decode(getFormulaireHabilitationElectriqueById($data["id"]));
			array_push($listesFiches, $formulaire);
		}
		
		return json_encode($listesFiches);
	}
<<<<<<< HEAD
=======
	
	function getFichesHabilitationsElectriquesRefusees()
	{
		include("connexionBdd.php");
		
		$listesFiches = array();
		
		$req = $bdd->query("SELECT id FROM habil_elec WHERE brouillon = FALSE AND valider = FALSE ORDER BY date DESC");
		while($data = $req->fetch())
		{
			$formulaire = json_decode(getFormulaireHabilitationElectriqueById($data["id"]));
			array_push($listesFiches, $formulaire);
		}
		
		return json_encode($listesFiches);
	}
	
	function validerHabilitationElectrique($idFormulaire)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("UPDATE habil_elec SET date_expiration = DEFAULT, valider = TRUE WHERE id = ?");
			$reponse = $req->execute(array($idFormulaire));
		}
		catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}
	
	function refuserHabilitationElectrique($idFormulaire)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		try{
			$req = $bdd->prepare("UPDATE habil_elec SET date_expiration = NULL, valider = FALSE WHERE id = ?");
			$reponse = $req->execute(array($idFormulaire));
		}
		catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}
>>>>>>> 00b6ebfca65d48959f11399a6a0ccfc2405c6b68
?>
