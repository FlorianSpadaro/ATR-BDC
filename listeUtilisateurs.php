<?php
    include("header.php");
    $utilisateurs = json_decode(getUtilisateurs());
?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Liste des utilisateurs</title>

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
                            <h1>Liste des utilisateurs</h1>
                            <hr class="small">
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="container">
            <table id="listeUtilisateurs" class="tablesorter table table-striped table-hover"> 
                <thead> 
                <tr> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Nom</a></th> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Prénom</a></th> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Email</a></th> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Fonction</a></th> 
                    <th><a href="#" class="titreTab"><span class="glyphicon glyphicon-sort"></span> Niveau</a></th> 
                    <th>Action</th> 
                </tr> 
                </thead> 
                <tbody>
                <?php
                    if($utilisateurs != null)
                    {
                        foreach($utilisateurs as $user)
                        {
                            ?>
                            <tr id="utilisateur-<?php echo $user->id ?>"> 
                                <td><?php echo strtoupper($user->nom) ?></td> 
                                <td><?php echo ucfirst(strtolower($user->prenom)) ?></td> 
                                <td><?php echo $user->email ?></td> 
                                <td><?php echo $user->fonction->libelle ?></td> 
                                <td><?php echo $user->fonction->niveau->libelle ?></td>
                                <td>
                                    <a data-toggle="modal" href="#modificationsUtilisateur" class="modifierUser"><span class="glyphicon glyphicon-edit"></span></a>
                                    <a href="#" class="supprimerUser"><span class="glyphicon glyphicon-remove-sign"></span></a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                ?>
                </tbody> 
            </table> 
        </div>
        
        <div class="modal" id="modificationsUtilisateur">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Modifier utilisateur</h4>
              </div>
              <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Nom : </label>
                        <input type="text" name="nomUtilisateurModif" id="nomUtilisateurModif" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Prenom : </label>
                        <input type="text" name="nomUtilisateurModif" id="nomUtilisateurModif" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Email : </label>
                        <input type="email" name="nomUtilisateurModif" id="nomUtilisateurModif" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Fonction : </label>
                        <select class="form-control">
                        </select>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button class="btn btn-info" data-dismiss="modal">Fermer</button>
              </div>
            </div>
          </div>
        </div>
        
        
        <!--  Footer -->

        <?php include("footer.php"); ?> 
        <script type="text/javascript" src="vendor/tablesort/jquery.tablesorter.min.js"></script>
        <script src="js/myJs/listeUtilisateurs.js"></script>
    </body>
</html>