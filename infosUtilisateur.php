<head>
    <style>
        th{
            text-align: right;
        }
        td{
            padding-left: 10px;
        }
        #messageDiv{
            display: none;
        }
        #emailDiv{
            display: none;
        }
        #succes{
            display: none;
            color: green;
        }
    </style>
</head>

<body>

    <input type="hidden" name="idUser" id="idUser" value="<?php echo $_GET["id"] ?>" />

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <img src="<?php echo $_GET["photo"] ?>" width="100" height="130">
        <b><?php echo strtoupper($_GET["nom"])." ".strtoupper(substr($_GET["prenom"], 0, 1)).strtolower(substr($_GET["prenom"], 1)) ?></b>
    </div>
    <div class="modal-body">
        <div id="test"></div>
        <table>
            <tr>
                <th>Email : </th>
                <td>
                    <?php echo $_GET["email"] ?>
                </td>
            </tr>
            <tr>
                <th>Fonction : </th>
                <td>
                    <?php echo $_GET["fonction"] ?>
                </td>
            </tr>
            <tr>
                <th>Niveau : </th>
                <td>
                    <?php echo $_GET["niveau"] ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <div class="btn-group">
            <button class="btn btn-info" id="buttonMessage"><span class="glyphicon glyphicon-envelope"></span> Message</button>
        </div>
        <div id="succes"><span class="glyphicon glyphicon-ok-circle"></span> Message envoyé avec succès</div>
        
        <div id="messageDiv">
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
        
    </div>
    
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
            $("#annulerMessage").click(function(e){
                e.preventDefault();
                $("#messageDiv").hide("fade");
                $("#message").val("");
                $("#sujet").val("");
            });
            
            $("#buttonMessage").click(function(){
                $("#messageDiv").show("fade");
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
                var idUser = $("#idUser").val();
                var message = $("#message").val();
                var idCorrespondant = $("#user_id").val();
                var objet = {utilisateur_id: idUser, sujet: sujet, message: message, correspondant_id: idCorrespondant};
                $.post("API/addMessage.php", objet, function(data){
                    var reponse = JSON.parse(data);
                    if(reponse)
                        {
                            $("#messageDiv").hide("fade");
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
