<?php
    include("header.php");
    $domaine = json_decode(getDomaineById($_GET["id"]));
    $sousDomaines = json_decode(getProjetsBySousDomaineByDomaineId($_GET["id"]));
?>
    <!DOCTYPE html>
    <html>

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>
            <?php echo $domaine->libelle ?>
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

    </head>

    <body>
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 ">
                        <div class="site-heading">
                            <h1>
                                <?php echo $domaine->libelle ?>
                            </h1>
                            <h2 class="subheading">
                                Domaine du secteur
                                <?php echo $domaine->secteur->libelle ?>
                            </h2>
                            <span class="meta">
                                <?php 
                                if($domaine->description != null)
                                {
                                    echo $domaine->description;
                                }
                                ?>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </header>

            <div class="container">
              <div id="monaccordeon" class="panel-group">
                <h3>Sous-domaines</h3>
                  <?php
                  foreach($sousDomaines as $sd)
                  {
                      ?>
                    <div class="panel panel-default">
                      <div class="panel-heading"> 
                        <h3 class="panel-title">
                          <a href="#sd<?php echo $sd->id ?>" data-parent="#monaccordeon" data-toggle="collapse" title="<?php if(isset($sd->description) && ($sd->description != null)){echo $sd->description;} ?>" class="click"><span class="badge pull-right">
                    <?php 
                    if(isset($sd->projets) && ($sd->projets != null))
                    {
                        $nb = 0;
                        foreach($sd->projets as $proj)
                        {
                            $nb++;
                        }
                        echo $nb;
                    }
                    else{
                        echo "0";
                    }
                    ?>
                    </span> <?php echo $sd->libelle ?></a> 
                        </h3>
                      </div>
                      <div id="sd<?php echo $sd->id ?>" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <?php
                            if(isset($sd->projets) && ($sd->projets != null))
                            {
                                ?>
                                <div class="list-group">
                                <?php
                                foreach($sd->projets as $projet)
                                {
                                    ?>
                                    <a href="projet.php?id=<?php echo $projet->id ?>" class="list-group-item" title="<?php echo $projet->description ?>"><?php echo $projet->titre ?></a>
                                    <?php
                                }
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
    </body>


    <!--  Footer -->

    <?php include("footer.php"); ?>
    <script src="js/myJs/domaine.js"></script>

    </html>
