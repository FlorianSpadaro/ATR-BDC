$(function(){
    $(".listeNiveaux").change(function(){
        var elt = $(this);
        var idFonction = elt.attr("id").split("-")[1];
        var idNiveau = elt.val().split("-")[1];
        var repUser = confirm("Voulez-vous vraiment modifier les droits?");
        if(repUser)
            {
                $.post("API/modifierNiveauFonction.php", {fonction_id: idFonction, niveau_id: idNiveau}, function(data){
                    var reponse = JSON.parse(data);
                    if(!reponse)
                        {
                            $.post("API/getNiveauByFonctionId.php", {fonction_id: idFonction}, function(data){
                                var niveau = JSON.parse(data);
                                elt.val("niveau-" + niveau.id);
                            });
                            alert("Une erreur s'est produite, veuillez réessayer plus tard");
                        }
                });
            }
        else{
            $.post("API/getNiveauByFonctionId.php", {fonction_id: idFonction}, function(data){
                var niveau = JSON.parse(data);
                elt.val("niveau-" + niveau.id);
            });
        }
    });
});