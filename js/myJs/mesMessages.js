$(function () {
    $("#nouveauMessage").on("keyup", function(){
                if($(this).val().length > 0 && $(this).val() !== "")
                    {
                        $("#nbCaracNw").text($(this).val().length);
                        $("#envoyerMessage").prop("disabled", false);
                    }
                else{
                    $("#nbCaracNw").text("0");
                }
            });
    
    $("#formNouveauMessage").submit(function(e){
        e.preventDefault();
        var nbDestinataires = $(".destinataires").length;
        if(nbDestinataires > 0)
            {
                $(".destinataires").each(function(){
                    var idCorrespondant = $(this).attr("id").split("-")[1];
                    var sujet = $("#nouveauSujet").val();
                    var message = $("#nouveauMessage").val();
                    var idUser = $("#user_id").val();
                    $.post("API/addMessage.php", {utilisateur_id: idUser, correspondant_id: idCorrespondant, sujet: sujet, message: message}, function(data){
                        //console.log(data);
                    });
                });
                $("#annulerRedigerNouveauMessage").click();
            }
        else{
            $("#erreurDestinataires").show();
        }
    });
    
    $("#annulerRedigerNouveauMessage").click(function(e){
        $(".destinataires").remove();
        document.getElementById("formNouveauMessage").reset();
        $("#nbCaracNw").text("0");
    });
    
    $("#supprimerSelection").prop("disabled", true);
    
    $("#allMessagesRecus").click(function(){
        if($(this).is(":checked"))
            {
                $(".selectionMessageRecus").each(function(){
                    $(this).prop("checked", "checked");
                });
                $("#supprimerSelection").prop("disabled", false);
            }
        else{
            $(".selectionMessageRecus").each(function(){
                    $(this).removeProp("checked");
                });
            $("#supprimerSelection").prop("disabled", true);
        }
    });
    
    $(".selectionMessageRecus").click(function(){
        var nb = 0;
        $(".selectionMessageRecus:checked").each(function(){
            nb++;
        });
        if(nb>0)
            {
                $("#allMessagesRecus").prop("checked", "checked");
                $("#supprimerSelection").prop("disabled", false);
            }
        else{
            $("#allMessagesRecus").removeProp("checked");
            $("#supprimerSelection").prop("disabled", true);
        }
    });
    
    $("#supprimerSelection").click(function(){
        var tab = [];
        $(".selectionMessageRecus:checked").each(function(){
            tab.push($(this).attr("id"));
        });
        if(tab.length > 1)
            {
                $("#nbMessagesSelectionne").text(tab.length);
            }
        else{
            $("#nbMessagesSelectionne").text("");
        }
        $('#messageSuppression').modal('show');
        $("#confirmationSuppression").click(function(){
            tab.forEach(function(id){
                $.post("API/deleteMessageRecuById.php", {message_recu_id: id}, function(){
                    console.log(id);
                    $("#"+id).removeProp("checked");
                    $("#messageRecu"+id).hide();
                });
            });
            $("#allMessagesRecus").removeProp("checked");
            $('#messageSuppression').modal('hide');
        });
    });
    
    //ENVOYER
    
    
    $("#supprimerSelectionEnvoyes").prop("disabled", true);
    
    $("#allMessagesEnvoyes").click(function(){
        if($(this).is(":checked"))
            {
                $(".selectionMessagesEnvoyes").each(function(){
                    $(this).prop("checked", "checked");
                });
                $("#supprimerSelectionEnvoyes").prop("disabled", false);
            }
        else{
            $(".selectionMessagesEnvoyes").each(function(){
                    $(this).removeProp("checked");
                });
            $("#supprimerSelectionEnvoyes").prop("disabled", true);
        }
    });
    
    $(".selectionMessagesEnvoyes").click(function(){
        var nb = 0;
        $(".selectionMessagesEnvoyes:checked").each(function(){
            nb++;
        });
        if(nb>0)
            {
                $("#allMessagesEnvoyes").prop("checked", "checked");
                $("#supprimerSelectionEnvoyes").prop("disabled", false);
            }
        else{
            $("#allMessagesEnvoyes").removeProp("checked");
            $("#supprimerSelectionEnvoyes").prop("disabled", true);
        }
    });
    
    $("#supprimerSelectionEnvoyes").click(function(){
        var tab2 = [];
        $(".selectionMessagesEnvoyes:checked").each(function(){
            tab2.push($(this).attr("id"));
        });
        if(tab2.length > 1)
            {
                $("#nbMessagesSelectionne").text(tab2.length);
            }
        else{
            $("#nbMessagesSelectionne").text("");
        }
        $('#messageSuppression').modal('show');
        $("#confirmationSuppression").click(function(){
            tab2.forEach(function(id){
                $.post("API/deleteMessageEnvoyeById.php", {message_envoye_id: id}, function(){
                    $("#"+id).removeProp("checked");
                    $("#messageEnvoye"+id).hide();
                });
            });
            $("#allMessagesEnvoyes").removeProp("checked");
            $('#messageSuppression').modal('hide');
        });
    });
    
    
    $("#destinataires").autocomplete({source: function(requete, reponse){
        $.post("API/getNomPrenomUtilisateurBySearch.php", {recherche: $("#destinataires").val()}, function(data){
            var users = JSON.parse(data);
            var tab = [];
            if(users != null)
                {
                    users.forEach(function(user){
                        var utilisateur = {};
                        utilisateur.value = user.nom + " " + user.prenom;
                        utilisateur.label = user.nom + " " + user.prenom;
                        utilisateur.id = user.id;
                        tab.push(utilisateur);
                    });
                }
            reponse(tab);
        });
    }, select: function(event, ui){
        event.preventDefault();
        var idUser = $("#label-" + ui.item.id);
        if(idUser.length == 0)
            {
                var lblElt = document.createElement("label");
                lblElt.classList += "destinataires label label-info";
                lblElt.id = "label-" + ui.item.id;
                lblElt.innerHTML = "<a href='#' id='user-" + ui.item.id + "'><span class=\"glyphicon glyphicon-remove-sign\"></span>" + ui.item.value + "</a>";
                document.getElementById("listeDestinataires").appendChild(lblElt);
                document.getElementById("destinataires").value = "";
                document.getElementById("user-" + ui.item.id).addEventListener("click", function(e){
                    e.preventDefault();
                    $("#label-" + ui.item.id).remove();
                });
            }
        else{
            alert("Vous avez déjà sélectionné cet utilisateur");
        }
    }});
});
