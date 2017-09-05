$(function(){
    $('input[name=miniatureNC]:first').click();
    $("#validerNouveauContrat").prop("disabled", true);
    
    $("#libelleContrat").on("keyup", function(){
        if($(this).val() != "")
            {
                $("#validerModifContrat").prop("disabled", false);
            }
        else{
            $("#validerModifContrat").prop("disabled", true);
        }
    });
    
    $("#libelleNouveauContrat").on("keyup", function(){
        if($(this).val() != "")
            {
                $("#validerNouveauContrat").prop("disabled", false);
            }
        else{
            $("#validerNouveauContrat").prop("disabled", true);
        }
    });
    
    $("#annulerNouveauContrat").click(function(){
        $('input[name=miniatureNC]:first').click();
        $("#libelleNouveauContrat").val("");
        $("#validerNouveauContrat").prop("disabled", true);
    });
    
    $("#validerNouveauContrat").click(function(e){
        e.preventDefault();
        var libelle = $("#libelleNouveauContrat").val();
        var idUser = $("#user_id").val();
        var idMiniature = $('input[name=miniatureNC]:checked').val().split("-")[1];
        $.post("API/addContrat.php", {utilisateur_id: idUser, libelle: libelle, miniature_id: idMiniature}, function(data){
            var reponse = JSON.parse(data);
            if(reponse){
                document.location.href = "contrats.php";
            }
            else{
                alert("Une erreur s'est produite, veuillez réessayer plus tard");
            }
        });
    });
    
    $("#btnModifierContrat").prop("disabled", true).show();
    $("#btnSupprimerContrat").prop("disabled", true).show();
    
    $(".unContrat").click(function(e){
        e.preventDefault();
        $("#listeContrats .active").removeClass("active");
        $(this).addClass("active");
        $("#btnModifierContrat").removeProp("disabled");
        $("#btnSupprimerContrat").removeProp("disabled");
    });
    
    $("#btnSupprimerContrat").click(function(){
        var reponse = confirm("Voulez-vous vraiment supprimer ce contrat?");
        if(reponse)
            {
                var id = $("#listeContrats .active").attr("id").split("-")[1];
                $.post("API/removeContratById.php", {contrat_id: id}, function(data){
                    var reponse = JSON.parse(data);
                    if(reponse)
                        {
                            $("#contrat-" + id).remove();
                            $("#btnModifierContrat").prop("disabled", true);
                            $("#btnSupprimerContrat").prop("disabled", true);
                        }
                    else{
                        alert("Une erreur s'est produite, veuillez réessayer plus tard");
                    }
                });
            }
    });
    
    $("#btnModifierContrat").click(function(){
        var id = $("#listeContrats .active").attr("id").split("-")[1];
        $.post("API/getContratById.php", {contrat_id: id}, function(data){
            var contrat = JSON.parse(data);
            if(contrat != null)
                {
                    $("#libelleContrat").val(contrat.libelle);
                    $("#miniature-" + contrat.miniature.id).click();
                }
        });
    });
    
    $("#validerModifContrat").click(function(e){
        e.preventDefault();
        var idContrat = $("#listeContrats .active").attr("id").split("-")[1];
        var libelle = $("#libelleContrat").val();
        var idMiniature = $('input[name=miniature]:checked').val().split("-")[1];
        $.post("API/modifierContrat.php", {contrat_id: idContrat, libelle: libelle, miniature_id: idMiniature}, function(data){
            var reponse = JSON.parse(data);
            if(reponse)
                {
                    document.location.href = "contrats.php";
                }
            else{
                alert("Une erreur s'est produite, veuillez réessayer plus tard");
            }
        });
    });
});