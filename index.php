<?php
    include("header.php");
?>

    <!DOCTYPE html>
    <html>

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Accueil</title>

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
        #searchBarOption
{
    width:741px;
    position:absolute;
    top:34px;
    overflow:hidden;
    
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

}
.searchOption:hover
{
    background-color:#e4e4e4;
 
}
.noResultOption:hover
{
    background-color:white;
}
        </style>
    </head>

    <body>

        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>Accueil</h1>
                            <hr class="small">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input id="searchBar" list="searchBarOption" type="text" class="form-control" placeholder="Rechercher...">
                                            <select id="searchBarOption" class="form-control searchSelector">
                                                
                                          </select>
                                        
                                        <a class="input-group-addon" href="#"><span class="glyphicon glyphicon-search"></span></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div id="modal">
            <!--<div class="modal fade" id="infos">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div>-->
        </div>

        <!-- Main Content -->
        <div class="container">
            <?php
            if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]->niveau == 3)
            {
                ?>
                <div class="col-lg-offset-8">
                    <a href="nouvelleActualite.php"><button class="btn btn-default">Nouvelle actualité</button></a>
                </div>
                <?php
            }
            ?>
            
            <div class="row">



                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1" id="listeActus">

                    <!--Pager-->
                </div>
                <div class="col-md-offset-4" id="chargement"><img src="img/loading.gif"></div>

            </div>
        </div>



        <hr>

        <!--  Footer -->

        <?php include("footer.php"); ?>


        <script src="js/myJs/index.js"></script>

    </body>
    </html>


<!--<div class="modal fade" id="infos"><div class="modal-dialog"><div class="modal-content"></div></div></div>-->
