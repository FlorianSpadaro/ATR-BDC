<?php
    include("header.php");

    $secteurs = json_decode(getSecteursDomainesSousDomainesProjets());
    $contrats = json_decode(getContrats());
    $abonnements = json_decode(getAbonnementsByUtilisateurId($_SESSION["user_id"]));
?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Abonnements</title>

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

    </head>
    
    <body>
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>Mes Abonnements</h1>
                            <span class="meta">TEST</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
            
            <div class="panel-group" id="secteurs">
                <h3> Secteurs</h3>
            <?php
            if(isset($secteurs) && ($secteurs != null))
            {
                foreach($secteurs as $secteur)
                {
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a href="#panelSecteur<?php echo $secteur->id ?>" data-toggle="collapse" data-parent="#secteurs" class="lienPanel"> <?php echo $secteur->libelle ?> </a>
                                    <span>
                                        <span class="label label-default">Domaines <span>0</span>/<?php echo $secteur->nbDomaines ?></span>
                                        <span class="label label-default">Sous-Domaines <span>0</span>/<?php echo $secteur->nbSousDomaines ?></span>
                                        <span class="label label-default">Projets <span>0</span>/<?php echo $secteur->nbProjets ?></span>
                                    </span> 
                                    <a href="#" class="pull-right">S'abonner <span class="glyphicon glyphicon-plus-sign"></span></a>
                            </h3>
                        </div>
                        <div id="panelSecteur<?php echo $secteur->id ?>" class="panel-collapse collapse in">
                            <div class="panel-body">
                                
                                <!-- DOMAINES -->
                                
                                <?php
                                if(isset($secteur->domaine) && ($secteur->domaine != null))
                                {
                                    ?>
                                    <div class="panel-group" id="domainesSecteur<?php echo $secteur->id ?>">
                                        <h3> Domaines</h3>
                                        <?php
                                        foreach($secteur->domaine as $domaine)
                                        {
                                            ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">
                                                        <a href="#panelDomaine<?php echo $domaine->id ?>" data-toggle="collapse" data-parent="#domainesSecteur<?php echo $secteur->id ?>" class="lienPanel"> <?php echo $domaine->libelle ?> </a>
                                                        <span>
                                                            <span class="label label-default">Sous-Domaines <span>0</span>/<?php echo $domaine->nbSousDomaines ?></span>
                                                            <span class="label label-default">Projets <span>0</span>/<?php echo $domaine->nbProjets ?></span>
                                                        </span> 
                                                        <a href="#" class="pull-right">S'abonner <span class="glyphicon glyphicon-plus-sign"></span></a>
                                                    </h3>
                                                </div>
                                                <div id="panelDomaine<?php echo $domaine->id ?>" class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        
                                                        <!-- SOUS-DOMAINES -->
                                                        
                                                        <?php
                                                        if(isset($domaine->sous_domaine) && ($domaine->sous_domaine != null))
                                                        {
                                                            ?>
                                                            <div class="panel-group" id="sousDomainesDomaine<?php echo $domaine->id ?>">
                                                                <h3> Sous-Domaines</h3>
                                                                <?php
                                                                foreach($domaine->sous_domaine as $sousDomaine)
                                                                {
                                                                    ?>
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">
                                                                                <a href="#panelSousDomaine<?php echo $sousDomaine->id ?>" data-toggle="collapse" data-parent="#sousDomainesDomaine<?php echo $domaine->id ?>" class="lienPanel"> <?php echo $sousDomaine->libelle ?> </a>
                                                                                <span>
                                                                                    <span class="label label-default">Projets <span>0</span>/<?php echo $sousDomaine->nbProjets ?></span>
                                                                                </span> 
                                                                                <a href="#" class="pull-right">S'abonner <span class="glyphicon glyphicon-plus-sign"></span></a>
                                                                            </h3>
                                                                        </div>
                                                                        <div id="panelSousDomaine<?php echo $sousDomaine->id ?>" class="panel-collapse collapse in">
                                                                            <div class="panel-body">
                                                                                
                                                                                <!-- PROJETS -->
                                                                                
                                                                                <?php
                                                                                if(isset($sousDomaine->projet) && ($sousDomaine->projet != null))
                                                                                {
                                                                                    ?>
                                                                                    <div class="list-group">
                                                                                        <?php
                                                                                        foreach($sousDomaine->projet as $projet)
                                                                                        {
                                                                                            ?>
                                                                                            <div class="list-group-item">
                                                                                                <a href="#" title="<?php echo $projet->description ?>">
                                                                                                    <?php echo $projet->titre ?>
                                                                                                </a>
                                                                                                <a href="#" class="pull-right">S'abonner <span class="glyphicon glyphicon-plus-sign"></span></a>
                                                                                            </div>
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
            ?>
            </div>
            
<!--            
           <div class="panel-group" id="accordeon">
                <h3> Secteurs</h3>
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">
                      <a href="#item1" data-toggle="collapse" data-parent="#accordeon"> Accordéon </a>
                        <span>
                            <span class="label label-default">Domaines <span>0</span>/100</span>
                            <span class="label label-default">Sous-Domaines <span>0</span>/100</span>
                            <span class="label label-default">Projets <span>0</span>/10000</span>
                        </span> 
                        <a href="#" class="pull-right">S'abonner <span class="glyphicon glyphicon-plus-sign"></span></a>
                        
                    </h3>
                  </div>
                  <div id="item1" class="panel-collapse collapse in">
                    <div class="panel-body">
                        
                        
 Premier conteneur enfant  
                        
                        <div class="panel-group">
                            <h3> Domaines</h3>
                            <div class="panel panel-default">
                              <div class="panel-heading"> 
                                <h3 class="panel-title">
                                  <a href="#item11" data-toggle="collapse"> Accordéon </a> 
                                </h3>
                              </div>
                              <div id="item11" class="panel-collapse collapse in">
                                <div class="panel-body"> Ce plugin permet de créer des effets "accordéon" totalement paramétrables</div>
                              </div>
                            </div>
                            <div class="panel panel-default">
                              <div class="panel-heading"> 
                                <h3 class="panel-title">
                                  <a href="#item12" data-toggle="collapse"> Fenêtre modale </a> 
                                </h3>
                              </div>
                              <div id="item12" class="panel-collapse collapse">
                                <div class="panel-body"> Ce plugin permet de créer des fenêtres modales élégantes avec une grande simplicité. </div>
                              </div>
                            </div>
                            <div class="panel panel-default">
                              <div class="panel-heading"> 
                                <h3 class="panel-title">
                                  <a href="#item13" data-toggle="collapse"> Carousel </a>
                                </h3>
                              </div>
                              <div id="item13" class="panel-collapse collapse">
                                <div class="panel-body"> Ce plugin permet de faire défiler des images ou des vidéo, ou tout autre élément média avec une mise 
                            en forme esthétique </div>
                            </div>
                          </div>
                        </div>

                        
                        
                        
                    </div>
                  </div>
                </div>
                <div class="panel panel-success">
                  <div class="panel-heading"> 
                    <h3 class="panel-title">
                      <a href="#item2" data-toggle="collapse" data-parent="#accordeon"> Fenêtre modale </a>
                      <a href="#" class="pull-right">Se désabonner <span class="glyphicon glyphicon-ok-sign"></span></a>
                    </h3>
                  </div>
                  <div id="item2" class="panel-collapse collapse">
                    <div class="panel-body"> Ce plugin permet de créer des fenêtres modales élégantes avec une grande simplicité. </div>
                  </div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading"> 
                    <h3 class="panel-title">
                      <a href="#item3" data-toggle="collapse" data-parent="#accordeon"> Carousel </a> 
                    </h3>
                  </div>
                  <div id="item3" class="panel-collapse collapse">
                    <div class="panel-body"> Ce plugin permet de faire défiler des images ou des vidéo, ou tout autre élément média avec une mise 
                en forme esthétique </div>
                </div>
              </div>
            </div>-->
            
            
        </div>
        
        <!--  Footer -->

        <?php include("footer.php"); ?>
        <script src="js/myJs/mesAbonnements.js"></script>
        
    </body>
</html>