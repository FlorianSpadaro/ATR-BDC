<?php
	function removeAbonnementById($id)
	{
		include("connexionBdd.php");
		try{
			$req = $bdd->prepare("DELETE FROM abonnement WHERE id = ?");
			$reponse = $req->execute(array($id));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function addAbonnement($idUser, $idSecteur, $idDomaine, $idSousDomaine, $idProjet, $idContrat)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("INSERT INTO abonnement(utilisateur_id, secteur_id, domaine_id, sous_domaine_id, projet_id, contrat_id) VALUES(?, ?, ?, ?, ?, ?)");
			$reponse = $req->execute(array($idUser, $idSecteur, $idDomaine, $idSousDomaine, $idProjet, $idContrat));
		}catch(Exception $e){
			$reponse = false;
		}
		return json_encode($reponse);
	}

	function getAbonnementsByUtilisateurId($id)
	{
		include("connexionBdd.php");
		
		$abonnements = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id, secteur_id, domaine_id, sous_domaine_id, projet_id, contrat_id FROM abonnement WHERE utilisateur_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$abonnements[$i]["id"] = $data["id"];
			$abonnements[$i]["secteur_id"] = $data["secteur_id"];
			$abonnements[$i]["domaine_id"] = $data["domaine_id"];
			$abonnements[$i]["sous_domaine_id"] = $data["sous_domaine_id"];
			$abonnements[$i]["projet_id"] = $data["projet_id"];
			$abonnements[$i]["contrat_id"] = $data["contrat_id"];
			
			$i++;
		}
		
		return json_encode($abonnements);
	}

	function getContrats()
	{
		include("connexionBdd.php");
		$contrats = null;
		$i = 0;
		$req = $bdd->query("SELECT id FROM contrat");
		while($data = $req->fetch())
		{
			$contrats[$i] = json_decode(getContratById($data["id"]));
			$i++;
		}
		return json_encode($contrats);
	}

	function getSecteursDomainesSousDomainesProjets()
	{
		include("connexionBdd.php");
		
		$tab = array();
		$req = $bdd->query("SELECT * FROM secteur");
		while($data = $req->fetch())
		{
			$nbDomainesSecteur = 0;
			$nbSousDomainesSecteur = 0;
			$nbProjetsSecteur = 0;
			
			$secteur = (object)[];
			$secteur->id = $data["id"];
			$secteur->libelle = $data["libelle"];
			$secteur->domaine = array();
			
			$req2 = $bdd->prepare("SELECT id, libelle, description FROM domaine WHERE secteur_id = ?");
			$req2->execute(array($data["id"]));
			while($data2 = $req2->fetch())
			{
				$nbSousDomainesDomaine = 0;
				$nbProjetsDomaine = 0;
				
				$nbDomainesSecteur++;
				
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
					
					$nbSousDomainesSecteur++;
					$nbSousDomainesDomaine++;
					
					$sous_domaine = (object)[];
					$sous_domaine->id = $data3["id"];
					$sous_domaine->libelle = $data3["libelle"];
					$sous_domaine->description = $data3["description"];
					$sous_domaine->projet = array();
					
					$req4 = $bdd->prepare("SELECT id, titre, description, date_creation, date_derniere_maj FROM projet WHERE sous_domaine_id = ?");
					$req4->execute(array($data3["id"]));
					while($data4 = $req4->fetch())
					{
						$nbProjetsSecteur++;
						$nbProjetsDomaine++;
						$nbProjetsSousDomaine++;
						
						$projet = (object)[];
						$projet->id = $data4["id"];
						$projet->titre = $data4["titre"];
						$projet->description = $data4["description"];
						$projet->date_creation = json_decode(modifierDate($data4["date_creation"]));
						$projet->date_derniere_maj = json_decode(modifierDate($data4["date_derniere_maj"]));
						
						array_push($sous_domaine->projet, $projet);
					}
					$sous_domaine->nbProjets = $nbProjetsSousDomaine;
					
					array_push($domaine->sous_domaine, $sous_domaine);
				}
				$domaine->nbSousDomaines = $nbSousDomainesDomaine;
				$domaine->nbProjets = $nbProjetsDomaine;
				
				array_push($secteur->domaine, $domaine);
			}
			$secteur->nbDomaines = $nbDomainesSecteur;
			$secteur->nbSousDomaines = $nbSousDomainesSecteur;
			$secteur->nbProjets = $nbProjetsSecteur;
			
			array_push($tab, $secteur);
		}
		
		return json_encode($tab);
	}

	function modifierMdpByUtilisateurId($idUser, $mdp)
	{
		include("connexionBdd.php");
		
		$mdp = json_decode(hashage($mdp));
		try{
			$req = $bdd->prepare("UPDATE connexion SET mdp = ? WHERE utilisateur_id = ?");
			$reponse = $req->execute(array($mdp, $idUser));
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
		$req = $bdd->prepare("SELECT id, libelle, description FROM sous_domaine WHERE domaine_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$sousDomaines[$i]["id"] = $data["id"];
			$sousDomaines[$i]["libelle"] = $data["libelle"];
			$sousDomaines[$i]["description"] = $data["description"];
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
			$contrat["utilisateur"] = json_decode(getUtilisateurById($data["utilisateur_id"]));
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
			$domaine["utilisateur"] = json_decode(getUtilisateurById($data["utilisateur_id"]));
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
			$sousDomaine["utilisateur"] = json_decode(getUtilisateurById($data["utilisateur_id"]));
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
		
		$user = null;
		$mdp = json_decode(hashage($mdp));
		$req = $bdd->prepare("SELECT utilisateur_id FROM connexion WHERE login = ? AND mdp = ?");
		$req->execute(array($login, $mdp));
		if($data = $req->fetch())
		{
			$user = json_decode(getUtilisateurById($data["utilisateur_id"]));
		}
		
		return json_encode($user);
	}
	
	function modifierDate($d) // retourne JJ/MM/AAAA + HH:mm:ss
	{
		$date["jour"] = substr($d, 8, 2)."/".substr($d, 5, 2)."/".substr($d, 0, 4);
		$date["heure"] = substr($d, 11, 8);
		
		return json_encode($date);
	}
?>