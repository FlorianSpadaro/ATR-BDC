<?php
    include("header.php");
    if(!isset($_SESSION["niveau"]) || $_SESSION["niveau"]->niveau != 3)
    {
        header('Location: index.php');
        exit();
    }
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

        <title>Gérer droits</title>

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
                            <h1>Gérer droits</h1>
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
            <table id="listeFonctions" class="tablesorter table table-striped table-hover"> 
                <thead> 
                <tr> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Fonction</a></th> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Niveau</a></th>
                </tr> 
                </thead> 
                <tbody>
                    <?php
                    if($fonctions != null)
                    {
                        foreach($fonctions as $fonction)
                        {
                            ?>
                            <tr>
                                <td><?php echo $fonction->libelle ?></td>
                                <td>
                                    <select id="fonction-<?php echo $fonction->id ?>" class="listeNiveaux" name="fonction-<?php echo $fonction->id ?>">
                                        <?php
                                        if($niveaux != null)
                                        {
                                            foreach($niveaux as $niveau)
                                            {
                                                ?>
                                                <option value="niveau-<?php echo $niveau->niveau ?>" <?php if($fonction->niveau->id == $niveau->niveau){ echo "selected"; } ?> ><?php echo $niveau->libelle.' ('.$niveau->niveau.')' ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody> 
            </table> 
        </div>
        
        
        <!--  Footer -->

        <?php include("footer.php"); ?> 
        <script type="text/javascript" src="vendor/tablesort/jquery.tablesorter.min.js"></script>
        <script src="js/myJs/gererDroits.js"></script>
    </body>
</html>