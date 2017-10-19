<?php
    include("header.php");
    $contrats = json_decode(getContrats());
    $miniatures = json_decode(getMiniatures());
?>
<!DOCTYPE html>
<html>
     <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Contrats</title>

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
            .erreurNouvelleMiniature, .erreurNouvelleMiniatureNC{
                color: red;
                display: none;
            }
            
            #btnModifierContrat, #btnSupprimerContrat, #divAjouterMiniature, #divAjouterMiniatureNC{
                display: none;
            }
            /*#listeMiniaturesNouveauContrat{
                display: flex;
                justify-content: space-around;
            }*/
        </style>
    </head>
    
    <body>
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>Contrats</h1>
                            <hr class="small">
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
            <div>
                <button id="btnCreationNvContrat" data-toggle="modal" href="#modalNouveauContrat" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Nouveau Contrat</button>
                <span class="btn-group pull-right">
                    <button data-toggle="modal" href="#modalModifContrat" class="btn btn-info" id="btnModifierContrat">Modifier</button>
                    <button class="btn btn-danger" id="btnSupprimerContrat">Supprimer</button>
                </span>
            </div>
            <br/>
            <div id="listeContrats" class="list-group">
                
                <?php
                if($contrats != null)
                {
                    foreach($contrats as $contrat)
                    {
                        ?>
                        <a href="#" id="contrat-<?php echo $contrat->id ?>" class="list-group-item unContrat"><img width="30px" height="30px" src="<?php echo $contrat->miniature->url ?>" /> <?php echo $contrat->libelle ?></a>
                        <?php
                    }
                }
                ?>
              <!--<a href="#" class="list-group-item">First item</a>
              <a href="#" class="list-group-item">Second item</a>
              <a href="#" class="list-group-item">Third item</a>-->
            </div>
        </div>
        
        <div class="modal fade" id="modalModifContrat">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Modifier Contrat</h4>
              </div>
              <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="libelleContrat">Libellé du contrat:</label>
                        <input type="text" class="form-control" id="libelleContrat" name="libelleContrat" required />
                    </div>
                    <div class="form-group">
                        <div>
                            <label>Miniature: </label>
                        </div>
                        <div id="listeMiniatures" class="container-fluid listeMiniatures">
                            <!--<?php
                            if($miniatures != null)
                            {
                                $i = 0;
                                foreach($miniatures as $miniature)
                                {
                                    ?>
                                    <div class="radio  col-lg-3" style="display: flex" style="justify-content: space-between">
                                        <div>
                                            <label class="radio" for="miniature-<?php echo $miniature->id ?>"><input type="radio" name="miniature" value="miniature-<?php echo $miniature->id ?>" id="miniature-<?php echo $miniature->id ?>" /> <img width="50px" height="50px" src="<?php echo $miniature->url ?>" /></label>
                                        </div>
                                        <div>
                                            <button id="btnSupprMiniatureMC-<?php echo $miniature->id ?>" type="button" class="close btnSupprMiniature"><span class="glyphicon glyphicon-remove"></span></button>
                                        </div>
                                    </div>
                                    <?php
                                        $i++;
                                }
                            }
                            ?>-->
                        </div>
                        <button class="btn btn-link pull-right" id="btnAjouterMiniature"><span class="glyphicon glyphicon-plus"></span> Ajouter miniature</button>
                        <div id="divAjouterMiniature" class="well">
                            <h4>Nouvelle Miniature</h4>
                            <div class="form-group">
                                <label for="nomNouvelleMiniature">Nom: </label>
                                <input type="text" id="nomNouvelleMiniature" name="nomNouvelleMiniature" class="form-control" />
                                <span class="meta erreurNouvelleMiniature" id="erreurNomMiniature">Veuillez saisir un nom pour la miniature</span>
                            </div>
                            <div class="form-group">
                                <label for="fichierMiniature">Miniature: </label>
                                <input type="file" name="fichierMiniature" id="fichierMiniature" class="form-control" />
                                <span class="meta erreurNouvelleMiniature" id="erreurFichierMiniature">Veuillez choisir une image (1 Mo max)</span>
                            </div>
                            <div class="btn-group pull-right">
                                <button class="btn-link" id="annulerAjoutMiniature">Annuler</button>
                                <button class="btn-default" id="ajouterNouvelleMiniature">Ajouter</button>
                            </div>
                            <br/>
                        </div>
                        <br/>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button class="btn btn-info" id="validerModifContrat">Valider</button>
                <button class="btn btn-danger" data-dismiss="modal">Annuler</button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="modalNouveauContrat">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Nouveau Contrat</h4>
              </div>
              <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="libelleNouveauContrat">Libellé du contrat:</label>
                        <input type="text" class="form-control" id="libelleNouveauContrat" name="libelleNouveauContrat" required />
                    </div>
                    <div class="form-group">
                        <div>
                            <label>Miniature: </label>
                        </div>
                        <div id="listeMiniaturesNouveauContrat" class="container-fluid listeMiniatures">
                            <!--<br/><br/>
                            <?php
                            if($miniatures != null)
                            {
                                $i = 0;
                                foreach($miniatures as $miniature)
                                {
                                    if($i == 3)
                                    {
                                        $i = 0;
                                        echo "<br/>";
                                    }
                                    ?>
                                    <div class="radio col-lg-3" style="display: flex" style="justify-content: space-between">
                                        <div>
                                            <label class="radio" for="miniatureNC-<?php echo $miniature->id ?>"><input type="radio" name="miniatureNC" value="miniatureNC-<?php echo $miniature->id ?>" id="miniatureNC-<?php echo $miniature->id ?>" /> <img width="50px" height="50px" src="<?php echo $miniature->url ?>" /></label>
                                        </div>
                                        <div>
                                            <button id="btnSupprMiniature-<?php echo $miniature->id ?>" type="button" class="close btnSupprMiniature"><span class="glyphicon glyphicon-remove"></span></button>
                                        </div>
                                    </div>
                                    <?php
                                        $i++;
                                }
                            }
                            ?>-->
                        </div>
                        <button class="btn btn-link pull-right" id="btnAjouterMiniatureNC"><span class="glyphicon glyphicon-plus"></span> Ajouter miniature</button>
                        <div id="divAjouterMiniatureNC" class="well">
                            <h4>Nouvelle Miniature</h4>
                            <div class="form-group">
                                <label for="nomNouvelleMiniatureNC">Nom: </label>
                                <input type="text" id="nomNouvelleMiniatureNC" name="nomNouvelleMiniatureNC" class="form-control" />
                                <span class="meta erreurNouvelleMiniatureNC" id="erreurNomMiniatureNC">Veuillez saisir un nom pour la miniature</span>
                            </div>
                            <div class="form-group">
                                <label for="fichierMiniatureNC">Miniature: </label>
                                <input type="file" name="fichierMiniatureNC" id="fichierMiniatureNC" class="form-control" />
                                <span class="meta erreurNouvelleMiniatureNC" id="erreurFichierMiniatureNC">Veuillez choisir une image (1 Mo max)</span>
                            </div>
                            <div class="btn-group pull-right">
                                <button class="btn-link" id="annulerAjoutMiniatureNC">Annuler</button>
                                <button class="btn-default" id="ajouterNouvelleMiniatureNC">Ajouter</button>
                            </div>
                            <br/>
                        </div>
                        <br/>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button class="btn btn-info" id="validerNouveauContrat">Valider</button>
                <button class="btn btn-danger" data-dismiss="modal" id="annulerNouveauContrat">Annuler</button>
              </div>
            </div>
          </div>
        </div>
        
        <!--  Footer -->

        <?php include("footer.php"); ?>
        <script src="js/myJs/contrats.js"></script>
    </body>
</html>