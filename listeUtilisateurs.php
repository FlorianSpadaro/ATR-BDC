<?php
    include("header.php");
    if(!isset($_SESSION["niveau"]) || $_SESSION["niveau"]->niveau != 3)
    {
        header('Location: index.php');
        exit();
    }
    $utilisateurs = json_decode(getUtilisateurs());
    $fonctions = json_decode(getFonctions());
    $niveaux = json_decode(getNiveaux());
?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Liste des utilisateurs</title>

        <!-- Bootstrap Core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Theme CSS -->
        <link href="css/clean-blog.min.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/myCss/sousMenus.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
        <style>
            #divAjouterFonctionModif, #divAjouterFonctionNew, #attenteReinitialiserMdp{
                display: none;
            }
            #erreurLibelleNouvelleFonctionModif, .erreurModifUser, #erreurLibelleNouvelleFonctionNew, .erreurNewUser{
                color: red;
                display: none;
            }
            .formGererAbonnement{
                display: inline-block;
            }
            #colonneAbonnement{
                text-align: center;
            }
        </style>
        
    </head>
    
    <body>
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>Liste des utilisateurs</h1>
                            <hr class="small">
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
            <!--<button data-toggle="modal" href="#nouvelUtilisateur" class="btn btn-success" id="btnNouvelUtilisateur"><span class="glyphicon glyphicon-plus"></span> Ajouter utilisateur</button>
            <br/><br/>-->
            <!--<div class="help-block pull-right">
                Vous ne pouvez supprimer que les utilisateurs inactifs (un utilisateur devient actif lors de sa première connexion)
            </div>-->
            <table id="listeUtilisateurs" class="tablesorter table table-striped table-hover"> 
                <thead> 
                <tr> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Nom</a></th> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Prénom</a></th> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Email</a></th> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Fonction</a></th> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Niveau</a></th> 
                    <th>Abonnements</th> 
                </tr> 
                </thead> 
                <tbody>
                <?php
                    if($utilisateurs != null)
                    {
                        foreach($utilisateurs as $user)
                        {
                            ?>
                            <tr id="utilisateur-<?php echo $user->id ?>"> 
                                <td><?php echo strtoupper($user->nom) ?></td> 
                                <td><?php echo ucfirst(strtolower($user->prenom)) ?></td> 
                                <td><?php echo $user->email ?></td> 
                                <td><?php echo $user->fonction->libelle ?></td> 
                                <td><?php echo $user->fonction->niveau->libelle ?></td>
                                <td id="colonneAbonnement">
                                    <form class="formGererAbonnement" method="post" action="mesAbonnements.php">
                                        <input type="hidden" name="usr" value="<?php echo $user->id ?>" />
                                        <a href="#" class="submitGererAbo" type="submit" data-toggle="tooltip" title="gérer abonnements"><span class="glyphicon glyphicon-heart-empty"></span></a>
                                    </form>
                                    <!--<a data-toggle="modal" href="#modificationsUtilisateur" class="modifierUser" title="modifier utilisateur"><span class="glyphicon glyphicon-edit"></span></a>-->
                                    <?php
                                    if(!$user->actif)
                                    {
                                        ?>
                                        <!--<a href="#" class="supprimerUser" data-toggle="tooltip" title="supprimer utilisateur"><span class="glyphicon glyphicon-remove-sign"></span></a>-->
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                ?>
                </tbody> 
            </table> 
        </div>
        
        <div class="modal" id="modificationsUtilisateur">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Modifier utilisateur</h4>
              </div>
              <div class="modal-body">
                  <!--<button class="btn btn-info" id="reinitialiserMdpUser"><span class="glyphicon glyphicon-wrench"></span> Réinitialiser mot de passe</button>
                  <img id="attenteReinitialiserMdp" src="img/wait.gif" />
                  <hr>-->
                <form>
                    <input type="hidden" name="idUtilisateurModif" id="idUtilisateurModif" />
                    <div>
                        <img width="100" height="130" id="photoUtilisateurModif" src="" />
                        <input type="file" id="nouvellePhotoUtilisateurModif" />
                    </div>
                    <div class="form-group">
                        <label>Nom : </label>
                        <input type="text" name="nomUtilisateurModif" id="nomUtilisateurModif" class="form-control" required />
                        <div class="help-block erreurModifUser" id="erreurNomUserModif">Veuillez saisir un nom</div>
                    </div>
                    <div class="form-group">
                        <label>Prenom : </label>
                        <input type="text" name="prenomUtilisateurModif" id="prenomUtilisateurModif" class="form-control" required />
                        <div class="help-block erreurModifUser" id="erreurPrenomUserModif">Veuillez saisir un prénom</div>
                    </div>
                    <div class="form-group">
                        <label>Email : </label>
                        <input type="email" name="emailUtilisateurModif" id="emailUtilisateurModif" class="form-control" required />
                        <div class="help-block erreurModifUser" id="erreurEmailUserModif">Veuillez saisir un email</div>
                    </div>
                    <div class="form-group">
                        <label>Fonction : </label>
                        <select class="form-control" name="fonctionUtilisateurModif" id="fonctionUtilisateurModif" required>
                            <?php
                            if($fonctions != null)
                            {
                                foreach($fonctions as $fonction)
                                {
                                    ?>
                                    <option value="fonction-<?php echo $fonction->id ?>"><?php echo $fonction->libelle ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <button id="ajouterFonctionUtilisateurModif" class="btn btn-link"><span class="glyphicon glyphicon-plus"></span> Ajouter fonction</button>
                        <br/>
                        <div id="divAjouterFonctionModif" class="well">
                            <h4>Nouvelle Fonction</h4>
                            <div class="form-group">
                                <label>Libellé</label>
                                <input type="text" class="form-control" id="libelleFonctionModif" />
                                <span class="help-block" id="erreurLibelleNouvelleFonctionModif">Veuillez saisir un libellé</span>
                            </div>
                            <div class="form-group">
                                <label>Niveau</label>
                                <select id="niveauNouvelleFonctionModif" name="niveauNouvelleFonctionModif" class="form-control">
                                    <?php
                                    if(niveaux != null)
                                    {
                                        foreach($niveaux as $niv)
                                        {
                                            ?>
                                            <option value="niveau-<?php echo $niv->id ?>">
                                                <?php echo $niv->libelle ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="btn-group pull-right">
                                <button class="btn btn-link" id="annulerNouvelleFonctionModif">Annuler</button>
                                <button class="btn btn-default" id="validerNouvelleFonctionModif">Valider</button>
                            </div>
                            <br/>
                            <br/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Niveau : </label>
                        <label id="niveauUtilisateurModif" class="label label-default" ></label>
                        <div class="help-block">Info : Le niveau est lié à la fonction de l'utilisateur</div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                  <div class="btn-group">
                      <button id="annulerModifUtilisateur" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                      <button id="validerModifUtilisateur" class="btn btn-info">Valider</button>
                  </div>
              </div>
            </div>
          </div>
        </div>
        
        
        <!-- NOUVEL UTILISATEUR -->
        
        <div class="modal" id="nouvelUtilisateur">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Nouvel utilisateur</h4>
              </div>
              <div class="modal-body">
                <form>
                    <div>
                        <img width="100" height="130" id="photoNouvelUtilisateur" src="images/photosUtilisateurs/inconnu.jpg" />
                        <input type="file" id="nouvellePhotoUtilisateurNew" />
                    </div>
                    <div class="form-group">
                        <label>Nom : </label>
                        <input type="text" name="nomUtilisateurNew" id="nomUtilisateurNew" class="form-control" required />
                        <div class="help-block erreurNewUser" id="erreurNomUserNew">Veuillez saisir un nom</div>
                    </div>
                    <div class="form-group">
                        <label>Prenom : </label>
                        <input type="text" name="prenomUtilisateurNew" id="prenomUtilisateurNew" class="form-control" required />
                        <div class="help-block erreurNewUser" id="erreurPrenomUserNew">Veuillez saisir un prénom</div>
                    </div>
                    <div class="form-group">
                        <label>Email : </label>
                        <input type="email" name="emailUtilisateurNew" id="emailUtilisateurNew" class="form-control" required />
                        <div class="help-block erreurNewUser" id="erreurEmailUserNew">Veuillez saisir un email</div>
                    </div>
                    <div class="form-group">
                        <label>Fonction : </label>
                        <select class="form-control" name="fonctionUtilisateurNew" id="fonctionUtilisateurNew" required>
                            <?php
                            if($fonctions != null)
                            {
                                foreach($fonctions as $fonction)
                                {
                                    ?>
                                    <option value="fonction-<?php echo $fonction->id ?>"><?php echo $fonction->libelle ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <button id="ajouterFonctionUtilisateurNew" class="btn btn-link"><span class="glyphicon glyphicon-plus"></span> Ajouter fonction</button>
                        <br/>
                        <div id="divAjouterFonctionNew" class="well">
                            <h4>Nouvelle Fonction</h4>
                            <div class="form-group">
                                <label>Libellé</label>
                                <input type="text" class="form-control" id="libelleFonctionNew" />
                                <span class="help-block" id="erreurLibelleNouvelleFonctionNew">Veuillez saisir un libellé</span>
                            </div>
                            <div class="form-group">
                                <label>Niveau</label>
                                <select id="niveauNouvelleFonctionNew" name="niveauNouvelleFonctionNew" class="form-control">
                                    <?php
                                    if(niveaux != null)
                                    {
                                        foreach($niveaux as $niv)
                                        {
                                            ?>
                                            <option value="niveau-<?php echo $niv->id ?>">
                                                <?php echo $niv->libelle ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="btn-group pull-right">
                                <button class="btn btn-link" id="annulerNouvelleFonctionNew">Annuler</button>
                                <button class="btn btn-default" id="validerNouvelleFonctionNew">Valider</button>
                            </div>
                            <br/>
                            <br/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Niveau : </label>
                        <label id="niveauUtilisateurNew" class="label label-default" ></label>
                        <div class="help-block">Info : Le niveau est lié à la fonction de l'utilisateur</div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                  <div class="btn-group">
                      <button id="annulerNewUtilisateur" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                      <button id="validerNewUtilisateur" class="btn btn-info">Valider</button>
                  </div>
              </div>
            </div>
          </div>
        </div>
        
        
        <!--  Footer -->

        <?php include("footer.php"); ?> 
        <script type="text/javascript" src="vendor/tablesort/jquery.tablesorter.min.js"></script>
        <script src="js/myJs/listeUtilisateurs.js"></script>
    </body>
</html>