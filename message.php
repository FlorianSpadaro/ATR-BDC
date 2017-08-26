<?php
    include("header.php");
    $message = json_decode(getMessageById($_GET["id"]));
?>

<!DOCTYPE html>
<html>
   <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Message</title>

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
           #reponse{
               resize: vertical;
               height: 150px;
           }
       </style>
    </head> 
    
    <body>
        
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>Message</h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
            
            
            <div class="panel panel-default row">
              <div class="panel-heading">
                <h1 class="panel-title"><?php echo $message->sujet ?></h1>
              </div>
              <div class="panel-body"><?php echo $message->message ?></div>
              <div class="panel-footer pull-right">Envoyé par <a data-toggle="modal" href="infosUtilisateur.php?id=<?php echo $message->envoyeur->id ?>" data-target="#infos"><?php echo strtoupper($message->envoyeur->nom)." ".strtoupper(substr($message->envoyeur->prenom, 0, 1)).strtolower(substr($message->envoyeur->prenom, 1)) ?></a> le <?php echo $message->date->jour." à ".$message->date->heure ?></div>
            </div>
            
            
            <form class="row well">
                <legend>Répondre</legend>
                <div class="form-group">
                    <textarea class="form-control" maxlength="249" id="reponse"></textarea>
                    <span class="badge"><span id="nbCarac">0</span>/250</span>
                </div>
                <button class="btn btn-info pull-right" id="envoyerReponse">Envoyer</button>
            </form>
            
        </div>
        
        
        <div class="modal fade" id="infos">
            <div class="modal-dialog">  
              <div class="modal-content"></div>  
            </div> 
          </div>
        
        <!-- Footer -->
        <?php include("footer.php"); ?>
        
        <script src="js/myJs/message.js"></script>
    </body>
</html>
