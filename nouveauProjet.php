<?php
    include("header.php");
    if(!isset($_SESSION["niveau"]) || $_SESSION["niveau"]->niveau != 3)
    {
        header('Location: index.php');
        exit();
    }
    $listeSecteurs = json_decode(getSecteursDomainesSousdomaines());
    
?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Nouveau projet</title>

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
        <link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
        <link href="bootstrap-toggle-master/css/bootstrap-toggle.min.css" rel="stylesheet">
        
        <style>
            .radio{
                display: inline-block;
            }
            #divProjetGenerique, #divProjetSpecifique, #waitValider{
                display: none;
            }
            #divEnvoiMail{
                text-align: right;
            }
        </style>
    </head>
    
    <body>

        <div id="listeSecteurs" style="display:none"><?php echo json_encode($listeSecteurs) ?></div>

        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>Nouveau projet</h1>
                            <hr class="small">
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
            <form id="form1">
                <div class="form-group">
                    <div class="form-group">
                        <label>Choisir un secteur</label>
                        <select name="secteurProjet" id="secteurProjet" class="form-control">
                            <?php
                                foreach($secteurs as $secteur)
                                {
                                    ?>
                                    <option value="<?php echo $secteur->id ?>"><?php echo $secteur->libelle ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                    <div id="radioTypeDossier">
                        <div class="radio well">
                          <label><input type="radio" id="projetGenerique" value="projetGenerique" name="typeProjet">Créer un article générique</label>
                        </div>
                        <div class="radio well">
                          <label><input class="typeProjet" type="radio" id="projetSpecifique" value="projetSpecifique" name="typeProjet">Créer un article spécifique</label>
                        </div>
                    </div>
                    
                    <div id="divProjetGenerique" class="divRadio">
                        <div class="form-group">
                            <label>Sélectionner un ou plusieurs domaines</label>
                            <select name="domainesProjet" id="domainesProjet" class="form-control" multiple>
                            </select>
                        </div>
                    </div>
                    <div id="divProjetSpecifique" class="divRadio">
                        <div class="form-group">
                            <label>Sélectionner un sous-domaine</label>
                            <select id="sousDomaineProjet" name="sousDomaineProjet" class="form-control">
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="titreNouveauProjet" id="titreNouveauProjet" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Description (facultatif)</label>
                    <input type="text" name="descriptionNouveauProjet" id="descriptionNouveauProjet" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Image d'en-tête (facultatif)</label>
                    <input type="file" class="form-control" id="imageEnteteNouveauProjet" name="imageEnteteNouveauProjet" />
                </div>
                <div class="form-group">
                    <label>Contenu</label>
                    <textarea id="summernote" required>
                    </textarea>
                </div>
            </form>
            <label>Pièces jointes (facultatif)</label>
            <div action="API/addPjProjet.php" class="dropzone" id="form2">
            </div>
            <br/>
            <div class="form-group" id="divEnvoiMail">
                <label for="envoiMail">Envoi de mails <input type="checkbox" name="envoiMail" id="envoiMail" checked></label>
            </div>
            <div class="form-group pull-right">
                <button class="btn btn-link" id="btnReinitialiser">Réinitialiser</button>
                <button class="btn btn-default" id="validerNouveauProjet">Valider</button>
                <img src="img/wait.gif" id="waitValider" />
            </div>

        </div>
        
        <!--  Footer -->

        <?php include("footer.php"); ?>
        
        <script src="js/dropzone.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>
        <script src="js/summernote-fr-FR.js"></script>
        <script src="bootstrap-toggle-master/js/bootstrap-toggle.min.js"></script>
        
        <script src="js/myJs/nouveauProjet.js"></script>
    </body>
</html>