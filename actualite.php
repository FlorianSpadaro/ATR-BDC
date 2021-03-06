<?php
    include("header.php");
    $actualite = json_decode(getActualiteById($_GET["id"]));
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Actualité -
            <?php echo $actualite->titre ?>
        </title>

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
            
            #btnSupprimerActu{
                color: red;
            }
        </style>

    </head>

    <body>
        
        <input type="hidden" id="idActu" name="idActu" value="<?php echo $_GET["id"] ?>" />
        
        <div class="modal fade" id="infos">
            <div class="modal-dialog">  
              <div class="modal-content"></div>  
            </div> 
          </div>

        <!-- Page Header -->
        <!-- Set your background image for this header on the line below. -->
        <header class="intro-header" style="background-image: url('<?php echo $actualite->image_entete ?>')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="post-heading">
                            <h1><?php echo $actualite->titre ?></h1>
                            <h2 class="subheading"><?php echo $actualite->description ?></h2>
                            <span class="meta">Posté par <a data-toggle="modal" href="infosUtilisateur.php?id=<?php echo $actualite->utilisateur->id ?>" data-target="#infos"><?php echo strtoupper($actualite->utilisateur->nom)." ".strtoupper(substr($actualite->utilisateur->prenom, 0, 1)).strtolower(substr($actualite->utilisateur->prenom, 1)) ?></a> le <?php $date = json_decode(modifierDate($actualite->date_creation)); echo $date->jour." à ".$date->heure ?> 
                            <?php
                            if($actualite->date_creation != $actualite->date_derniere_maj)
                            {
                                ?>
                                (MAJ le <?php $date = json_decode(modifierDate($actualite->date_derniere_maj)); echo $date->jour." à ".$date->heure ?>)<br/><br/>
                                <?php
                            }
                            ?>
                            <br/>
                            <!--<b>Catégorie: </b><?php
                            /*if($actualite->secteur != null)
                            {
                                echo $actualite->secteur->libelle;
                            }
                            else if($actualite->domaine != null){
                                echo $actualite->domaine->secteur->libelle." > ".$actualite->domaine->libelle;
                            }
                            else if($actualite->sous_domaine != null){
                                echo $actualite->sous_domaine->domaine->secteur->libelle." > ".$actualite->sous_domaine->domaine->libelle." > ".$actualite->sous_domaine->libelle;
                            }
                            else if($actualite->projet != null)
                            {
                                echo $actualite->projet->sous_domaine->domaine->secteur->libelle." > ".$actualite->projet->sous_domaine->domaine->libelle." > ".$actualite->projet->sous_domaine->libelle." > ".$actualite->projet->titre;
                            }
                            else{
                                echo "Aucun";
                            }
                            if($actualite->contrat != null)
                            {*/
                                ?>
                                <br/>
                                <?php
                            //}
                            ?>-->
                        </span>
                        <div class="btn-group">
                            <a href="modifierActualite.php?id=<?php echo $_GET["id"] ?>"><button class="btn btn-link">Modifier actualite</button></a>
                            <button id="btnSupprimerActu" class="btn btn-link pull-right">Supprimer actualite</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Post Content -->
        <article>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <?php echo $actualite->contenu ?>

                            <!--<p>Never in all their history have men been able truly to conceive of the world as one: a single sphere, a globe, having the qualities of a globe, a round earth in which all the directions eventually meet, in which there is no center because every point, or none, is center — an equal earth which all men occupy as equals. The airman's earth, if free men make it, will be truly round: a globe in practice, not in theory.</p>

                    <p>Science cuts two ways, of course; its products can be used for both good and evil. But there's no turning back from science. The early warnings about technological dangers also come from science.</p>

                    <p>What was most significant about the lunar voyage was not that man set foot on the Moon but that they set eye on the earth.</p>

                    <p>A Chinese tale tells of some men sent to harm a young girl who, upon seeing her beauty, become her protectors rather than her violators. That's how I felt seeing the Earth for the first time. I could not help but love and cherish her.</p>

                    <p>For those who have seen the Earth from space, and for the hundreds and perhaps thousands more who will, the experience most certainly changes your perspective. The things that we share in our world are far more valuable than those which divide us.</p>

                    <h2 class="section-heading">The Final Frontier</h2>

                    <p>There can be no thought of finishing for ‘aiming for the stars.’ Both figuratively and literally, it is a task to occupy the generations. And no matter how much progress one makes, there is always the thrill of just beginning.</p>

                    <p>There can be no thought of finishing for ‘aiming for the stars.’ Both figuratively and literally, it is a task to occupy the generations. And no matter how much progress one makes, there is always the thrill of just beginning.</p>

                    <blockquote>The dreams of yesterday are the hopes of today and the reality of tomorrow. Science has not yet mastered prophecy. We predict too much for the next year and yet far too little for the next ten.</blockquote>

                    <p>Spaceflights cannot be stopped. This is not the work of any one man or even a group of men. It is a historical process which mankind is carrying out in accordance with the natural laws of human development.</p>

                    <h2 class="section-heading">Reaching for the Stars</h2>

                    <p>As we got further and further away, it [the Earth] diminished in size. Finally it shrank to the size of a marble, the most beautiful you can imagine. That beautiful, warm, living object looked so fragile, so delicate, that if you touched it with a finger it would crumble and fall apart. Seeing this has to change a man.</p>

                    <a href="#">
                        <img class="img-responsive" src="img/post-sample-image.jpg" alt="">
                    </a>
                    <span class="caption text-muted">To go places and do things that have never been done before – that’s what living is all about.</span>

                    <p>Space, the final frontier. These are the voyages of the Starship Enterprise. Its five-year mission: to explore strange new worlds, to seek out new life and new civilizations, to boldly go where no man has gone before.</p>

                    <p>As I stand out here in the wonders of the unknown at Hadley, I sort of realize there’s a fundamental truth to our nature, Man must explore, and this is exploration at its greatest.</p>

                    <p>Placeholder text by <a href="http://spaceipsum.com/">Space Ipsum</a>. Photographs by <a href="https://www.flickr.com/photos/nasacommons/">NASA on The Commons</a>.</p>-->
                    </div>
                </div>
            </div>
        </article>

        <hr>

        <!-- Footer -->
        <footer>
            <h3 id="titrePj">Pièces jointes</h3>
            <?php
                    $piecesJointes = json_decode(getPiecesJointesByActualiteId($actualite->id));
                    
                    if($piecesJointes !== null)
                    {
                        $pdf = "pdf";
                        $texte = array("doc", "docx", "txt");
                        $excel = array("xls", "xlsx");
                        $powerpoint = array("ppt", "pptx");
                        $image = array("jpg", "jpeg", "gif", "png");
                        ?>
                <div id="piecesJointes" class="container jumbotron">
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
                            <span class="col-xs-offset-3 col-xs-6 label label-info">Aucune pièce jointe n'est liée à cette actualité</span>
                        </div>
                    </div>
                    <?php
                }
                    ?>
        </footer>
        <?php include("footer.php") ?>
            <script src="js/myJs/actualite.js"></script>

    </body>

    </html>
