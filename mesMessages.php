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
                                                <a href="message.php?id=<?php echo $messageRecu->id ?>">
                                                    <?php echo $messageRecu->sujet ?>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="modal fade" id="infos<?php echo $messageRecu->correspondant->id ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content"></div>
                                                    </div>
                                                </div>
                                                <a data-toggle="modal" href="infosUtilisateur.php?id=<?php echo $messageRecu->correspondant->id ?>&amp;prenom=<?php echo $messageRecu->correspondant->prenom ?>&amp;nom=<?php echo $messageRecu->correspondant->nom ?>&amp;fonction=<?php echo $messageRecu->correspondant->fonction->libelle ?>&amp;niveau=<?php echo $messageRecu->correspondant->fonction->niveau->libelle ?>&amp;photo=<?php echo $messageRecu->correspondant->photo ?>&amp;email=<?php echo $messageRecu->correspondant->email ?>" data-target="#infos<?php echo $messageRecu->correspondant->id ?>">
                                                    <?php echo strtoupper($messageRecu->correspondant->nom)." ".strtoupper(substr($messageRecu->correspondant->prenom, 0, 1)).strtolower(substr($messageRecu->correspondant->prenom, 1)); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php echo $messageRecu->date->jour." à ".$messageRecu->date->heure ?>
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
                                            <a href="message.php?id=<?php echo $messageEnvoye->id ?>">
                                                <?php echo $messageEnvoye->sujet ?>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="modal fade" id="infos<?php echo $messageEnvoye->utilisateur->id ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content"></div>
                                                </div>
                                            </div>
                                            <a data-toggle="modal" href="infosUtilisateur.php?id=<?php echo $messageEnvoye->utilisateur->id ?>&amp;prenom=<?php echo $messageEnvoye->utilisateur->prenom ?>&amp;nom=<?php echo $messageEnvoye->utilisateur->nom ?>&amp;fonction=<?php echo $messageEnvoye->utilisateur->fonction->libelle ?>&amp;niveau=<?php echo $messageEnvoye->utilisateur->fonction->niveau->libelle ?>&amp;photo=<?php echo $messageEnvoye->utilisateur->photo ?>&amp;email=<?php echo $messageEnvoye->utilisateur->email ?>" data-target="#infos<?php echo $messageEnvoye->utilisateur->id ?>">
                                                <?php echo strtoupper($messageEnvoye->utilisateur->nom)." ".strtoupper(substr($messageEnvoye->utilisateur->prenom, 0, 1)).strtolower(substr($messageEnvoye->utilisateur->prenom, 1)); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php echo $messageEnvoye->date->jour." à ".$messageEnvoye->date->heure ?>
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

                        <!--<section class="col-sm-8 table responsive">
                    <table class="table table-bordered table-striped table-condensed">
                        <caption>
                            <h4>Messages envoyés</h4>
                        </caption>
                        <thead>
                            <tr>
                                <th>Correspondant</th>
                                <th>Sujet</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="success">
                                <td>Grand Mekong</td>
                                <td>Demande croissante de certaines parties de l’animal pour la médecine chinoise traditionnelle et fragmentation des habitats du fait du développement non durable d’infrastructures</td>
                                <td>6 jours</td>
                                <td>Supprimer</td>
                            </tr>
                            <tr class="danger">
                                <td>Île de Sumatra</td>
                                <td>Production d’huile de palme et de pâtes à papiers</td>
                                <td>6 jours</td>
                                <td>Supprimer</td>
                            </tr>
                            <tr class="warning">
                                <td>Indonésie et Malaisie</td>
                                <td>Pâte à papier, l’huile de palme et le caoutchouc</td>
                                <td>6 jours</td>
                                <td>Supprimer</td>
                            </tr>
                            <tr class="active">
                                <td>États-Unis</td>
                                <td>Les tigres captifs représentent un danger pour les tigres sauvages</td>
                                <td>6 jours</td>
                                <td>Supprimer</td>
                            </tr>
                            <tr class="success">
                                <td>Europe</td>
                                <td>Gros appétit pour l’huile de palme</td>
                                <td>6 jours</td>
                                <td>Supprimer</td>
                            </tr>
                            <tr class="danger">
                                <td>Népal</td>
                                <td>Commerce illégal de produits dérivés de tigres</td>
                                <td>6 jours</td>
                                <td>Supprimer</td>
                            </tr>
                        </tbody>
                    </table>
                </section>-->
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
