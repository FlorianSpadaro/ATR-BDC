<?php
    session_start();
    include("API/fonctions.php");
    if(isset($_POST["deconnexion"]) && $_POST["deconnexion"] == "true")
    {
        session_destroy();
        header('Location: index.php');
        exit();
    }
    if(isset($_POST["user_id"]) && $_POST["user_id"] != null)
    {
        $_SESSION["user_id"] = $_POST["user_id"];
        $_SESSION["niveau"] = json_decode(getNiveauByUtilisateurId($_POST["user_id"]));
    }
    $secteurs = json_decode(getSousDomainesByDomainesBySecteurs());
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

        <!--<title>Accueil</title>-->

        <!-- Bootstrap Core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Theme CSS -->
        <link href="css/clean-blog.min.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="bootstrap-toggle-master/css/bootstrap-toggle.min.css" >
         <link rel="stylesheet" href="bootstrap-chosen-master/bootstrap-chosen.css" >
        
       


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
        <style>
            #totalNotif{
                display: none;
            }
            .newDomaine{
                background-color: burlywood;
            }
            #searchBarOption
            {
                width: 779px;
                position: absolute;
                top: 47px;
                left: -38px;
                overflow: hidden;
            }
                        .searchOptionProjet{
                font-style: italic;
                font-size:px;
            }
                        .noResultOption
            {
            color:#c10000;
            font-weight: bold;
            }
            .searchOption
            {
                border-bottom:1px #e4e4e4 dashed;
                padding-top:2px;
                padding-bottom:2px;
                cursor:pointer;
            }
            .searchOption:hover
            {
                background-color:#e4e4e4;
            }
            .noResultOption:hover
            {
                background-color:white;
            }
            #searchBar
            {
                margin-top:13px;
                margin-right:20px;
                width:200px;
                border-top-right-radius:7px;
                border-bottom-right-radius:7px;
                border:rgb(230, 233, 234) 1px solid;
                border-left:none;
                outline-style:none;
                box-shadow:none;
            }
            #searchBarIcon
            {

                background-color:white;
                border:#e6e8e8 1px solid;
                border-right:none;
                border-radius:0;
                position:absolute;
                left:-38px;
                top:13px;
                height:34px;
                border-top-left-radius:7px;
                border-bottom-left-radius:7px;
                box-shadow
            }   
        </style>

    </head>

    <body>
        <?php 
        if(isset($_SESSION["user_id"]))
        {
            ?>
            <div class="modal fade" id="infos<?php echo $_SESSION["user_id"] ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                    </div>
                </div>
            </div>
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION["user_id"] ?>" />
            <?php
        }
        ?>
        <div>
        </div>
        <div class="modal" id="divNouveauDomaine">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Nouveau domaine</h4>
              </div>
              <div class="modal-body">
                <form>
                    <input type="hidden" name="idSecteurNewDomaine" id="idSecteurNewDomaine"  />
                    <div class="form-group">
                        <label>Libellé</label>
                        <input type="text" class="form-control" name="libelleNewDomaine" id="libelleNewDomaine" required />
                    </div>
                    <div class="form-group">
                        <label>Description (facultatif)</label>
                        <input type="text" class="form-control" name="descriptionNewDomaine" id="descriptionNewDomaine" />
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                  <div class="btn-group">
                      <button class="btn btn-danger" id="btnAnnulerNewDomaine" data-dismiss="modal">Annuler</button>
                      <button class="btn btn-info" id="btnValiderNewDomaine" data-dismiss="modal">Valider</button>
                  </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="connexion">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Connexion</h4>
                    </div>
                    <div class="modal-body">
                        <form id="formConnexion" method="post" action="#">
                            <div class="form-group">
                                <label for="login">Login</label>
                                <input type="text" class="form-control" name="login" id="login" placeholder="Votre login" required>
                            </div>
                            <div class="form-group">
                                <label for="mdp">Mot de passe</label>
                                <input type="password" class="form-control" name="mdp" id="mdp" placeholder="Votre mot de passe" required>
                            </div>

                            <input type="hidden" name="user_id" id="user_id" value="" />

                            <button id="boutonConnexion" class="btn btn-default">Connexion</button>
                            <img id="attenteConnexion" src="img/wait.gif">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <label id="connexionFooter" class="label-control"></label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header page-scroll" id="barreNavigation">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                    </button>

                    <?php
                        if(isset($_SESSION["user_id"]) && $_SESSION["user_id"] != null && !isset($_POST["deconnexion"]))
                        {
                            ?>
                        <span class="dropdown">
                                <a class="navbar-brand" id="monCompte" href="#" data-toggle="dropdown">Mon Compte <span id="totalNotif" class="label label-danger"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="infosUtilisateur.php?id=<?php echo $_SESSION["user_id"] ?>" data-toggle='modal' data-target="#infos<?php echo $_SESSION["user_id"] ?>"><span class="glyphicon glyphicon-user"></span> Informations Personnelles</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="mesNotifications.php"><span class="glyphicon glyphicon-tags"></span> Notifications <span id="nbNotifs" class="badge">0</span></a></li>
                        <li><a href="mesMessages.php"><span class="glyphicon glyphicon-envelope"></span> Mes messages <span id="nbMessages" class="badge"></span></a></li>
                        <li><a href="mesAbonnements.php"><span class="glyphicon glyphicon-heart-empty"></span> Mes abonnements</a></li>
                        <!--<li><a href="#"><span class="fa fa-question-circle"></span> Mes suggestions</a></li>-->
                        <?php
                        if((isset($_SESSION["niveau"])) && ($_SESSION["niveau"]->niveau == 3))
                        {
                            ?>
                            <li><a href="listeUtilisateurs.php"><span class="glyphicon glyphicon-list-alt"></span> Liste des utilisateurs</a></li>
                            <?php
                        }
                        ?>
                        <li class="divider"></li>
                        <li>
                            <form method="post">
                                <input type="hidden" name="deconnexion" value="true">
                                <button class="btn btn-link">
                                <span class="glyphicon glyphicon-off"></span>   Déconnexion
                            </button>
                            </form>
                        </li>
                        </ul>
                        </span>

                        <?php
                        }
                        else{
                            ?>
                            <a class="navbar-brand" href="#connexion" data-toggle="modal" id="seConnecter">Se connecter</a>
                            <?php
                        }
                    ?>

                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                        <form class="form-inline" method="get" action="projets.php">
                            <div class="input-group">
                                <span class="input-group-addon" id="searchBarIcon"><span class="glyphicon glyphicon-search"></span></span>
                                <input id="searchBar" name="searchbar" list="searchBarOption" type="text" class="form-control" placeholder="Rechercher..." autocomplete="off">
                                <select id="searchBarOption" class="form-control searchSelector"></select>   
                            </div>
                        </form>
                                    
                        </li>
                        <li class="active">
                            <a href="index.php">Accueil</a>
                        </li>
                        <?php
                        foreach($secteurs as $secteur)
                        {
                            ?>
                            <li class="dropdown" id="secteur<?php echo $secteur->id ?>">
                                <a href="#" class="secteur dropdown-toggle" data-toggle="dropdown"><?php echo $secteur->libelle ?></a>
                                
                                <ul class="dropdown-menu">
                                    <?php
                                    if($secteur->id == 2)
                                    {
                                        ?>
                                    <li class="contratSelectHeader">

                                        <select name="listContratHeader" id="listContratHeader" multiple>
                                          
                                            <?php
                                            foreach($contrats as $contrat)
                                            {
                                                ?>
                                                
                                                <option value="<?php echo $contrat->id; ?>"><?php echo $contrat->libelle; ?></option><?php
                                            }
                                            ?>
                                        </select>
                                         
                                    </li>
                                        <?php
                                    }
                                    ?>
                                    
                                    <?php
                                    if($secteur->domaines != null)
                                    {
                                        foreach($secteur->domaines as $domaine)
                                        {
                                            ?>
                                            <li class="domaine_" id="domaine<?php echo $domaine->id ?>">
                                                <a tabindex="-1" href="domaine.php?id=<?php echo $domaine->id ?>"><?php echo $domaine->libelle ?></a>
                                                <ul class="dropdown-menu hidden-xs hidden-sm hidden-md hidden-lg">
                                                    <?php
                                                    if(isset($domaine->sous_domaines) && $domaine->sous_domaines != null)
                                                    {
                                                        foreach($domaine->sous_domaines as $sousDomaine)
                                                        {
                                                            ?>
                                                            <li id="sousDomaine<?php echo $sousDomaine->id ?>"><a tabindex="-1" href="#"><?php echo $sousDomaine->libelle ?></a></li>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </li>
                                            <?php
                                        }
                                        if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]->niveau == 3)
                                            {
                                                ?>
                                                <li class="newDomaine" id="newDomaineSecteur-<?php echo $secteur->id ?>">
                                                    <a data-toggle="modal" href="#divNouveauDomaine" tabindex="-1"><span class="glyphicon glyphicon-plus"></span> Ajouter domaine</a>
                                                </li>
                                                <?php
                                            }
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                                <!--<li class="dropdown">
                            <a href="about.html" data-toggle="dropdown">Cuivre</a>
                        </li>
                        <li>
                            <a href="post.html">FTTH</a>
                        </li>
                        <li>
                            <a href="contact.html">Vie de l'entreprise</a>
                        </li>-->
                                <li>
                                    <a href="projets.php">Projets</a>
                                </li>
                        <?php
                        if((isset($_SESSION["niveau"])) && ($_SESSION["niveau"]->niveau == 3))
                        {
                            ?>
                            <li>
                                <a href="contrats.php">Contrats</a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

        <!-- Page Header -->
        <!-- Set your background image for this header on the line below. -->

    </body>
    </html>
