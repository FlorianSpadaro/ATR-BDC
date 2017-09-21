<?php
    include("header.php");
    $domaine = json_decode(getDomaineById($_GET["id"]));
    $sousDomaines = json_decode(getProjetsBySousDomaineByDomaineId($_GET["id"]));
    $projetsGeneriques = json_decode(getProjetsGeneriquesByDomaineId($_GET["id"]));
    $contrats = json_decode(getContrats());
    $secteurs = json_decode(getSecteurs());
    $domaines = json_decode(getDomaines());
?>
    <!DOCTYPE html>
    <html>

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>
            <?php echo $domaine->libelle ?>
        </title>

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
            #suppressionDomaine{
                color: red;
            }
            #formNouveauProjet{
                display: inline-block;
            }
        </style>
    </head>

    <body>
        <?php
        if(isset($_GET["contrats"]) && $domaine->secteur->id == 2)
        {
            ?>
            <input type="hidden" name="cacherSd" id="cacherSd" value="<?php echo $_GET["contrats"] ?>" />
            <?php
        }
        else{
            ?>
            <input type="hidden" name="cacherSd" id="cacherSd" value="false" />
            <?php
        }
        ?>
        <input type="hidden" id="idDomaine" name="idDomaine" value="<?php echo $_GET["id"] ?>" />
        
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 ">
                        <div class="site-heading">
                            <h1>
                                <?php echo $domaine->libelle ?>
                            </h1>
                            <h2 class="subheading">
                                Domaine du secteur
                                <?php echo $domaine->secteur->libelle ?>
                            </h2>
                            <span class="meta">
                                <?php
                                if($domaine->description != null)
                                {
                                    echo $domaine->description;
                                }
                                ?>
                            </span>
                            <?php
                            if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]->niveau == 3)
                            {
                                ?>
                                <br/>
                                <div class="btn-group">
                                    <button data-toggle="modal" href="#divModifierDomaine" class="btn btn-link" id="modificationDomaine">Modifier ce domaine</button>
                                    <button class="btn btn-link" id="suppressionDomaine">Supprimer ce domaine</button>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </header>
        
        <div class="modal" id="divModifierSousDomaine">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Modifier Sous-Domaine</h4>
              </div>
              <div class="modal-body">
                <form>
                    <input type="hidden" name="idSousDomaine" id="idSousDomaine" />
                    <div class="form-group">
                        <label>Libelle</label>
                        <input type="text" name="libelleSousDomaineModif" id="libelleSousDomaineModif" class="form-control" />
                        <div class="help-block">Info: ce libellé sera précédé du nom du contrat</div>
                    </div>
                    <div class="form-group">
                        <label>Description (facultatif)</label>
                        <input type="text" name="descriptionSousDomaineModif" id="descriptionSousDomaineModif" class="form-control" />
                    </div>
                    <div class="form-gorup">
                        <label>Contrat</label>
                        <select class="form-control" id="contratSousDomaineModif" name="contratSousDomaineModif">
                            <?php
                            foreach($contrats as $contrat)
                            {
                                ?>
                                <option value="<?php echo $contrat->id ?>"><?php echo $contrat->libelle ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Domaine</label>
                        <select id="domaineSousDomaineModif" name="domaineSousDomaineModif" class="form-control">
                            <?php
                            if($domaines != null)
                            {
                                foreach($domaines as $dom)
                                {
                                    ?>
                                    <option value="<?php echo $dom->id ?>"><?php echo $dom->libelle ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                  <div class="btn-group">
                      <button class="btn btn-danger" data-dismiss="modal">Annuler</button>
                      <button id="btnValiderModifSousDomaine" class="btn btn-info">Valider</button>
                  </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal" id="divNouveauSousDomaine">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Nouveau Sous-Domaine</h4>
              </div>
              <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Libelle</label>
                        <input type="text" name="libelleSousDomaineNew" id="libelleSousDomaineNew" class="form-control" />
                        <div class="help-block">Info: ce libellé sera précédé du nom du contrat</div>
                    </div>
                    <div class="form-group">
                        <label>Description (facultatif)</label>
                        <input type="text" name="domaineSousDomaineNew" id="domaineSousDomaineNew" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Contrat</label>
                        <select class="form-control" name="contratSousDomaineNew" id="contratSousDomaineNew">
                            <?php
                            foreach($contrats as $contrat)
                            {
                                ?>
                                <option value="<?php echo $contrat->id ?>"><?php echo $contrat->libelle ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                  <div class="btn-group">
                      <button class="btn btn-danger" data-dismiss="modal">Annuler</button>
                      <button id="btnValiderNewSousDomaine" class="btn btn-info">Valider</button>
                  </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal" id="divModifierDomaine">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Modifier Domaine</h4>
              </div>
              <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Libellé</label>
                        <input type="text" class="form-control" name="libelleModifDomaine" id="libelleModifDomaine" required />
                    </div>
                    <div class="form-group">
                        <label>Description (facultatif)</label>
                        <input type="text" class="form-control" name="descriptionModifDomaine" id="descriptionModifDomaine" />
                    </div>
                    <div class="form-group">
                        <label>Secteur</label>
                        <select id="secteurModifDomaine" name="secteurModifDomaine" class="form-control">
                            <?php
                            if($secteurs != null)
                            {
                                foreach($secteurs as $secteur)
                                {
                                    ?>
                                    <option value="<?php echo $secteur->id ?>"><?php echo $secteur->libelle ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                  <div class="btn-group">
                      <button class="btn btn-danger" id="btnAnnulerModifDomaine" data-dismiss="modal">Annuler</button>
                      <button class="btn btn-info" id="btnValiderModifDomaine">Valider</button>
                  </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal" id="divNouveauProjet">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Nouveau Projet</h4>
              </div>
              <div class="modal-body">
                <form id="formNouveauProjet">
                    <input type="hidden" id="utilisateur_id" name="utilisateur_id" value="<?php echo $_SESSION["user_id"] ?>" />
                    <input type="hidden" id="sous_domaine_id" name="sous_domaine_id" />
                    <div class="form-group">
                        <label>Image d'entête (facultatif)</label>
                        <input type="file" name="imageEntete" id="imageEntete"/>
                        <div class="help-block">Il est conseillé de choisir une image de haute résolution</div>
                    </div>
                    <div class="form-group">
                        <label>Titre</label>
                        <input id="titreProjet" name="titreProjet" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Description (facultatif)</label>
                        <input id="descriptionProjet" name="descriptionProjet" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Contrat</label>
                        <select class="form-control" id="contratProjet" name="contratProjet" required>
                            <option value="0">Aucun</option>
                            <?php
                            if($contrats != null)
                            {
                                foreach($contrats as $contrat)
                                {
                                    ?>
                                    <option value="<?php echo $contrat->id ?>"><?php echo $contrat->libelle ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Contenu (au format html ou htm)</label>
                        <input type="file" name="contenuProjet" id="contenuProjet" required/>
                        <div class="help-block">Pour créer un fichier au format htm ou html, créer un fichier sur Word, puis lorsque vous l'enregistrez en cliquant sur "Enregistrer sous", choisir comme type (en dessous du nom du fichier) "Page web (*.htm;*.html)"</div>
                    </div>
                    <div class="form-group">
                        <label>Pièces jointes</label>
                        <input type="file" name="pjProjet" id="pjProjet" multiple />
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button id="btnValiderNewProjet" class="btn btn-success">Valider</button>
                <button class="btn btn-info" data-dismiss="modal">Fermer</button>
              </div>
            </div>
          </div>
        </div>

            <div class="container">
                <?php
                if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]->niveau == 3)
                {
                    ?>
                    <div>
                        <button data-toggle="modal" href="#divNouveauSousDomaine" id="btnNouveauSousDomaine" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Ajouter sous-domaine</button>
                    </div>
                    <?php
                }
                ?>
                <br/>
                <!--<ul class="nav nav-pills container">
                    <li class="active"><a href="#projetsSpecifiques" data-toggle="tab">Projets Spécifiques</a></li>
                    <li><a href="#projetsGeneriques" data-toggle="tab">Projets Génériques</a></li>
                </ul>
                <br/>
                <div class="tab-content">
                    
                    <div class="tab-pane fade" id="projetsGeneriques">
                        <div class="list-group">
                            <?php
                            /*if($projetsGeneriques != null)
                            {
                                foreach($projetsGeneriques as $projetGen)
                                {
                                    */?>
                                    <a href="projet.php?id=<?php /*echo $projetGen->id*/ ?>" class="list-group-item" title="<?php /*echo $projetGen->description*/ ?>"><?php /*echo $projetGen->titre*/ ?></a>
                                    <?php
                               /* }
                            }
                            else{*/
                                ?>
                            <label>Ce domaine ne contient aucun projet générique</label>
                                <?php
                            //}
                            ?>
                        </div>
                    </div>-->
                    
                    <!--<div class="tab-pane active fade in" id="projetsSpecifiques">-->
                        <div id="monaccordeon" class="panel-group">
                        <h3>Sous-domaines</h3>
                          <?php
                          if(isset($sousDomaines) && ($sousDomaines != null))
                          {
                              foreach($sousDomaines as $sd)
                          {
                              ?>
                            <div class="panel panel-default divSd contrat__<?php echo $sd->contrat_id ?>">
                              <div class="panel-heading"> 
                                <h3 class="panel-title">
                                  <a href="#sd<?php echo $sd->id ?>" data-parent="#monaccordeon" data-toggle="collapse" title="<?php if(isset($sd->description) && ($sd->description != null)){echo $sd->description;} ?>" class="click"><span class="badge pull-right">
                            <?php 
                            if(isset($sd->projets) && ($sd->projets != null))
                            {
                                $nb = 0;
                                foreach($sd->projets as $proj)
                                {
                                    $nb++;
                                }
                                echo $nb;
                            }
                            else{
                                echo "0";
                            }
                            ?>
                            </span> <?php echo $sd->libelle ?></a> 
                                </h3>
                              </div>
                              <div id="sd<?php echo $sd->id ?>" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <?php
                                    if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]->niveau == 3)
                                    {
                                        ?>
                                        <div class="btn-group">
                                            <button id="btnModifierSousDomaine-<?php echo $sd->id ?>" data-toggle="modal" href="#divModifierSousDomaine" class="btn btn-info btnModifierSousDomaine">Modifier sous-domaine</button>
                                            <button id="btnSupprimerSousDomaine-<?php echo $sd->id ?>" class="btn btn-danger btnSupprimerSousDomaine">Supprimer sous-domaine</button>
                                        </div>
                                        <br/>
                                        <br/>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if(isset($sd->projets) && ($sd->projets != null))
                                    {
                                        ?>
                                        <div class="list-group">
                                        <?php
                                        if($projetsGeneriques != null)
                                        {
                                            foreach($projetsGeneriques as $proGen)
                                            {
                                                ?>
                                                <a href="projet.php?id=<?php echo $projet->id ?>" class="list-group-item list-group-item-info" title="<?php echo $projet->description ?>"><?php echo $projet->titre ?><span class="badge">Générique</span></a>
                                                <?php
                                            }
                                        }
                                        foreach($sd->projets as $projet)
                                        {
                                            ?>
                                            <a href="projet.php?id=<?php echo $projet->id ?>" class="list-group-item" title="<?php echo $projet->description ?>"><?php echo $projet->titre ?><span class="badge">Spécifique</span></a>
                                            <?php
                                        }
                                        ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                              </div>
                            </div>
                            <?php
                          }
                          }
                          else{
                            ?>
                            <label class="label label-info">Ce domaine ne contient aucun sous-domaine</label>
                            <?php
                          }
                          ?>

                      </div>
                    <!--</div>-->
                    
                </div>
                
            </div>
        <br/>
        <br/>
    </body>


    <!--  Footer -->

    <?php include("footer.php"); ?>
    <script src="js/myJs/domaine.js"></script>

    </html>
