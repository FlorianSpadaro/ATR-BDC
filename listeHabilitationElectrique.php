<?php
    include("header.php");
    if(!isset($_SESSION["niveau"]) || $_SESSION["niveau"]->niveau != 3)
    {
        header('Location: index.php');
        exit();
    }
    $listeFormulaires = json_decode(getFichesHabilitationsElectriquesAValider());
<<<<<<< HEAD
=======
    $listeFormulairesValides = json_decode(getFichesHabilitationsElectriquesValidees());
    $listeFormulairesRefuses = json_decode(getFichesHabilitationsElectriquesRefusees());
>>>>>>> 00b6ebfca65d48959f11399a6a0ccfc2405c6b68
?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Liste des habilitations électriques</title>

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
                            <h1>Liste des habilitations électriques</h1>
                            <hr class="small">
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
<<<<<<< HEAD
            <!--<button data-toggle="modal" href="#nouvelUtilisateur" class="btn btn-success" id="btnNouvelUtilisateur"><span class="glyphicon glyphicon-plus"></span> Ajouter utilisateur</button>
            <br/><br/>-->
            <!--<div class="help-block pull-right">
                Vous ne pouvez supprimer que les utilisateurs inactifs (un utilisateur devient actif lors de sa première connexion)
            </div>-->
            <table id="listeHabilitations" class="tablesorter table table-striped table-hover"> 
=======

        <ul class="nav nav-pills container">
            <li class="active"><a href="#listeHabilitations" data-toggle="tab">En Attente</a></li>
            <li><a href="#listeHabilitationsValidees" data-toggle="tab">Validés</a></li>
            <li><a href="#listeHabilitationsRefusees" data-toggle="tab">Refusés</a></li>
        </ul>
        <div class="tab-content">
        <div id="listeHabilitations" class="tab-pane active fade in">
            <table id="tableListeHabilitations" class="tablesorter table table-striped table-hover"> 
>>>>>>> 00b6ebfca65d48959f11399a6a0ccfc2405c6b68
                <thead> 
                <tr> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Nom</a></th>
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Date</a></th> 
                    <th>Voir</th> 
                </tr> 
                </thead> 
                <tbody>
                    <?php
                    if(sizeof($listeFormulaires) > 0)
                    {
                        foreach($listeFormulaires as $formulaire)
                        {
                            $utilisateur = json_decode(getUtilisateurById($formulaire->utilisateur_id));
                            $date = json_decode(modifierDate($formulaire->date));
                            ?>
                            <tr>
                                <td><?php echo strtoupper($utilisateur->nom)." ".ucfirst(strtolower($utilisateur->prenom)) ?></td>
                                <td><?php echo $date->jour." à ".$date->heure ?></td>
<<<<<<< HEAD
                                <td><button class="btn btn-success">Voir</button></td>
=======
                                <td><a href="validationHabiliteElectrique.php?id=<?php echo $formulaire->id ?>"><button class="btn btn-success">Voir</button></a></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody> 
            </table>
        </div>
        <div id="listeHabilitationsValidees" class="tab-pane fade">
            <table class="tablesorter table table-striped table-hover"> 
                <thead> 
                <tr> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Nom</a></th>
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Date</a></th> 
                    <th>Voir</th> 
                </tr> 
                </thead> 
                <tbody>
                    <?php
                    if(sizeof($listeFormulairesValides) > 0)
                    {
                        foreach($listeFormulairesValides as $formulaire)
                        {
                            $utilisateur = json_decode(getUtilisateurById($formulaire->utilisateur_id));
                            $date = json_decode(modifierDate($formulaire->date));
                            ?>
                            <tr>
                                <td><?php echo strtoupper($utilisateur->nom)." ".ucfirst(strtolower($utilisateur->prenom)) ?></td>
                                <td><?php echo $date->jour." à ".$date->heure ?></td>
                                <td><a href="validationHabiliteElectrique.php?id=<?php echo $formulaire->id ?>"><button class="btn btn-success">Voir</button></a></td>
>>>>>>> 00b6ebfca65d48959f11399a6a0ccfc2405c6b68
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody> 
            </table> 
        </div>
<<<<<<< HEAD
=======
        <div id="listeHabilitationsRefusees" class="tab-pane fade">
            <table id="tableListeHabilitationsRefusees" class="tablesorter table table-striped table-hover"> 
                <thead> 
                <tr> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Nom</a></th>
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Date</a></th> 
                    <th>Voir</th> 
                </tr> 
                </thead> 
                <tbody>
                    <?php
                    if(sizeof($listeFormulairesRefuses) > 0)
                    {
                        foreach($listeFormulairesRefuses as $formulaire)
                        {
                            $utilisateur = json_decode(getUtilisateurById($formulaire->utilisateur_id));
                            $date = json_decode(modifierDate($formulaire->date));
                            ?>
                            <tr>
                                <td><?php echo strtoupper($utilisateur->nom)." ".ucfirst(strtolower($utilisateur->prenom)) ?></td>
                                <td><?php echo $date->jour." à ".$date->heure ?></td>
                                <td><a href="validationHabiliteElectrique.php?id=<?php echo $formulaire->id ?>"><button class="btn btn-success">Voir</button></a></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody> 
            </table>
        </div>
        </div>
        </div>
>>>>>>> 00b6ebfca65d48959f11399a6a0ccfc2405c6b68
  
        <!--  Footer -->

        <?php include("footer.php"); ?> 
        <script type="text/javascript" src="vendor/tablesort/jquery.tablesorter.min.js"></script>
        <script src="js/myJs/listeHabilitationElectrique.js"></script>
    </body>
</html>