<?php
    include("header.php");
    
    $search = null;
    $params = null;
    if(isset($_POST["params"]) && ($_POST["params"] != null))
    {
        $params = json_decode($_POST["params"]);
    }
    else{
        if(isset($_GET["searchbar"]) && ($_GET["searchbar"] != null))
        {
            $search = $_GET["searchbar"];
        }
        else if(isset($_GET["params"]) && ($_GET["params"] != null)){
            $params = json_decode(urldecode($_GET["params"]));
        }
    }

    $nbProjetsAfficher = 10;
    $nbProjets = json_decode(getNbProjets($params));
    
    $nbPages = ceil($nbProjets/$nbProjetsAfficher);
    
    if((!isset($_GET["p"])) || ($_GET["p"] == null) || ($_GET["p"]== "") || ($_GET["p"] < 1))
    {
        $_GET["p"] = 1;
    }
    else if(isset($_GET["p"]) && ($_GET["p"] > $nbPages))
    {
        $_GET["p"] = $nbPages;
    }
    $debutProjets = ($_GET["p"]*$nbProjetsAfficher)-$nbProjetsAfficher;
    $projets = json_decode(getProjetsByNum($nbProjetsAfficher, $debutProjets, $params, $search));
?>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Projets</title>

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
        <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
        <style>
            #filtreListeTypes a{
                border: 1px solid black;
            }
            .panel-footer{
                text-align: right;
            }
            #formNumPage{
                display: none;
            }
            #btnNum{
                width: 50px;
                height: 50px;
                border-radius: 25px;
                text-align: left;
            }
            #rechercheProjet{
                display: none;
            }
            #nouveauProjet{
                color: green;
            }
        </style>
        
    </head>
    <body>
        
        <input type="hidden" id="nbPages" value="<?php echo $nbPages ?>" />
        
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <?php 
                            if(!isset($_GET["searchbar"]))
                            {
                                echo "<h1>Liste des Projets</h1>";
                            }
                            else
                            {
                                echo "<h1>Recherche</h1><h2><i>'".$_GET['searchbar']."'</i></h2>";
                            }
                            ?>
                            <h1></h1>
                            <hr class="small">
                            <?php
                            if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]->niveau == 3)
                            {
                                ?>
                                <a href="nouveauProjet.php" id="nouveauProjet" class="btn btn-link">Créer un Nouveau Projet</a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
            
            <h3>
                <?php
                if(!isset($_GET["searchbar"]))
                {
                    ?>
                    <button id="btnFiltres" class="btn btn-info">Filtres <span class="glyphicon glyphicon-filter"></span></button>
                    <?php
                }
                ?>
                
                <form href="projets.php" method="post" class="form-horizontal" id="rechercheProjet">
                    <div id="listeHidden">
                        <input type="hidden" id="params" name="params" />
                    </div>
                    <div id="divFiltres" class="jumbotron">
                        <div class="form-group">
                            <div class="input-group">
                                <input id="inputRechercheProjet" type="search" class="form-control" placeholder="Rechercher..."><a id="validerRechercheProjet" class="input-group-addon" href="#"><span class="glyphicon glyphicon-search"></span></a>
                            </div>
                        </div>
                        <div class="form-group">
                            <legend>Zones de recherche</legend>
                            <fieldset>
                                <span class="checkbox col-lg-3 well">
                                    <label for="filtreTitreProjet" class="checkbox">
                                        <input type="checkbox" name="filtreTitreProjet" id="filtreTitreProjet" checked />
                                        Titre
                                    </label>
                                </span>
                                <span class="checkbox col-lg-3 col-lg-offset-1 well">
                                    <label for="filtreDescriptionProjet" class="checkbox">
                                        <input type="checkbox" name="filtreDescriptionProjet" id="filtreDescriptionProjet" checked />
                                        Description
                                    </label>
                                </span>
                                <span class="checkbox col-lg-3 col-lg-offset-1 well">
                                    <label for="filtreContenuProjet" class="checkbox">
                                        <input type="checkbox" name="filtreContenuProjet" id="filtreContenuProjet" checked />
                                        Contenu
                                    </label>
                                </span>
                            </fieldset>
                        </div>
                        <div class="form-group">
                            <legend>Filtres</legend>
                            <p class="help-block">Note: Par défaut, tous les filtres sont activés (sauf les contrats). Cliquez dessus pour les désactiver</p>
                            <div id="imageChargementFiltre" style="text-align: center">
                                <img src="img/loading.gif" />
                            </div>
                            <fieldset id="tousLesFiltres" style="display: none">
                                <ul id="entetesOnglets" class="nav nav-pills">
                                    <li class="active"><a id="enteteTypesFiltre" href="#filtreTypes" data-toggle="tab">Types</a></li>
                                    <li><a id="enteteSecteurFiltre" href="#filtreSecteurs" data-toggle="tab">Secteurs</a></li>
                                    <li><a href="#filtreDomaines" data-toggle="tab">Domaines</a></li>
                                    <li><a href="#filtreSousDomaines" data-toggle="tab">Sous-Domaines</a></li>
                                    <li><a href="#filtreContrats" data-toggle="tab">Contrats</a></li>
                                </ul>
                                <br/>
                                <div class="tab-content">
                                    <div class="tab-pane active in fade" id="filtreTypes">
                                        <div class="list-group" id="filtreListeTypes">
                                          <a id="type-generique" class="list-group-item active visible filtreTypeProjet" href="#">Projets Génériques</a>
                                          <a id="type-specifique" class="list-group-item active visible filtreTypeProjet" href="#">Projets Spécifiques</a>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="filtreSecteurs">
                                        <div class="list-group" id="filtreListeSecteurs">
                                          <label class="label label-info" id="labelSecteurFiltre">Aucun Secteur</label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="filtreDomaines">
                                        <div class="list-group" id="filtreListeDomaines">
                                            <label class="label label-info" id="labelDomaineFiltre">Aucun domaine ne correspond aux secteurs sélectionnés</label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="filtreSousDomaines">
                                        <div class="list-group" id="filtreListeSousDomaines">
                                            <label class="label label-info" id="labelSousDomaineFiltre">Aucun sous-domaine ne correspond aux secteurs et domaines sélectionnés</label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="filtreContrats">
                                        <div class="list-group" id="filtreListeContrats">
                                            <label class="label label-info" id="labelContratFiltre">Aucun contrat</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="btn-group pull-right">
                                <button type="reset" class="btn btn-danger" id="annulerFiltre">Annuler</button>
                                <button class="btn btn-default" id="validerFiltre" disabled>Valider</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                </form>
            </h3>
            <?php
            if($projets != null)
            {
                foreach($projets as $projet)
                {
                    ?>
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title">
                            <a href="projet.php?id=<?php echo $projet->id ?>"><?php echo $projet->titre ?></a>
                            <span class="pull-right">Type : <?php 
                                if($projet->sous_domaine_id != null)
                                {
                                    echo "Spécifique";
                                }
                                else{
                                    echo "Générique";
                                }
                                ?></span>
                          </h3>
                      </div>
                      <div class="panel-body">
                          <?php
                        if($projet->description != null)
                        {
                            echo $projet->description;
                        }
                    else{
                        ?>
                          <label class="label label-info">Ce projet n'a aucune description</label>
                        <?php
                    }
                        ?>
                        </div>
                      <div class="panel-footer"><?php echo $projet->date_creation->jour." ".$projet->date_creation->heure; 
                            if(($projet->date_derniere_maj != null) && ($projet->date_derniere_maj != $projet->date_creation->jour))
                            {
                                echo " (MAJ : ".$projet->date_derniere_maj->jour." ".$projet->date_derniere_maj->heure.")";
                            }
                          ?></div>
                    </div>
                    <?php
                }
            }
            else{
                ?>
                <div style="text-align: center">
                    <label class="label label-info">Aucun Projet</label>
                </div>
                <?php
            }
            ?>
            
            <hr>
            <?php
            if(!isset($_GET["searchbar"]))
            {
                ?>
                <div class="pull-right">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a id="btnNum" href="#" class="btn btn-lg btn-info">N°</a>
                            <form class="form-inline" id="formNumPage">
                                <input class="form-control" type="number" placeholder="n° page" id="numPage" required />
                            </form>
                        </li>
                        <?php
                        if($_GET["p"] != 1)
                        {
                            ?>
                            <li class="nav-item">
                                <div class="btn-group">
                                    <a href="projets.php?p=1<?php if($params != null){ echo "&amp;params=".urlencode(json_encode($params)); } ?>"><button class="btn btn-default"><span class="glyphicon glyphicon-fast-backward"></span></button></a>
                                    <a href="projets.php?p=<?php echo ($_GET["p"]-1); if($params != null){ echo "&amp;params=".urlencode(json_encode($params)); } ?>"><button class="btn btn-default"><span class="glyphicon glyphicon-backward"></span></button></a>
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if($nbPages < 12)
                        {
                            for($i = 1; $i <= $nbPages; $i++)
                            {
                                ?>
                                <li class="nav-item <?php if($_GET["p"] == $i){ echo "active"; } ?>">
                                    <a class="nav-link" href="projets.php?p=<?php echo $i; if($params != null){ echo "&amp;params=".urlencode(json_encode($params)); } ?>"><?php echo $i ?></a>
                                </li>
                                <?php
                            }
                        }
                        else{
                            if($_GET["p"] < 6)
                            {
                                for($i = 1; $i <= 10; $i++)
                                {
                                    ?>
                                    <li class="nav-item <?php if($_GET["p"] == $i){ echo "active"; } ?>">
                                        <a class="nav-link" href="projets.php?p=<?php echo $i; if($params != null){ echo "&amp;params=".urlencode(json_encode($params)); } ?>"><?php echo $i ?></a>
                                    </li>
                                    <?php
                                }
                            }
                            else if($_GET["p"] > ($nbPages-6))
                            {
                                for($i = ($nbPages-10); $i <= $nbPages; $i++)
                                {
                                    ?>
                                    <li class="nav-item <?php if($_GET["p"] == $i){ echo "active"; } ?>">
                                        <a class="nav-link" href="projets.php?p=<?php echo $i; if($params != null){ echo "&amp;params=".urlencode(json_encode($params)); } ?>"><?php echo $i ?></a>
                                    </li>
                                    <?php
                                }
                            }
                            else{
                                for($i = ($_GET["p"]-5); $i <= ($_GET["p"]+5); $i++)
                                {
                                    ?>
                                    <li class="nav-item <?php if($_GET["p"] == $i){ echo "active"; } ?>">
                                        <a class="nav-link" href="projets.php?p=<?php echo $i; if($params != null){ echo "&amp;params=".urlencode(json_encode($params)); } ?>"><?php echo $i ?></a>
                                    </li>
                                    <?php
                                }
                            }
                        }
                        ?>
                        <?php
                        if($_GET["p"] != $nbPages)
                        {
                            ?>
                            <li class="nav-item">
                                <div class="btn-group">
                                    <a href="projets.php?p=<?php echo ($_GET["p"]+1); if($params != null){ echo "&amp;params=".urlencode(json_encode($params)); } ?>"><button class="btn btn-default"><span class="glyphicon glyphicon-forward"></span></button></a>
                                    <a href="projets.php?p=<?php echo $nbPages; if($params != null){ echo "&amp;params=".urlencode(json_encode($params)); } ?>"><button class="btn btn-default"><span class="glyphicon glyphicon-fast-forward"></span></button></a>
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <br/>
                </div>
                <?php
            }
            ?>
            
        </div>
        
        <br/><br/>
        
        <!--  Footer -->

        <?php include("footer.php"); ?>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>
        <script src="js/myJs/projets.js"></script>
        
        
    </body>
</html>