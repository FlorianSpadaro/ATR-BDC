$(function () {
    
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

});
