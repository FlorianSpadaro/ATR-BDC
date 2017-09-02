<?php
    include("header.php");

    if((!isset($_GET["p"])) || ($_GET["p"] == null) || ($_GET["p"]== ""))
    {
        $_GET["p"] = 1;
    }
    $debutProjets = ($_GET["p"]*10)-10;
    $nbProjets = json_decode(getNbProjets());
    $nbProjetsAfficher = 10;
    $projets = json_decode(getProjetsByNum($nbProjetsAfficher, $debutProjets));
    $nbPages = ceil($nbProjets/$nbProjetsAfficher);
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
        <style>
            .panel-footer{
                text-align: right;
            }
        </style>
        
    </head>
    <body>
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>Liste des Projets</h1>
                            <hr class="small">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Rechercher..."><a class="input-group-addon" href="#"><span class="glyphicon glyphicon-search"></span></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
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
                            <span class="pull-right">Contrat : <?php 
                                if($projet->contrat != null)
                                {
                                    echo $projet->contrat->libelle;
                                }
                                else{
                                    echo "Aucun";
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
            ?>
            
            <hr>
            
            <div class="pull-right">
                <ul class="nav nav-pills">
                    <?php
                    if($_GET["p"] != 1)
                    {
                        ?>
                        <li class="nav-item">
                            <div class="btn-group">
                                <a href="projets.php?p=1"><button class="btn btn-default"><span class="glyphicon glyphicon-fast-backward"></span></button></a>
                                <a href="projets.php?p=<?php echo ($_GET["p"]-1) ?>"><button class="btn btn-default"><span class="glyphicon glyphicon-backward"></span></button></a>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    for($i = 1; $i <= $nbPages; $i++)
                    {
                        ?>
                        <li class="nav-item <?php if($_GET["p"] == $i){ echo "active"; } ?>">
                            <a class="nav-link" href="projets.php?p=<?php echo $i ?>"><?php echo $i ?></a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    if($_GET["p"] != $nbPages)
                    {
                        ?>
                        <li class="nav-item">
                            <div class="btn-group">
                                <a href="projets.php?p=<?php echo ($_GET["p"]+1) ?>"><button class="btn btn-default"><span class="glyphicon glyphicon-forward"></span></button></a>
                                <a href="projets.php"?p=<?php echo $nbPages ?>><button class="btn btn-default"><span class="glyphicon glyphicon-fast-forward"></span></button></a>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
        
        <br/><br/>
        
        <!--  Footer -->

        <?php include("footer.php"); ?>
        
        
    </body>
</html>