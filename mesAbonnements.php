<?php
    include("header.php");

    $secteurs = json_decode(getSecteursDomainesSousDomainesProjets($_SESSION["user_id"]));
    $contrats = json_decode(getContrats());
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
        
        <style>
            .abonner, .desabonner{
                text-align: right;
            }
            .etiquettes{
                margin-right: 10px;
            }
        </style>

    </head>
    
    <body>
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>Mes Abonnements</h1>
                            <span class="meta"></span>
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
                    <div class="panel panel-default" id="enteteSecteur<?php echo $secteur->id ?>">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a href="#panelSecteur<?php echo $secteur->id ?>" data-toggle="collapse" data-parent="#secteurs" class="lienPanel"> <?php echo $secteur->libelle ?> </a>
                                <a href="#" class="pull-right abonner col-lg-2" id="secteur-<?php echo $secteur->id ?>">S'abonner <span class="glyphicon glyphicon-plus-sign"></span></a>
                                    <span class="pull-right etiquettes">
                                        <span class="label label-default">Domaines <span class="nbAbo nbAboDomaine" id="domainesAboSecteur<?php echo $secteur->id ?>"><?php echo $secteur->nbDomainesAbo ?></span>/<?php echo $secteur->nbDomaines ?></span>
                                        <span class="label label-default">Sous-Domaines <span  id="sousDomainesAboSecteur<?php echo $secteur->id ?>" class="nbAbo nbAboSousDomaine"><?php echo $secteur->nbSousDomainesAbo ?></span>/<?php echo $secteur->nbSousDomaines ?></span>
                                        <span class="label label-default">Projets <span id="projetsAboSecteur<?php echo $secteur->id ?>" class="nbAbo nbAboProjet"><?php echo $secteur->nbProjetsAbo ?></span>/<?php echo $secteur->nbProjets ?></span>
                                    </span> 
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
                                            <div class="panel panel-default" id="enteteDomaine<?php echo $domaine->id ?>">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">
                                                        <a href="#panelDomaine<?php echo $domaine->id ?>" data-toggle="collapse" data-parent="#domainesSecteur<?php echo $secteur->id ?>" class="lienPanel" title="<?php echo $domaine->description ?>"> <?php echo $domaine->libelle ?> </a>
                                                        <a href="#" class="pull-right abonner col-lg-2" id="domaine-<?php echo $domaine->id ?>">S'abonner <span class="glyphicon glyphicon-plus-sign"></span></a>
                                                        <span class="pull-right etiquettes">
                                                            <span class="label label-default">Sous-Domaines <span id="sousDomainesAboDomaine<?php echo $domaine->id ?>" class="nbAbo nbAboSousDomaine"><?php echo $domaine->nbSousDomainesAbo ?></span>/<?php echo $domaine->nbSousDomaines ?></span>
                                                            <span class="label label-default">Projets <span id="projetsAboDomaine<?php echo $domaine->id ?>" class="nbAbo nbAboProjet"><?php echo $domaine->nbProjetsAbo ?></span>/<?php echo $domaine->nbProjets ?></span>
                                                        </span> 
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
                                                                    <div class="panel panel-default" id="enteteSousDomaine<?php echo $sousDomaine->id ?>">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">
                                                                                <a href="#panelSousDomaine<?php echo $sousDomaine->id ?>" data-toggle="collapse" data-parent="#sousDomainesDomaine<?php echo $domaine->id ?>" class="lienPanel" title="<?php echo $sousDomaine->description ?>"> <?php echo $sousDomaine->libelle ?> </a>
                                                                                <a href="#" class="pull-right abonner col-lg-2" id="sousDomaine-<?php echo $sousDomaine->id ?>">S'abonner <span class="glyphicon glyphicon-plus-sign"></span></a>
                                                                                <span class="pull-right etiquettes">
                                                                                    <span class="label label-default">Projets <span id="projetsAboSousDomaine<?php echo $sousDomaine->id ?>" class="nbAbo nbAboProjet"><?php echo $sousDomaine->nbProjetsAbo ?></span>/<?php echo $sousDomaine->nbProjets ?></span>
                                                                                </span> 
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
                                                                                            <div class="list-group-item" id="enteteProjet<?php echo $projet->id ?>">
                                                                                                <a href="#" title="<?php echo $projet->description ?>">
                                                                                                    <?php echo $projet->titre ?>
                                                                                                </a>
                                                                                                <a href="#" class="pull-right abonner" id="projet-<?php echo $projet->id ?>">S'abonner <span class="glyphicon glyphicon-plus-sign"></span></a>
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
            
            <div class="panel-group" id="contrats">
                <h3> Contrats</h3>
                <?php
                if(isset($contrats) && ($contrats != null))
                {
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a href="#listeContrats" data-toggle="collapse" data-parent="#contrats" class="lienPanel"> Liste des contrats </a>
                            </h3>
                        </div>
                        <div id="listeContrats" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <div class="list-group">
                                    <?php
                                    foreach($contrats as $contrat)
                                    {
                                        ?>
                                        <div class="list-group-item" id="enteteContrat<?php echo $contrat->id ?>">
                                            <img width="50" height="50" src="<?php echo $contrat->miniature->url ?>" />
                                            <a href="#">
                                                <?php echo $contrat->libelle ?>
                                            </a>
                                            <a href="#" class="pull-right abonner" id="contrat-<?php echo $contrat->id ?>">S'abonner <span class="glyphicon glyphicon-plus-sign"></span></a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            
        </div>
        <br/>
        <br/>
        
        <!--  Footer -->

        <?php include("footer.php"); ?>
        <script src="js/myJs/mesAbonnements.js"></script>
        
    </body>
</html>