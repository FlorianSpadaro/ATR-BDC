<?php
    include("header.php");
    $projet = json_decode(getProjetById($_GET["id"]));
    $parents = json_decode(getSousDomainesDomainesSecteursByProjetId($_GET["id"]));
?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Projet - <?php echo $projet->titre ?></title>

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
            #titrePj {
                text-align: center;
            }
            
            #piecesJointes {
                text-align: center;
            }

        </style>

    </head>
    
    <body>
        <div class="modal fade" id="infos">
            <div class="modal-dialog">  
              <div class="modal-content"></div>  
            </div> 
          </div>
        
         <header class="intro-header" style="background-image: url('img/Full-HD-Wallpapers-5A9.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="post-heading">
                            <h1><?php echo $projet->titre ?></h1>
                            <h2 class="subheading">
                                <?php 
                                if(isset($projet->description) && ($projet->description != null))
                                {
                                    echo $projet->description;
                                }
                                ?>
                            </h2>
                            <span class="meta">
                                Projet (<?php echo $parents->secteur->libelle ?> <span class="glyphicon glyphicon-triangle-right"></span> <?php echo $parents->domaine->libelle ?> <span class="glyphicon glyphicon-triangle-right"></span> <?php echo $parents->sousDomaine->libelle ?>)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <article>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <?php echo $projet->contenu ?>
                    </div>
                </div>
            </div>
        </article>
        
        
        <hr>

        <!-- Footer -->
        <footer>
            <h3 id="titrePj">Pièces jointes</h3>
            <?php
                    $piecesJointes = json_decode(getPiecesJointesByProjetId($projet->id));
                    
                    if($piecesJointes !== null)
                    {
                        $pdf = "pdf";
                        $texte = array("doc", "docx", "txt");
                        $excel = array("xls", "xlsx");
                        $powerpoint = array("ppt", "pptx");
                        $image = array("jpg", "jpeg", "gif", "png");
                        ?>
                        <div id="piecesJointes" class="container">
                            <?php
                            foreach($piecesJointes as $pj)
                            {
                                $extension_upload = $pj->extension;
                                ?>
                        <div id="element" class="col-xs-12 col-lg-3 col-md-4 col-sm-6">
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
                                                    <img src="<?php echo $pj->libelle ?>" width="50" height="50" class="imgPj" />
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
                            <span class="col-xs-offset-3 col-xs-6 label label-info">Aucune pièce jointe n'est liée à ce projet</span>
                        </div>
                    </div>
                    <?php
                }
                    ?>
        </footer>
        <?php include("footer.php") ?>

    </body>
</html>