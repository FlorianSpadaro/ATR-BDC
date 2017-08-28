<?php
session_start();
?>

<head>
    <style>
        .th{
            text-align: right;
        }
        .td{
            padding-left: 10px;
        }
        .messageDiv, .mdpDiv{
            display: none;
        }
        #succes{
            display: none;
            color: green;
        }#succesMdp{
            display: none;
            color: green;
        }
        #mdpErrone{
            color: red;
            display: none;
        }
        #mdpIdentiques{
            color: red;
            display: none;
        }
    </style>
</head>

<body>
    
    <?php
    require_once("API/fonctions.php");
    $user = json_decode(getUtilisateurById($_GET["id"]));
    ?>
    
    <input type="hidden" name="idUser" id="idUser" value="<?php echo $_GET["id"] ?>" />

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <img src="<?php echo $user->photo ?>" width="100" height="130">
        <b><?php echo strtoupper($user->nom)." ".strtoupper(substr($user->prenom, 0, 1)).strtolower(substr($user->prenom, 1)) ?></b>
    </div>
    <div class="modal-body">
        <div id="test"></div>
        <table>
            <tr>
                <th class="th">Email : </th>
                <td class="td">
                    <?php echo $user->email ?>
                </td>
            </tr>
            <tr>
                <th class="th">Fonction : </th>
                <td class="td">
                    <?php echo $user->fonction->libelle ?>
                </td>
            </tr>
            <tr>
                <th class="th">Niveau : </th>
                <td class="td">
                    <?php echo $user->fonction->niveau->libelle ?>
                </td>
            </tr>
        </table>
    </div>
    <?php
    if(isset($_SESSION["user_id"]) && ($_SESSION["user_id"] != null))
    {
        ?>
        <div class="modal-footer">
        <?php
            if($_SESSION["user_id"] == $_GET["id"])
            {
                ?>
                <button class="btn btn-link buttonMdp" id="buttonMdp"><span class="glyphicon glyphicon-wrench"></span> Modifier mot de passe</button>
                <?php
            }
            ?>
        <div class="btn-group">
            <button class="btn btn-info buttonMessage" id="buttonMessage"><span class="glyphicon glyphicon-envelope"></span> Message</button>
        </div>
        <div id="succes"><span class="glyphicon glyphicon-ok-circle"></span> Message envoyé avec succès</div>
        <div id="succesMdp"><span class="glyphicon glyphicon-ok-circle"></span> Mot de passe modifié avec succès</div>
        
        <div class="messageDiv">
            <hr/>
            <form id="messageForm">
                <div class="form-group">
                    <label>Sujet</label><input type="text" id="sujet" class="form-control" maxlength="50" required />
                    <label>Message</label><textarea class="form-control" name="message" maxlength="249" id="message"></textarea>
                    <span class="badge"><span id="nbCarac">0</span>/250</span>
                </div>
                <div class="btn-group">
                    <button class="btn btn-default" type="submit" id="envoyerMessage" disabled><span class="glyphicon glyphicon-send"></span> Envoyer</button>
                    <button class="btn btn-danger" id="annulerMessage">Annuler</button>
                </div>
            </form>
        </div>
        <div class="mdpDiv">
            <hr/>
            <form id="mdpForm">
                <div class="form-group" id="fg1">
                    <label class="mdpActuel lbl">Mot de passe actuel</label><input type="password" id="mdpActuel" class="form-control" maxlength="250" required />
                    <span id="mdpErrone" class="erreur meta">Mot de passe erroné</span>
                </div>
                <div class="form-group" id="fg2">
                    <label class="nouveauMdp lbl">Nouveau mot de passe</label><input type="password" id="newMdp1" class="form-control" maxlength="250" required />
                    <label class="nouveauMdp lbl">Confirmation nouveau mot de passe</label><input type="password" id="newMdp2" class="form-control" maxlength="250" required />
                    <label id="mdpIdentiques" class="NouveauMdp erreur">Les mots de passe ne sont pas identiques</label>
                </div>
                <div class="btn-group">
                    <button class="btn btn-default" type="submit" id="changerMdp">Modifier</button>
                    <button class="btn btn-danger" id="annulerMdp">Annuler</button>
                </div>
            </form>
        </div>
        
    </div>
        <?php
    }
    ?>
    
    
    <!--<div id="message" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Message</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>-->
    
    <script>
        $(function(){
            
            
            $("#changerMdp").click(function(e){
                $("#mdpErrone").hide();
                $("#mdpIdentiques").hide();
                $(".has-error").removeClass("has-error");
                
                e.preventDefault();
                $.post("API/verificationMdpByUtilisateurId.php", {utilisateur_id: $("#user_id").val(), mdp: $("#mdpActuel").val()}, function(data){
                    var reponse = JSON.parse(data);
                    if(reponse)
                        {
                            if($("#newMdp1").val() == $("#newMdp2").val())
                                {
                                    $.post("API/modifierMdpByUtilisateurId.php", {utilisateur_id: $("#user_id").val(), mdp: $("#newMdp1").val()}, function(data){
                                        var reponse = JSON.parse(data);
                                        if(reponse)
                                            {
                                                $(".mdpDiv").hide("fade");
                                                $("#mdpActuel").val("");
                                                $("#newMdp1").val("");
                                                $("#newMdp2").val("");
                                                $("#succesMdp").show("fade").delay(5000).hide("fade");
                                            }
                                        else{
                                            alert("Une erreur s'est produite, veuillez réessayer plus tard");
                                        }
                                    });
                                }
                            else{
                                $("#fg2").addClass("has-error");
                                $("#mdpIdentiques").show();
                            }
                        }
                    else{
                        $("#fg1").addClass("has-error");
                        $("#mdpErrone").show();
                    }
                });
            });
            
            $("#annulerMessage").click(function(e){
                e.preventDefault();
                $(".messageDiv").hide("fade");
                $("#message").val("");
                $("#sujet").val("");
                $("#nbCarac").text("0");
            });
            
            $("#annulerMdp").click(function(e){
                e.preventDefault();
                $(".mdpDiv").hide("fade");
                $("#mdpActuel").val("");
                $("#newMdp1").val("");
                $("#newMdp2").val("");
                $(".has-error").removeClass("has-error");
                $(".lbl").css("color", "black");
                $(".erreur").hide();
                
            });
            
            $(".buttonMessage").click(function(){
                $(".messageDiv").show("fade");
                $(".mdpDiv").hide("fade");
            });
            
            $(".buttonMdp").click(function(){
                $(".messageDiv").hide("fade");
                $(".mdpDiv").show("fade");
            });
            
            $("#message").on("keyup", function(){
                if($("#message").val().length > 0 && $("#message").val() !== "")
                    {
                        $("#nbCarac").text($("#message").val().length);
                        $("#envoyerMessage").prop("disabled", false);
                    }
                else{
                    $("#nbCarac").text("0");
                    $("#envoyerMessage").prop("disabled", true);
                }
            });
            
            $("#messageForm").submit(function(e){
                e.preventDefault();
                var sujet = $("#sujet").val();
                var idCorrespondant = $("#idUser").val();
                var message = $("#message").val();
                var idUser = $("#user_id").val();
                var objet = {utilisateur_id: idUser, sujet: sujet, message: message, correspondant_id: idCorrespondant};
                $.post("API/addMessage.php", objet, function(data){
                    var reponse = JSON.parse(data);
                    if(reponse)
                        {
                            $(".messageDiv").hide("fade");
                            $("#message").val("");
                            $("#sujet").val("");
                            $("#succes").show("fade").delay(5000).hide("fade");
                        }
                    else{
                        alert("Une erreur c'est produite, veuillez réessayer plus tard.");
                    }
                });
            });
        });
    </script>
</body>
