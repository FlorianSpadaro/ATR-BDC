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
                            <h1>HO-B0</h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
            <div class="container">
                <h1>Questionnaire</h1>
                
                <p><h4>1.</h4>De quoi dépendent les dommages du courant électrique sur le corps humain ? Citez trois exemples. </p>
                <input type="text" name="q1_ans1">
                <input type="text" name="q1_ans2">
                <input type="text" name="q1_ans3">

                <p><h4>2.</h4>Le courant est-il dangereux à partir d’une intensité de 5 mA ou 5 A ? </p>
                <label for="q2_ans1"><input type="radio" name="q2_ans" id="q2_ans1"> 5 mA</label><br/>
                <label for="q2_ans2"><input type="radio" name="q2_ans" id="q2_ans2"> 5 A</label>

                <p><h4>3.</h4>En courant alternatif, quelles sont les limites de la Basse Tension ?  </p>
                <label for="q3_ans1"><input type="checkbox" name="q3_ans" id="q3_ans1"> 50 à 500 volts</label><br/>
                <label for="q3_ans2"><input type="checkbox" name="q3_ans" id="q3_ans2"> 1 à 5000 volts</label><br/>
                <label for="q3_ans3"><input type="checkbox" name="q3_ans" id="q3_ans3"> 50 à 1000 volts</label>

                <p><h4>4.</h4>En cas d’accident d’origine électrique, quelle est la première opération à effectuer ?</p>
                <label for="q4_ans1"><input type="checkbox" name="q4_ans" id="q4_ans1"> Secourir la victime </label><br/>
                <label for="q4_ans2"><input type="checkbox" name="q4_ans" id="q4_ans2"> Faire couper le courant </label><br/>
                <label for="q4_ans3"><input type="checkbox" name="q4_ans" id="q4_ans3"> Donner l’alerte </label>

                <p><h4>5.</h4>Comment reconnaît-on un « local d’accès réservé aux électriciens » ? </p>
                <label for="q5_ans1"><input type="checkbox" name="q5_ans" id="q5_ans1"> La porte est fermée à clef  </label><br/>
                <label for="q5_ans2"><input type="checkbox" name="q5_ans" id="q5_ans2"> Il y a un symbole de danger jaune sur la porte  </label>

                <p><h4>6.</h4>En BT, si vous êtes « au voisinage de pièces nues, accessibles et sous tension », à quelle distance vous trouvez-vous des pièces conductrices ?  </p>
                <input type="text" name="q6_ans1" placeholder="au plus">

                <p><h4>7.</h4>Une habilitation seulement B0V vous permet-elle de travailler à moins de 0,30 m de pièces conductrices sous tension :  </p>
                <label>-	Au voisinage d'une tension de 50 à 1000 volts ? </label> <label for="q7_ans1"><input type="radio" name="q7_ans1" id="q7_ans1"> Oui</label> <label for="q7_ans2"><input type="radio" name="q7_ans1" id="q7_ans2"> Non</label><br/>
                <label>-	Au voisinage d'une tension de plus de 1000 volts ? </label> <label for="q7_ans3"><input type="radio" name="q7_ans2" id="q7_ans3"> Oui</label> <label for="q7_ans4"><input type="radio" name="q7_ans2" id="q7_ans4"> Non</label>

                <p><h4>8.</h4>Si vous êtes habilité B0-H0, pouvez-vous : </p>
                <label>-	Recevoir une autorisation de travail ? </label> <label for="q8_ans1"><input type="radio" name="q8_ans1" id="q8_ans1"> Oui</label> <label for="q8_ans2"><input type="radio" name="q8_ans1" id="q8_ans2"> Non</label><br/>
                <label>-	Etre surveillant de sécurité électrique ? </label> <label for="q8_ans3"><input type="radio" name="q8_ans2" id="q8_ans3"> Oui</label> <label for="q8_ans4"><input type="radio" name="q8_ans2" id="q8_ans4"> Non</label>

                <p><h4>9.</h4>Etant habilité B0V pouvez-vous changer un fusible BT sous tension qui présente : </p>
                <label>-	Un risque de contact direct avec une partie électrique ? </label> <label for="q9_ans1"><input type="radio" name="q9_ans1" id="q9_ans1"> Oui</label> <label for="q9_ans2"><input type="radio" name="q9_ans1" id="q9_ans2"> Non</label><br/>
                <label>-	Un risque de projection sans risque de contact direct ?  </label> <label for="q9_ans3"><input type="radio" name="q9_ans2" id="q9_ans3"> Oui</label> <label for="q9_ans4"><input type="radio" name="q9_ans2" id="q9_ans4"> Non</label><br/>
                <label>-	Aucun de ces risques ?  </label> <label for="q9_ans5"><input type="radio" name="q9_ans3" id="q9_ans5"> Oui</label> <label for="q9_ans6"><input type="radio" name="q9_ans3" id="q9_ans6"> Non</label>

                <p><h4>10.</h4>Habilité B0-H0, pouvez-vous ouvrir une armoire électrique (avec des pièces nues sous tension accessibles) sans autorisation ?  </p>
                <label for="q10_ans1"><input type="radio" name="q10_ans1" id="q10_ans1"> Oui</label> <label for="q10_ans2"><input type="radio" name="q10_ans1" id="q10_ans2"> Non</label>

                <p><h4>11.</h4>L'habilitation B0-H0 vous permet-elle d'être désigné pour entrer, sans surveillance, dans un local d'accès réservé aux électriciens ?  </p>
                <label for="q11_ans1"><input type="radio" name="q11_ans1" id="q11_ans1"> Oui</label> <label for="q11_ans2"><input type="radio" name="q11_ans1" id="q11_ans2"> Non</label>

                <p><h4>12.</h4>L'habilitation est-elle : </p>
                <label>-	La preuve d'une qualification professionnelle ? </label> <label for="q12_ans1"><input type="radio" name="q12_ans1" id="q12_ans1"> Oui</label> <label for="q12_ans2"><input type="radio" name="q12_ans1" id="q12_ans2"> Non</label><br/>
                <label>-	La reconnaissance, par votre employeur, de votre capacité à travailler en sécurité ? </label> <label for="q12_ans3"><input type="radio" name="q12_ans2" id="q12_ans3"> Oui</label> <label for="q12_ans4"><input type="radio" name="q12_ans2" id="q12_ans4"> Non</label>

                <p><h4>13.</h4>Vous laissez tomber un outil dans une zone balisée, du côté des ouvrages sous tension : </p>
                <label>-	Vous franchissez le balisage ? </label> <label for="q13_ans1"><input type="radio" name="q13_ans1" id="q13_ans1"> Oui</label> <label for="q13_ans2"><input type="radio" name="q13_ans1" id="q13_ans2"> Non</label><br/>
                <label>-	Vous coupez le courant et ensuite franchissez le balisage ? </label> <label for="q13_ans3"><input type="radio" name="q13_ans2" id="q13_ans3"> Oui</label> <label for="q13_ans4"><input type="radio" name="q13_ans2" id="q13_ans4"> Non</label><br/>
                <label>-	Vous demandez des instructions au chargé de travaux ?  </label> <label for="q13_ans5"><input type="radio" name="q13_ans3" id="q13_ans5"> Oui</label> <label for="q13_ans6"><input type="radio" name="q13_ans3" id="q13_ans6"> Non</label>

                <p><h4>14.</h4>Reliez les habilitations et les lettres avec leurs significations en remplissant avec les numeros des habilitations:  </p>
                <div style="display:flex;">
                <label class="form-control"><span class="badge">1</span> B0-H0 </label>
                <label class="form-control"><span class="badge">2</span> B1 ou H1</label>
                <label class="form-control"><span class="badge">3</span> B2 ou H2 </label>
                <label class="form-control"><span class="badge">4</span> BR </label>
                <label class="form-control"><span class="badge">5</span> BC ou HC </label>
                <label class="form-control"><span class="badge">6</span> V </label>
                <label class="form-control"><span class="badge">7</span> T </label>
        </div>
        <div style="display:flex;">
                <label class="form-control" style="height:60px"><span class="badge"><input type="text" name="q14_ans1" id="q14_ans1" size="1"></span> Chargé de Consignation </label>
                <label class="form-control" style="height:60px"><span class="badge"><input type="text" name="q14_ans2" id="q14_ans2" size="1"></span> Non-Electricien 
</label>
                <label class="form-control" style="height:60px"><span class="badge"><input type="text" name="q14_ans3" id="q14_ans3" size="1"></span> Travail au Voisinage  </label>
                <label class="form-control" style="height:60px"><span class="badge"><input type="text" name="q14_ans4" id="q14_ans4" size="1"></span> Exécutant 
Electricien 
 </label>
                <label class="form-control" style="height:60px"><span class="badge"><input type="text" name="q14_ans5" id="q14_ans5" size="1"></span> Chargé de Travaux  </label>
                <label class="form-control" style="height:60px"><span class="badge"><input type="text" name="q14_ans6" id="q14_ans6" size="1"></span> Travail sous Tension  </label>
                <label class="form-control" style="height:60px"><span class="badge"><input type="text" name="q14_ans7" id="q14_ans7" size="1"></span> Chargé 
d’intervention 
 </label><br/>

        </div>
        <button class="btn btn-primary pull-left" id="valid-habilitation">Mettre au brouillon</button> <button class="btn btn-success pull-right" id="valid-habilitation">Soumettre le questionnaire</button>
            </div>
            <br>
            <br><br>

        <?php include("footer.php"); ?>
   

    </body>

    </html>