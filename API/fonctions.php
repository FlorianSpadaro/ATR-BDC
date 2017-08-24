<?php
	function deleteMessageById($id)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("DELETE FROM utilisateur_messages WHERE id = ?");
			$data = $req->execute(array($id));
		}catch(Exception $e){
			$data = false;
		}
		
		return json_encode($data);
	}

	function getMessagesByUtilisateurId($id)
	{
		include("connexionBdd.php");
		
		$messages = null;
		$i = 0;
		$j = 0;
		$req = $bdd->prepare("SELECT * FROM utilisateur_messages WHERE utilisateur_id = ? OR correspondant_id = ? ORDER BY lu, date DESC");
		$req->execute(array($id, $id));
		while($data = $req->fetch())
		{
			if($data["utilisateur_id"] == $id)
			{
				$messages["recus"][$i]["id"] = $data["id"];
				$messages["recus"][$i]["utilisateur"] = json_decode(getUtilisateurById($data["utilisateur_id"]));
				$messages["recus"][$i]["message"] = $data["message"];
				$messages["recus"][$i]["lu"] = $data["lu"];
				$messages["recus"][$i]["date"] = json_decode(modifierDate($data["date"]));
				$messages["recus"][$i]["correspondant"] = json_decode(getUtilisateurById($data["correspondant_id"]));
				$messages["recus"][$i]["sujet"] = $data["sujet"];
				$i++;
			}
			if($data["correspondant_id"] == $id)
			{
				$messages["envoyes"][$j]["id"] = $data["id"];
				$messages["envoyes"][$j]["utilisateur"] = json_decode(getUtilisateurById($data["utilisateur_id"]));
				$messages["envoyes"][$j]["message"] = $data["message"];
				$messages["envoyes"][$j]["lu"] = $data["lu"];
				$messages["envoyes"][$j]["date"] = json_decode(modifierDate($data["date"]));
				$messages["envoyes"][$j]["correspondant"] = json_decode(getUtilisateurById($data["correspondant_id"]));
				$messages["envoyes"][$j]["sujet"] = $data["sujet"];
				$j++;
			}
			
		}
		
		return json_encode($messages);
	}

	function getNbNotifsNonVuesByUtilisateurId($id)
	{
		include("connexionBdd.php");
		
		$nbNotifs = 0;
		$req = $bdd->prepare("SELECT COUNT(*) nb FROM utilisateur_notifs WHERE vu = 'FALSE' AND utilisateur_id = ?");
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
		$req = $bdd->prepare("SELECT COUNT(*) nb FROM utilisateur_messages WHERE lu = 'FALSE' AND utilisateur_id = ?");
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
			$req = $bdd->prepare("INSERT INTO utilisateur_messages(utilisateur_id, sujet, message, date, correspondant_id) VALUES(?, ?, ?, NOW(), ?)");
			$data = $req->execute(array($idUser, $sujet, $message, $idCorrespondant));
		}
		catch(Exception $e)
		{
			$data = false;
		}
		
		return json_encode($data);
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
			$actualite["notification"] = $data["notification"];
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

	function getActualitesByNum($numPremActu, $nbActus, $notifs)
	{
		include("connexionBdd.php");
		
		$actus = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id, titre, date_creation, date_derniere_maj, secteur_id, sous_domaine_id, projet_id, contrat_id, utilisateur_id, domaine_id, notification, description FROM actualite WHERE notification = ? ORDER BY date_creation DESC LIMIT ? OFFSET ?");
		$req->execute(array($notifs, $nbActus, $numPremActu));
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
			$actus[$i]["id"] = $data["id"];
			$actus[$i]["id"] = $data["id"];
			
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