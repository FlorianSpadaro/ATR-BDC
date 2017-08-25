<?php
    include("header.php");
    
    $messages = json_decode(getMessagesByUtilisateurId($_SESSION["user_id"]));
?>

    <!DOCTYPE html>
    <html>

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Messages</title>

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
                            <h1>Mes Messages</h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <?php
            $tab = array();
            foreach($messages->recus as $msgRecu)
            {
                $contient = false;
                foreach($tab as $elt)
                {
                    if($elt == $msgRecu->correspondant->id)
                    {
                        $contient = true;
                    }
                }
                if(!$contient)
                {
                    array_push($tab, $msgRecu->correspondant->id);
                }
            }
            foreach($messages->envoyes as $msgEnvoye)
            {
                $contient = false;
                foreach($tab as $elt)
                {
                    if($elt == $msgEnvoye->correspondant->id)
                    {
                        $contient = true;
                    }
                }
                if(!$contient)
                {
                    array_push($tab, $msgEnvoye->correspondant->id);
                }
            }
        
        foreach($tab as $elt)
        {
            ?>
            <div class="modal fade" id="infos<?php echo $elt ?>">
                <div class="modal-dialog">  
                  <div class="modal-content"></div>  
                </div> 
              </div>
            <?php
        }
        ?>
        
        <ul class="nav nav-pills container">
            <li class="active"><a href="#messagesRecus" data-toggle="tab">Messages reçus</a></li>
            <li><a href="#messagesEnvoyes" data-toggle="tab">Messages envoyés</a></li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane active fade in container" id="messagesRecus">

                <?php
                if($messages !== null && isset($messages->recus) && $messages->recus !== null){
                    ?>
                    <section class="col-sm-8 table responsive">
                        <table class="table table-condensed">
                            <caption>
                                <h4>Messages reçus</h4>
                            </caption>
                            <thead>
                                <tr>
                                    <th>Sujet</th>
                                    <th>Correspondant</th>
                                    <th>Date</th>
                                    <th>
                                        <div class="input-group">
                                            <span class="input-group-addon"><input type="checkbox" id="allMessagesRecus" /></span>
                                            <button class="btn btn-danger" id="supprimerSelection">Supprimer</button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            foreach($messages->recus as $messageRecu)
                            {
                                ?>
                                    <tr <?php if(!$messageRecu->lu){echo "class='success'";} ?> id="messageRecu<?php echo $messageRecu->id ?>" >
                                            <td>
                                                <a href="message.php?id=<?php echo $messageRecu->message->id ?>">
                                                    <?php echo $messageRecu->message->sujet ?>
                                                </a>
                                            </td>
                                            <td>
                                                
                                                <a data-toggle="modal" href="infosUtilisateur.php?id=<?php echo urlencode($messageRecu->correspondant->id) ?>&amp;prenom=<?php echo urlencode($messageRecu->correspondant->prenom) ?>&amp;nom=<?php echo urlencode($messageRecu->correspondant->nom) ?>&amp;fonction=<?php echo urlencode($messageRecu->correspondant->fonction->libelle) ?>&amp;niveau=<?php echo urlencode($messageRecu->correspondant->fonction->niveau->libelle) ?>&amp;photo=<?php echo urlencode($messageRecu->correspondant->photo) ?>&amp;email=<?php echo urlencode($messageRecu->correspondant->email) ?>" data-target="#infos<?php echo $messageRecu->correspondant->id ?>">
                                                    <?php echo strtoupper($messageRecu->correspondant->nom)." ".strtoupper(substr($messageRecu->correspondant->prenom, 0, 1)).strtolower(substr($messageRecu->correspondant->prenom, 1)); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php echo $messageRecu->message->date->jour." à ".$messageRecu->message->date->heure ?>
                                            </td>
                                            <td>
                                                <input type="checkbox" class="selectionMessageRecus" name="<?php echo $messageRecu->id ?>" id="<?php echo $messageRecu->id ?>" />
                                            </td>
                                    </tr>
                                    <?php
                            }
                            ?>
                            </tbody>
                        </table>

                    </section>
                    <?php
                }
                else{
                    ?>
                        <label class="label label-info">Vous n'avez reçu aucun message</label>
                        <?php
                }
                ?>



            </div>

            <div class="tab-pane fade container" id="messagesEnvoyes">

                <?php
                if($messages !== null && isset($messages->envoyes) && $messages->envoyes !== null)
                {
                    ?>
                    <section class="col-sm-8 table responsive">
                        <table class="table table-condensed">
                            <caption>
                                <h4>Messages envoyés</h4>
                            </caption>
                            <thead>
                                <tr>
                                    <th>Sujet</th>
                                    <th>Correspondant</th>
                                    <th>Date</th>
                                    <th>
                                        <div class="input-group">
                                            <span class="input-group-addon"><input type="checkbox" id="allMessagesEnvoyes" /></span>
                                            <button class="btn btn-danger" id="supprimerSelectionEnvoyes">Supprimer</button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            foreach($messages->envoyes as $messageEnvoye)
                            {
                                ?>
                                    <tr id="messageEnvoye<?php echo $messageEnvoye->id ?>">
                                        <td>
                                            <a href="message.php?id=<?php echo $messageEnvoye->message->id ?>">
                                                <?php echo $messageEnvoye->message->sujet ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a data-toggle="modal" href="infosUtilisateur.php?id=<?php echo urlencode($messageEnvoye->correspondant->id) ?>&amp;prenom=<?php echo urlencode($messageEnvoye->correspondant->prenom) ?>&amp;nom=<?php echo urlencode($messageEnvoye->correspondant->nom) ?>&amp;fonction=<?php echo urlencode($messageEnvoye->correspondant->fonction->libelle) ?>&amp;niveau=<?php echo urlencode($messageEnvoye->correspondant->fonction->niveau->libelle) ?>&amp;photo=<?php echo urlencode($messageEnvoye->correspondant->photo) ?>&amp;email=<?php echo urlencode($messageEnvoye->correspondant->email) ?>" data-target="#infos<?php echo $messageEnvoye->correspondant->id ?>">
                                                <?php echo strtoupper($messageEnvoye->correspondant->nom)." ".strtoupper(substr($messageEnvoye->correspondant->prenom, 0, 1)).strtolower(substr($messageEnvoye->correspondant->prenom, 1)); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php echo $messageEnvoye->message->date->jour." à ".$messageEnvoye->message->date->heure ?>
                                        </td>
                                        <td>
                                            <input type="checkbox" class="selectionMessagesEnvoyes" name="<?php echo $messageEnvoye->id ?>" id="<?php echo $messageEnvoye->id ?>" />
                                        </td>
                                    </tr>
                                    <?php
                            }
                            ?>
                            </tbody>
                        </table>

                    </section>
                    <?php
                }else{
                    ?>
                    <label class="label label-info">Vous n'avez envoyé aucun message</label>
                    <?php
                }
                ?>

            </div>
        </div>

        <div class="modal" id="messageSuppression">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        Voulez-vous supprimer ce(s) <span id="nbMessagesSelectionne"></span> message(s)?
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info" id="confirmationSuppression">Confirmer</button>
                        <button class="btn btn-danger" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

        <!--  Footer -->

        <?php include("footer.php"); ?>
            <script src="js/myJs/mesMessages.js"></script>

    </body>

    </html>
