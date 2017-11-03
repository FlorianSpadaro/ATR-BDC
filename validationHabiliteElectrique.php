<?php
    include("header.php");
    if(!isset($_SESSION["niveau"]) || $_SESSION["niveau"]->niveau != 3)
    {
        header('Location: index.php');
        exit();
    }
    $formulaire = json_decode(getFormulaireHabilitationElectriqueById($_GET["id"]));
    
    $userForm = json_decode(getUtilisateurById($formulaire->utilisateur_id));

    $reponses = (object) array();

    $reponses->q1_ans1 = array("TEST", "Autre Test", "DernierTest");

    $reponses->q2_ans1 = true;
    $reponses->q2_ans2 = false;

    $reponses->q3_ans1 = false;
    $reponses->q3_ans2 = false;
    $reponses->q3_ans3 = true;

    $reponses->q4_ans1 = false;
    $reponses->q4_ans2 = true;
    $reponses->q4_ans3 = false;

    $reponses->q5_ans1 = false;
    $reponses->q5_ans2 = true;

    $reponses->q6_ans1 = array("30cm");

    $reponses->q7_ans1 = true;
    $reponses->q7_ans2 = false;

    $reponses->q8_ans1 = true;
    $reponses->q8_ans2 = false;

    $reponses->q9_ans1 = false;
    $reponses->q9_ans2 = false;
    $reponses->q9_ans3 = false;

    $reponses->q10_ans1 = false;

    $reponses->q11_ans1 = true;

    $reponses->q12_ans1 = false;
    $reponses->q12_ans2 = true;

    $reponses->q13_ans1 = false;
    $reponses->q13_ans2 = false;
    $reponses->q13_ans3 = true;

    $reponses->q14_ans1 = 5;
    $reponses->q14_ans2 = 1;
    $reponses->q14_ans3 = 6;
    $reponses->q14_ans4 = 2;
    $reponses->q14_ans5 = 3;
    $reponses->q14_ans6 = 7;
    $reponses->q14_ans7 = 4;
?>

    <!DOCTYPE html>
    <html>

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Habilitation Electrique</title>

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

    <div class="modal" id="reponses1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Réponses possibles pour la question 1</h4>
            </div>
            <div class="modal-body">
                <?php
                if(sizeof($reponses->q1_ans1) > 0)
                {
                    ?>
                    <ul>
                        <?php
                        foreach($reponses->q1_ans1 as $rep1)
                        {
                            ?>
                            <li><?php echo $rep1 ?></li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" data-dismiss="modal">Fermer</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal" id="reponses6">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Réponses possibles pour la question 6</h4>
            </div>
            <div class="modal-body">
                <?php
                if(sizeof($reponses->q6_ans1) > 0)
                {
                    ?>
                    <ul>
                        <?php
                        foreach($reponses->q6_ans1 as $rep6)
                        {
                            ?>
                            <li><?php echo $rep6 ?></li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" data-dismiss="modal">Fermer</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal" id="titreHabilElec">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Prévisualisation du titre d'habilitation éléctrique</h4>
            </div>
            <div class="modal-body container-fluid">
                <div id="divHabilElec" class="col-xs-10 col-xs-offset-1">
                </div>
                <div id="editor"></div>
            </div>
            <div class="modal-footer">
                <div class="btn-group" >
                    <button class="btn btn-default" id="enregistrerTitreHabilElec" >Enregistrer</button>
                    <button class="btn btn-info" id="btnImprimer" >Imprimer</button>
                </div>
            </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="formulaire_id" id="formulaire_id" value="<?php echo $_GET["id"] ?>" />
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>HO-B0</h1>
                            <h2 class="subheading"><?php echo strtoupper($userForm->nom)." ".ucfirst(strtolower($userForm->prenom)) ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </header>
            <div class="container">
                
                <h1>Questionnaire</h1>
            
                <input type="hidden" name="brouillon_id" id="brouillon_id" value="NULL" />

                <p><h4>1. </h4>De quoi dépendent les dommages du courant électrique sur le corps humain ? Citez trois exemples. <a data-toggle="modal" href="#reponses1" ><span class="glyphicon glyphicon-registration-mark"></span></a></p>
                <input type="text" name="q1_ans1"  id="q1_ans1" value="<?php echo $formulaire->q1_ans1 ?>" readonly>
                <input type="text" name="q1_ans2" id="q1_ans2" value="<?php echo $formulaire->q1_ans2 ?>" readonly>
                <input type="text" name="q1_ans3" id="q1_ans3" value="<?php echo $formulaire->q1_ans3 ?>" readonly>

                <p><h4>2. 
                <?php if($reponses->q2_ans1 == $formulaire->q2_ans1 && $reponses->q2_ans2 == $formulaire->q2_ans2){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                </h4>Le courant est-il dangereux à partir d’une intensité de 5 mA ou 5 A ? </p>
                <label for="q2_ans1"><input type="radio" name="q2_ans" id="q2_ans1" <?php if($formulaire->q2_ans1){ echo "checked"; } ?>> 5 mA</label><br/>
                <label for="q2_ans2"><input type="radio" name="q2_ans" id="q2_ans2" <?php if($formulaire->q2_ans2){ echo "checked"; } ?>> 5 A</label>

                <p><h4>3.</h4>En courant alternatif, quelles sont les limites de la Basse Tension ?  </p>
                <label for="q3_ans1"><input type="checkbox" name="q3_ans" id="q3_ans1" <?php if($formulaire->q3_ans1){ echo "checked"; } ?>> 50 à 500 volts</label>
                <?php if($reponses->q3_ans1 == $formulaire->q3_ans1){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label for="q3_ans2"><input type="checkbox" name="q3_ans" id="q3_ans2" <?php if($formulaire->q3_ans2){ echo "checked"; } ?>> 1 à 5000 volts</label>
                <?php if($reponses->q3_ans2 == $formulaire->q3_ans2){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label for="q3_ans3"><input type="checkbox" name="q3_ans" id="q3_ans3" <?php if($formulaire->q3_ans3){ echo "checked"; } ?>> 50 à 1000 volts</label>
                <?php if($reponses->q3_ans3 == $formulaire->q3_ans3){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>

                <p><h4>4.</h4>En cas d’accident d’origine électrique, quelle est la première opération à effectuer ?</p>
                <label for="q4_ans1"><input type="checkbox" name="q4_ans" id="q4_ans1" <?php if($formulaire->q4_ans1){ echo "checked"; } ?>> Secourir la victime </label>
                <?php if($reponses->q4_ans1 == $formulaire->q4_ans1){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label for="q4_ans2"><input type="checkbox" name="q4_ans" id="q4_ans2" <?php if($formulaire->q4_ans2){ echo "checked"; } ?>> Faire couper le courant </label>
                <?php if($reponses->q4_ans2 == $formulaire->q4_ans2){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label for="q4_ans3"><input type="checkbox" name="q4_ans" id="q4_ans3" <?php if($formulaire->q4_ans3){ echo "checked"; } ?>> Donner l’alerte </label>
                <?php if($reponses->q4_ans3 == $formulaire->q4_ans3){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>

                <p><h4>5.</h4>Comment reconnaît-on un « local d’accès réservé aux électriciens » ? </p>
                <label for="q5_ans1"><input type="checkbox" name="q5_ans" id="q5_ans1" <?php if($formulaire->q5_ans1){ echo "checked"; } ?>> La porte est fermée à clef  </label>
                <?php if($reponses->q5_ans1 == $formulaire->q5_ans1){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label for="q5_ans2"><input type="checkbox" name="q5_ans" id="q5_ans2" <?php if($formulaire->q5_ans2){ echo "checked"; } ?>> Il y a un symbole de danger jaune sur la porte  </label>
                <?php if($reponses->q5_ans2 == $formulaire->q5_ans2){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>

                <p><h4>6.</h4>En BT, si vous êtes « au voisinage de pièces nues, accessibles et sous tension », à quelle distance vous trouvez-vous des pièces conductrices ?  <a data-toggle="modal" href="#reponses6" ><span class="glyphicon glyphicon-registration-mark"></span></a></p>
                <input type="text" name="q6_ans1" placeholder="au plus" id="q6_ans1" value="<?php echo $formulaire->q6_ans1 ?>" readonly>

                <p><h4>7.</h4>Une habilitation seulement B0V vous permet-elle de travailler à moins de 0,30 m de pièces conductrices sous tension :  </p>
                <label>-	Au voisinage d'une tension de 50 à 1000 volts ? </label> <label for="q7_ans1"><input type="radio" name="q7_ans1" id="q7_ans1" <?php if($formulaire->q7_ans1){ echo "checked"; } ?>> Oui</label> <label for="q7_ans2"><input type="radio" name="q7_ans1" id="q7_ans2" <?php if($formulaire->q7_ans1 == false && $formulaire->q7_ans1 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q7_ans1 == $formulaire->q7_ans1 && gettype($formulaire->q7_ans1) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label>-	Au voisinage d'une tension de plus de 1000 volts ? </label> <label for="q7_ans3"><input type="radio" name="q7_ans2" id="q7_ans3" <?php if($formulaire->q7_ans2){ echo "checked"; } ?>> Oui</label> <label for="q7_ans4"><input type="radio" name="q7_ans2" id="q7_ans4" <?php if($formulaire->q7_ans2 == false  && $formulaire->q7_ans2 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q7_ans2 == $formulaire->q7_ans2 && gettype($formulaire->q7_ans2) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>

                <p><h4>8.</h4>Si vous êtes habilité B0-H0, pouvez-vous : </p>
                <label>-	Recevoir une autorisation de travail ? </label> <label for="q8_ans1"><input type="radio" name="q8_ans1" id="q8_ans1" <?php if($formulaire->q8_ans1){ echo "checked"; } ?>> Oui</label> <label for="q8_ans2"><input type="radio" name="q8_ans1" id="q8_ans2" <?php if($formulaire->q8_ans1 == false  && $formulaire->q8_ans1 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q8_ans1 == $formulaire->q8_ans1 && gettype($formulaire->q8_ans1) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label>-	Etre surveillant de sécurité électrique ? </label> <label for="q8_ans3"><input type="radio" name="q8_ans2" id="q8_ans3" <?php if($formulaire->q8_ans2){ echo "checked"; } ?>> Oui</label> <label for="q8_ans4"><input type="radio" name="q8_ans2" id="q8_ans4" <?php if($formulaire->q8_ans2 == false  && $formulaire->q8_ans2 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q8_ans2 == $formulaire->q8_ans2 && gettype($formulaire->q8_ans2) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>

                <p><h4>9.</h4>Etant habilité B0V pouvez-vous changer un fusible BT sous tension qui présente : </p>
                <label>-	Un risque de contact direct avec une partie électrique ? </label> <label for="q9_ans1"><input type="radio" name="q9_ans1" id="q9_ans1" <?php if($formulaire->q9_ans1){ echo "checked"; } ?>> Oui</label> <label for="q9_ans2"><input type="radio" name="q9_ans1" id="q9_ans2" <?php if($formulaire->q9_ans1 == false  && $formulaire->q9_ans1 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q9_ans1 == $formulaire->q9_ans1 && gettype($formulaire->q9_ans1) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label>-	Un risque de projection sans risque de contact direct ?  </label> <label for="q9_ans3"><input type="radio" name="q9_ans2" id="q9_ans3" <?php if($formulaire->q9_ans2){ echo "checked"; } ?>> Oui</label> <label for="q9_ans4"><input type="radio" name="q9_ans2" id="q9_ans4" <?php if($formulaire->q9_ans2 == false  && $formulaire->q9_ans2 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q9_ans2 == $formulaire->q9_ans2 && gettype($formulaire->q9_ans2) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label>-	Aucun de ces risques ?  </label> <label for="q9_ans5"><input type="radio" name="q9_ans3" id="q9_ans5" <?php if($formulaire->q9_ans3){ echo "checked"; } ?>> Oui</label> <label for="q9_ans6"><input type="radio" name="q9_ans3" id="q9_ans6" <?php if($formulaire->q9_ans3 == false  && $formulaire->q9_ans3 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q9_ans3 == $formulaire->q9_ans3 && gettype($formulaire->q9_ans3) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>

                <p><h4>10.</h4>Habilité B0-H0, pouvez-vous ouvrir une armoire électrique (avec des pièces nues sous tension accessibles) sans autorisation ?  </p>
                <label for="q10_ans1"><input type="radio" name="q10_ans1" id="q10_ans1" <?php if($formulaire->q10_ans1){ echo "checked"; } ?>> Oui</label> <label for="q10_ans2"><input type="radio" name="q10_ans1" id="q10_ans2" <?php if($formulaire->q10_ans1 == false && $formulaire->q10_ans1 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q10_ans1 == $formulaire->q10_ans1 && gettype($formulaire->q10_ans1) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>

                <p><h4>11.</h4>L'habilitation B0-H0 vous permet-elle d'être désigné pour entrer, sans surveillance, dans un local d'accès réservé aux électriciens ?  </p>
                <label for="q11_ans1"><input type="radio" name="q11_ans1" id="q11_ans1" <?php if($formulaire->q11_ans1){ echo "checked"; } ?>> Oui</label> <label for="q11_ans2"><input type="radio" name="q11_ans1" id="q11_ans2" <?php if($formulaire->q11_ans1 == false && $formulaire->q11_ans1 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q11_ans1 == $formulaire->q11_ans1 && gettype($formulaire->q11_ans1) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>

                <p><h4>12.</h4>L'habilitation est-elle : </p>
                <label>-	La preuve d'une qualification professionnelle ? </label> <label for="q12_ans1"><input type="radio" name="q12_ans1" id="q12_ans1" <?php if($formulaire->q12_ans1){ echo "checked"; } ?>> Oui</label> <label for="q12_ans2"><input type="radio" name="q12_ans1" id="q12_ans2" <?php if($formulaire->q12_ans1 == false && $formulaire->q12_ans1 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q12_ans1 == $formulaire->q12_ans1 && gettype($formulaire->q12_ans1) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label>-	La reconnaissance, par votre employeur, de votre capacité à travailler en sécurité ? </label> <label for="q12_ans3"><input type="radio" name="q12_ans2" id="q12_ans3" <?php if($formulaire->q12_ans2){ echo "checked"; } ?>> Oui</label> <label for="q12_ans4"><input type="radio" name="q12_ans2" id="q12_ans4" <?php if($formulaire->q12_ans2 == false && $formulaire->q12_ans2 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q12_ans2 == $formulaire->q12_ans2 && gettype($formulaire->q12_ans2) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>

                <p><h4>13.</h4>Vous laissez tomber un outil dans une zone balisée, du côté des ouvrages sous tension : </p>
                <label>-	Vous franchissez le balisage ? </label> <label for="q13_ans1"><input type="radio" name="q13_ans1" id="q13_ans1" <?php if($formulaire->q13_ans1){ echo "checked"; } ?>> Oui</label> <label for="q13_ans2"><input type="radio" name="q13_ans1" id="q13_ans2" <?php if($formulaire->q13_ans1 == false && $formulaire->q13_ans1 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q13_ans1 == $formulaire->q13_ans1 && gettype($formulaire->q13_ans1) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label>-	Vous coupez le courant et ensuite franchissez le balisage ? </label> <label for="q13_ans3"><input type="radio" name="q13_ans2" id="q13_ans3" <?php if($formulaire->q13_ans2){ echo "checked"; } ?>> Oui</label> <label for="q13_ans4"><input type="radio" name="q13_ans2" id="q13_ans4" <?php if($formulaire->q13_ans2 == false && $formulaire->q13_ans2 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q13_ans2 == $formulaire->q13_ans2 && gettype($formulaire->q13_ans2) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                <br/>
                <label>-	Vous demandez des instructions au chargé de travaux ?  </label> <label for="q13_ans5"><input type="radio" name="q13_ans3" id="q13_ans5" <?php if($formulaire->q13_ans3){ echo "checked"; } ?>> Oui</label> <label for="q13_ans6"><input type="radio" name="q13_ans3" id="q13_ans6" <?php if($formulaire->q13_ans3 == false && $formulaire->q13_ans3 != null){ echo "checked"; } ?>> Non</label>
                <?php if($reponses->q13_ans3 == $formulaire->q13_ans3 && gettype($formulaire->q13_ans3) == "boolean"){
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>

                <p><h4>14.</h4>Reliez les habilitations et les lettres avec leurs significations en remplissant avec les numeros des habilitations:  </p>
                <div class="help-block"><span class="badge">NR</span> = Pas de réponse</div>
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
                <label class="form-control" style="height:60px"><span class="badge"><?php echo $formulaire->q14_ans1; if($formulaire->q14_ans1 == null){ echo "NR";} ?></span> Chargé de Consignation 
                <?php if($reponses->q14_ans1 == $formulaire->q14_ans1)
                {
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                </label>

                <label class="form-control" style="height:60px"><span class="badge"><?php echo $formulaire->q14_ans2; if($formulaire->q14_ans2 == null){ echo "NR";} ?></span> Non-Electricien 
                <?php if($reponses->q14_ans2 == $formulaire->q14_ans2)
                {
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
</label>

                <label class="form-control" style="height:60px"><span class="badge"><?php echo $formulaire->q14_ans3; if($formulaire->q14_ans3 == null){ echo "NR";} ?></span> Travail au Voisinage  
                <?php if($reponses->q14_ans3 == $formulaire->q14_ans3)
                {
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                </label>

                <label class="form-control" style="height:60px"><span class="badge"><?php echo $formulaire->q14_ans4; if($formulaire->q14_ans4 == null){ echo "NR";} ?></span> Exécutant 
Electricien 
<?php if($reponses->q14_ans4 == $formulaire->q14_ans4)
                {
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
 </label>

                <label class="form-control" style="height:60px"><span class="badge"><?php echo $formulaire->q14_ans5; if($formulaire->q14_ans5 == null){ echo "NR";} ?></span> Chargé de Travaux  
                <?php if($reponses->q14_ans5 == $formulaire->q14_ans5)
                {
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                </label>

                <label class="form-control" style="height:60px"><span class="badge"><?php echo $formulaire->q14_ans6; if($formulaire->q14_ans6 == null){ echo "NR";} ?></span> Travail sous Tension  
                <?php if($reponses->q14_ans6 == $formulaire->q14_ans6)
                {
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
                </label>

                <label class="form-control" style="height:60px"><span class="badge"><?php echo $formulaire->q14_ans7; if($formulaire->q14_ans7 == null){ echo "NR";} ?></span> Chargé 
d’intervention 
<?php if($reponses->q14_ans7 == $formulaire->q14_ans7)
                {
                    ?>
                    <label class="label label-success">Vrai</label>
                    <?php
                }
                else{
                    ?>
                    <label class="label label-danger erreur">Faux</label>
                    <?php
                }
                ?>
 </label>
 <br/>

        </div>
        <?php
        if(gettype($formulaire->valider) != "boolean")
        {
            ?>
            <br/>
            <div class="btn-group pull-right"><button class="btn btn-danger btn-lg" id="btnRefuserFormulaire" >Refuser</button><button class="btn btn-success btn-lg" id="btnAccepterFormulaire">Accepter</button></div>
            <?php
        }
        else{
            if($formulaire->valider)
            {
                ?>
                <br/>
                <button data-toggle="modal" href="#titreHabilElec" id="btnTitreHabilElec" class="btn btn-primary pull-right">Voir titre habiliation électrique</button>
                <?php
            }
        }
        ?>
        
            </div>
            <br/>
            <br/><br/>
            

        <?php include("footer.php"); ?>
        <script src="js/myJs/validationHabiliteElectrique.js"></script>

    </body>

    </html>
