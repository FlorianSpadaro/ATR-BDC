$(function () {
    $("#reponse").on("keyup", function () {
        if ($("#reponse").val().length > 0 && $("#reponse").val() !== "") {
            $("#nbCarac").text($("#reponse").val().length);
            $("#envoyerReponse").prop("disabled", false);
        } else {
            $("#nbCarac").text("0");
            $("#envoyerReponse").prop("disabled", true);
        }
    });
    
    $("#envoyerReponse").click(function(e){
        e.preventDefault();
        var idMessage = $("#idMessage").val();
        var idUser = $("#user_id").val();
        var reponse = $("#reponse").val();
        var objet = {message_id: idMessage, utilisateur_id: idUser, reponse: reponse};
        $.post("API/addReponseMessage.php", objet, function(data){
            var reponse2 = JSON.parse(data);
            if(reponse2)
                {
                    var panelElt = document.createElement("div");
                    panelElt.classList += "panel panel-warning row";
                    
                    var panelHead = document.createElement("div");
                    panelHead.classList += "panel-heading";
                    var panelTitle = document.createElement("h1");
                    panelTitle.classList += "panel-title";
                    panelTitle.textContent = $("#sujet").val();
                    panelHead.appendChild(panelTitle);
                    panelElt.appendChild(panelHead);
                    
                    var panelBody = document.createElement("div");
                    panelBody.classList += "panel-body";
                    panelBody.textContent = reponse;
                    panelElt.appendChild(panelBody);
                    
                    $(panelElt).hide().insertBefore($("#formulaire")).show("fold");
                    
                    $("#reponse").val("");
                    $("#nbCarac").text("0");
                    $("#envoyerReponse").prop("disabled", true);
                    
                    /*var panelFooter = document.createElement("div");
                    panelFooter.classList += "panel-footer pull-right";
                    panelFooter.innerHTML = 'Envoyé par <a data-toggle="modal" href="infosUtilisateur.php?id=' + idUser + '" data-target="#infos">' + NOM Prenom + '</a> le <?php echo $reponse->date->jour." à ".$reponse->date->heure ?>';*/
                    
                    if(idUser == $("#idReceveur").val())
                        {
                            $.post("API/addMessageRecu.php", {message_id: $("#idMessage").val(), utilisateur_id: $("#idEnvoyeur").val(), correspondant_id: $("#idReceveur").val()});
                        }
                }
            else{
                alert("Une erreur s'est produite, veuillez réessayer plus tard");
            }
        });
    });
    
});
