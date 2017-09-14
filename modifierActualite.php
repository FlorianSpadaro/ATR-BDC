<?php
    include("header.php");
    $actu = json_decode(getActualiteById($_GET["id"]));
?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Modification actualité</title>

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
        <style>
            #piecesJointes{
                display: flex;
                justify-content: space-around;
            }
            .element{
                text-align: center;
            }
            #pjActuellesSuppr{
                display: none;
            }
        </style>
    </head>
    
    <body>
        
        <input type="hidden" name="idActu" id="idActu" value="<?php echo $_GET["id"] ?>" />
        
        <header class="intro-header" style="background-image: url('<?php echo $actu->image_entete ?>')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>Modification actualité</h1>
                            <hr class="small">
                            <h4><?php echo $actu->titre ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
            <form id="form1">
                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="titreNouvelleActu" id="titreNouvelleActu" class="form-control" value="<?php echo $actu->titre ?>" required />
                </div>
                <div class="form-group">
                    <label>Description (facultatif)</label>
                    <input type="text" name="descriptionNouvelleActu" id="descriptionNouvelleActu" class="form-control" value="<?php if($actu->description != null){ echo $actu->description; } ?>" />
                </div>
                <div class="form-group">
                    <label>Image d'en-tête</label>
                    <input type="file" class="form-control" id="imageEnteteNouvelleActu" name="imageEnteteNouvelleActu" />
                    <div class="help-block">Info: Cela remplacera l'ancienne image d'en-tête</div>
                </div>
                <div class="form-group">
                    <label>Contenu</label>
                    <textarea id="summernote" required>
                    </textarea>
                </div>
            </form>
            <label>Pièces jointes</label>
            <div action="API/addPjActualite.php" class="dropzone" id="form2">
            </div>
            <br/>
            <div class="form-group">
                <label>Pièces jointes actuelles</label>
                <div class="row">
                    <?php
                    $piecesJointes = json_decode(getPiecesJointesByActualiteId($actu->id));
                    
                    if($piecesJointes !== null)
                    {
                        $pdf = "pdf";
                        $texte = array("doc", "docx", "txt");
                        $excel = array("xls", "xlsx");
                        $powerpoint = array("ppt", "pptx");
                        $image = array("jpg", "jpeg", "gif", "png");
                        ?>
                <div id="piecesJointes" class="container jumbotron">
                    <label class="label label-info" id="pjActuellesSuppr">Aucune pièce-jointe</label>
                    <?php
                            foreach($piecesJointes as $pj)
                            {
                                $extension_upload = $pj->extension;
                                ?>
                        <div class="element well">
                            <input type="hidden" id="pjConserve-<?php echo $pj->id ?>" name="pjConserve-<?php echo $pj->id ?>" class="pjConserve" value="<?php echo $pj->id ?>" />
                            <button type="button" id="pj-<?php echo $pj->id ?>" class="close btnSuprPj" data-dismiss="modal">x</button>
                            <a href="<?php echo $pj->url ?>">
                                <?php
                                    if($extension_upload == $pdf)
                                    {
                                        ?>
                                    <img src="images/pdf.png" width="50" height="50" class="imgPj" />
                                    <br/>
                                    <?php
                                        echo $pj->libelle;
                                    }
                                    else if(in_array($extension_upload, $texte))
                                    {
                                        ?>
                                        <img src="images/word.png" width="50" height="50" class="imgPj" />
                                        <br/>
                                        <?php
                                        echo $pj->libelle;
                                    }
                                    else if(in_array($extension_upload, $excel))
                                    {
                                        ?>
                                            <img src="images/excel.png" width="50" height="50" class="imgPj" />
                                            <br/>
                                            <?php
                                        echo $pj->libelle;
                                    }
                                    else if(in_array($extension_upload, $powerpoint))
                                    {
                                        ?>
                                                <img src="images/powerpoint.png" width="50" height="50" class="imgPj" />
                                                <br/>
                                                <?php
                                        echo $pj->libelle;
                                    }
                                    else if(in_array($extension_upload, $image))
                                    {
                                        ?>
                                                    <img src="<?php echo $pj->url ?>" width="50" height="50" class="imgPj" />
                                                    <br/>
                                                    <?php
                                        echo $pj->libelle;
                                    }
                                    ?>
                            </a>
                        </div>
                        <?php

                            }
                            ?>
                </div>
                <?php
                    }
                else{
                    ?>
                    <div class="container">
                        <div class="row">
                            <span class="label label-info">Aucune pièce jointe n'est actuellement liée à cette actualité</span>
                        </div>
                    </div>
                    <?php
                }
                    ?>
                </div>
            </div>
            <br/>
            <div class="form-group pull-right">
                <button class="btn btn-link" id="btnReinitialiser">Réinitialiser</button>
                <button class="btn btn-default" id="validerNouvelleActu">Valider</button>
            </div>
        </div>
        
        
        <!--  Footer -->

        <?php include("footer.php"); ?>
        
        <script src="js/dropzone.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>
        <script src="js/summernote-fr-FR.js"></script>
        
        <script src="js/myJs/modifierActualite.js"></script>
        
    </body>
</html>