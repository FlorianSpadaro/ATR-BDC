<?php
    include("header.php");
    $mesNotifs = json_decode(getNotificationsByUtilisateurId($_SESSION["user_id"]));
?>

    <!DOCTYPE html>
    <html>

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Notifications</title>

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
                            <h1>Mes Notifications</h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>


        <ul class="nav nav-pills container">
            <li class="active"><a href="#nouvellesNotifs" data-toggle="tab">Nouvelles Notifications</a></li>
            <li><a href="#anciennesNotifs" data-toggle="tab">Anciennes Notifications</a></li>
        </ul>

        <div class="tab-content">
            
            <div class="tab-pane active fade in container" id="nouvellesNotifs">
                <br/>
            
            <?php
            if(($mesNotifs != null))
            {
                $nbNouvelles = 0;
                foreach($mesNotifs as $maNotif)
                {
                    if(!$maNotif->vu)
                    {
                        $nbNouvelles++;
                        
                        ?>
                        
                        <div class="panel panel-default row" id="panel<?php echo $maNotif->id ?>">
                            <div class="panel-heading">
                                <h1 class="panel-title"><?php echo $maNotif->notification->titre ?><button type="button" class="close delete" data-dismiss="modal" id="<?php echo $maNotif->id ?>"><span class="glyphicon glyphicon-remove"></span></button></h1>
                            </div>
                            <div class="panel-body">
                                <?php echo $maNotif->notification->description ?>
                            </div>
                            <div class="panel-footer">
                                <a href="<?php echo $maNotif->notification->lien ?>"><button class="btn btn-info">Voir</button></a>
                                <span class="pull-right"><?php echo $maNotif->notification->date->jour." ".$maNotif->notification->date->heure ?></span>
                            </div>
                        </div>
                
                        <?php
                    }
                }
                if($nbNouvelles == 0)
                {
                    ?>
                    <label class="label label-info">Aucune nouvelle Notification</label>
                    <?php
                }
            }
            else{
                ?>
                <label class="label label-info">Aucune nouvelle Notification</label>
                <?php
            }
            ?>
                

            </div>

            <div class="tab-pane fade container" id="anciennesNotifs">
                <br/>
                
                <div class="row">
                    <button class="btn btn-danger pull-right" id="toutSupprimer">Tout supprimer</button>
                </div>
                <br/>
                
                <?php
                if(($mesNotifs != null))
                {
                    $nbNouvelles = 0;
                    foreach($mesNotifs as $maNotif)
                    {
                        if($maNotif->vu)
                        {
                            $nbNouvelles++;

                            ?>

                            <div class="panel panel-default row ancien" id="panel<?php echo $maNotif->id ?>">
                                <div class="panel-heading">
                                    <h1 class="panel-title"><?php echo $maNotif->notification->titre ?><button type="button" class="close delete" data-dismiss="modal" id="<?php echo $maNotif->id ?>"><span class="glyphicon glyphicon-remove"></span></button></h1>
                                </div>
                                <div class="panel-body">
                                    <?php echo $maNotif->notification->description ?>
                                </div>
                                <div class="panel-footer">
                                    <a href="<?php echo $maNotif->notification->lien ?>"><button class="btn btn-info">Voir</button></a>
                                    <span class="pull-right"><?php echo $maNotif->notification->date->jour." ".$maNotif->notification->date->heure ?></span>
                                </div>
                            </div>

                            <?php
                        }
                    }
                    if($nbNouvelles == 0)
                    {
                        ?>
                        <label class="label label-info">Aucune notification</label>
                        <?php
                    }
                }
                else{
                    ?>
                    <label class="label label-info">Aucune notification</label>
                    <?php
                }
                ?>

            </div>
        </div>
        
        
        
        <?php
            updateAllNotificationsVues($_SESSION["user_id"]);
        ?>
        
        <!--  Footer -->

        <?php include("footer.php"); ?>
        <script src="js/myJs/mesNotifications.js"></script>

    </body>

    </html>
