<?php
    include("header.php");
    $message = json_decode(getMessageById($_GET["id"]));
    updateMessageLu($_GET["id"], $_SESSION["user_id"]);
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
                            <h1><?php echo $message->sujet ?></h1>
                            <h2 class="subheading"><?php
                                if($message->envoyeur->id != $_SESSION["user_id"])
                                {
                                    echo strtoupper($message->envoyeur->nom)." ".strtoupper(substr($message->envoyeur->prenom, 0, 1)).strtolower(substr($message->envoyeur->prenom, 1));
                                }
                                else{
                                    echo strtoupper($message->receveur->nom)." ".strtoupper(substr($message->receveur->prenom, 0, 1)).strtolower(substr($message->receveur->prenom, 1));
                                }
                                ?>
                            </h2>
                        </div>
                                                    
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
            
            
            <div class="panel <?php if($message->envoyeur->id == $_SESSION["user_id"]){echo "panel-info";}else{echo "panel-default";} ?> row">
              <div class="panel-heading">
                <h1 class="panel-title"><?php echo $message->sujet ?></h1>
              </div>
              <div class="panel-body"><?php echo $message->message ?></div>
              <div class="panel-footer pull-right">Envoyé par <a data-toggle="modal" href="infosUtilisateur.php?id=<?php echo $message->envoyeur->id ?>" data-target="#infos"><?php echo strtoupper($message->envoyeur->nom)." ".strtoupper(substr($message->envoyeur->prenom, 0, 1)).strtolower(substr($message->envoyeur->prenom, 1)) ?></a> le <?php echo $message->date->jour." à ".$message->date->heure ?></div>
            </div>
            
            <?php
            if(isset($message->reponse) && ($message->reponse !== null))
            {
                foreach($message->reponse as $reponse)
                {
                    ?>
                    <div class="panel <?php if($reponse->utilisateur->id == $_SESSION["user_id"]){echo "panel-info";}else{echo "panel-default";} ?> row">
                      <div class="panel-heading">
                        <h1 class="panel-title"><?php echo $message->sujet ?></h1>
                      </div>
                      <div class="panel-body"><?php echo $reponse->reponse ?></div>
                      <div class="panel-footer pull-right">Envoyé par <a data-toggle="modal" href="infosUtilisateur.php?id=<?php echo $reponse->utilisateur->id ?>" data-target="#infos"><?php echo strtoupper($reponse->utilisateur->nom)." ".strtoupper(substr($reponse->utilisateur->prenom, 0, 1)).strtolower(substr($reponse->utilisateur->prenom, 1)) ?></a> le <?php echo $reponse->date->jour." à ".$reponse->date->heure ?></div>
                    </div>
                    <?php
                }
            }
            ?>
            
            
            <form class="row well" id="formulaire">
                <input type="hidden" name="idMessage" id="idMessage" value="<?php echo $_GET["id"] ?>" />
                <input type="hidden" name="idReceveur" id="idReceveur" value="<?php echo $message->receveur->id ?>" />
                <input type="hidden" name="idEnvoyeur" id="idEnvoyeur" value="<?php echo $message->envoyeur->id ?>" />
                <input type="hidden" name="sujet" id="sujet" value="<?php echo $message->sujet ?>" />
                <legend>Répondre</legend>
                <div class="form-group">
                    <textarea class="form-control" maxlength="250" id="reponse"></textarea>
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
