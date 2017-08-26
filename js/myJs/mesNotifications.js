$(function(){
    $(".delete").click(function(){
        var id = $(this).attr("id");
        $.post("API/deleteNotificationUtilisateurById.php", {notification_utilisateur_id: id}, function(data){
            var reponse = JSON.parse(data);
            if(reponse){
                $("#panel" + id).hide("slide");
            }
            else{
                alert("Une erreur s'est produite, veuillez réessayer plus tard");
            }
        });
    });
    
    $("#toutSupprimer").click(function(e){
        e.preventDefault();
        $.post("API/deleteAllAnciennesNotificationsUtilisateur.php", {utilisateur_id: $("#user_id").val()}, function(data){
            var reponse = JSON.parse(data);
            if(reponse){
                $(".ancien").hide("slide");
                $("#toutSupprimer").prop("disabled", true);
                var labelElt = document.createElement("label");
                labelElt.classList += "label label-info";
                labelElt.textContent = "Aucune notification";
                $(labelElt).appendTo($("#anciennesNotifs"));
            }
            else{
                alert("Une erreur s'est produite, veuillez réessayer plus tard");
            }
        });
    });
});