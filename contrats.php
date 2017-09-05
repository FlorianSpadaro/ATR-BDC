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
            #btnModifierContrat, #btnSupprimerContrat{
                display: none;
            }
            #listeMiniatures, #listeMiniaturesNouveauContrat{
                display: flex;
                justify-content: space-around;
            }
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
                <button data-toggle="modal" href="#modalNouveauContrat" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Nouveau Contrat</button>
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
                        <label>Miniature: </label><br/>
                        <div id="listeMiniatures">
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
                                    <div class="radio">
                                        <label class="radio" for="miniature-<?php echo $miniature->id ?>"><input type="radio" name="miniature" value="miniature-<?php echo $miniature->id ?>" id="miniature-<?php echo $miniature->id ?>" /> <img width="50px" height="50px" src="<?php echo $miniature->url ?>" /></label>
                                    </div>
                                    <?php
                                        $i++;
                                }
                            }
                            ?>
                        </div>
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
                        <label>Miniature: </label><br/>
                        <div id="listeMiniaturesNouveauContrat">
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
                                    <div class="radio">
                                        <label class="radio" for="miniatureNC-<?php echo $miniature->id ?>"><input type="radio" name="miniatureNC" value="miniatureNC-<?php echo $miniature->id ?>" id="miniatureNC-<?php echo $miniature->id ?>" /> <img width="50px" height="50px" src="<?php echo $miniature->url ?>" /></label>
                                    </div>
                                    <?php
                                        $i++;
                                }
                            }
                            ?>
                        </div>
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