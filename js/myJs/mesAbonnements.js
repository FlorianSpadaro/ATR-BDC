$(function(){
    $(".lienPanel").click();
    $('a').tooltip({ trigger: "hover" });
    
    function actualiserAbonnements(){
        $(".abonner").off("click");
        $(".desabonner").off("click");
        
        $.post("API/getSecteursDomainesSousDomainesProjets.php", {utilisateur_id: $("#idUser").val()}, function(data){
            var elems = JSON.parse(data);
            elems.forEach(function(secteur){
                $("#domainesAboSecteur" + secteur.id).text(secteur.nbDomainesAbo);
                $("#sousDomainesAboSecteur" + secteur.id).text(secteur.nbSousDomainesAbo);
                $("#projetsAboSecteur" + secteur.id).text(secteur.nbProjetsAbo);
                secteur.domaine.forEach(function(domaine){
                    $("#sousDomainesAboDomaine" + domaine.id).text(domaine.nbSousDomainesAbo);
                    $("#projetsAboDomaine" + domaine.id).text(domaine.nbProjetsAbo);
                    domaine.sous_domaine.forEach(function(sd){
                        $("#projetsAboSousDomaine" + sd.id).text(sd.nbProjetsAbo);
                    });
                });
            });
        });
        
        
        $.post("API/getAbonnementsByUtilisateurId.php", {utilisateur_id: $("#idUser").val()}, function(data){
            var abonnements = JSON.parse(data);
            $(".panel-success").removeClass("panel-success").addClass("panel-default").find(".desabonner").removeClass("desabonner").addClass("abonner").html("S'abonner <span class=\"glyphicon glyphicon-plus-sign\"></span>");
            $(".list-group-item-success").removeClass("list-group-item-success");
            /*$(".desabonner").each(function(){
                var tab = $(this).attr("id").split("-");
                var idAbo = tab[1];
                console.log(idAbo);
                var elt = $(this);
                $.post("API/getIdElementByAbonnementId.php", {abonnement_id: idAbo}, function(data){
                    var idElt = JSON.parse(data);
                    elt.removeClass("desabonner").addClass("abonner").html("S'abonner <span class=\"glyphicon glyphicon-plus-sign\"></span>").attr("id", tab[0] + "-" + idElt);
                });
            });*/
            $(".desabonner").removeClass("desabonner").addClass("abonner").html("S'abonner <span class=\"glyphicon glyphicon-plus-sign\"></span>");
            if(abonnements != null)
                {
                        abonnements.forEach(function(abo){
                        if(abo.secteur_id != null)
                            {
                                $("#enteteSecteur" + abo.secteur_id).removeClass("panel-default").addClass("panel-success");
                                $("#enteteSecteur" + abo.secteur_id + " .abonner:first").html('Se désabonner <span class="glyphicon glyphicon-remove"></span>').removeClass("abonner").addClass("desabonner");
                            }
                        else if(abo.domaine_id != null)
                            {
                                $("#enteteDomaine" + abo.domaine_id).removeClass("panel-default").addClass("panel-success");
                                $("#enteteDomaine" + abo.domaine_id + " .abonner:first").html('Se désabonner <span class="glyphicon glyphicon-remove"></span>').removeClass("abonner").addClass("desabonner");
                            }
                        else if(abo.sous_domaine_id != null)
                            {
                                $("#enteteSousDomaine" + abo.sous_domaine_id).removeClass("panel-default").addClass("panel-success");
                                $("#enteteSousDomaine" + abo.sous_domaine_id + " .abonner:first").html('Se désabonner <span class="glyphicon glyphicon-remove"></span>').removeClass("abonner").addClass("desabonner");
                            }
                        else if(abo.projet_id != null)
                            {
                                if($("#enteteProjet" + abo.projet_id).length > 0)
                                    {
                                        $("#enteteProjet" + abo.projet_id).addClass("list-group-item-success");
                                        $("#enteteProjet" + abo.projet_id + " .abonner:first").html('Se désabonner <span class="glyphicon glyphicon-remove"></span>').removeClass("abonner").addClass("desabonner");
                                    }
                                else{
                                    $(".enteteProjetGenerique" + abo.projet_id).addClass("list-group-item-success");
                                    $(".enteteProjetGenerique" + abo.projet_id + " .abonner").html('Se désabonner <span class="glyphicon glyphicon-remove"></span>').removeClass("abonner").addClass("desabonner");
                                }
                            }
                        else if(abo.contrat_id != null){
                            $("#enteteContrat" + abo.contrat_id).addClass("list-group-item-success");
                            $("#enteteContrat" + abo.contrat_id + " .abonner:first").html('Se désabonner <span class="glyphicon glyphicon-remove"></span>').removeClass("abonner").addClass("desabonner");
                        }

                    });
                }
            $(".desabonner").click(function(e){
                e.preventDefault();
                
                /*var tab = $(this).attr("id").split("-");
                var idAbo = tab[1];
                var elt = $(this);
                $.post("API/getIdElementByAbonnementId.php", {abonnement_id: idAbo}, function(data){
                    var idElt = JSON.parse(data);
                    elt.removeClass("desabonner").addClass("abonner").html("S'abonner <span class=\"glyphicon glyphicon-plus-sign\"></span>").attr("id", tab[0] + "-" + idElt);
                });*/
                
                var elt = $(this);
                elt.removeClass("desabonner").addClass("abonner").html("S'abonner <span class=\"glyphicon glyphicon-plus-sign\"></span>");
                elt.closest(".panel").find(".desabonner").removeClass("desabonner").addClass("abonner").html("S'abonner <span class=\"glyphicon glyphicon-plus-sign\"></span>");
                /*var tab = elt.attr("id").split("-");
                var id = tab[1];*/
                var id = elt.attr("id");
                
                $(this).replaceWith("<img src='img/wait.gif' height='16' width='16' class='pull-right' id='imageAttente' />");
                
                $.post("API/removeAbonnementById.php", {abonnement_id: id, utilisateur_id: $("#idUser").val()}, function(data){
                    console.log("removeAbo: " + data);
                    var reponse = JSON.parse(data);
                    $("#imageAttente").replaceWith(elt);
                    if(reponse){
                        
                        actualiserAbonnements();
                    }else{
                        alert("Une erreur s'est produite, veuillez réessayer plus tard");
                    }
                });
            });

            $(".abonner").click(function(e){
                e.preventDefault();
                
                var elt = $(this);
                
                var tab = $(this).attr("id").split("-");
                $(this).replaceWith("<img src='img/wait.gif' height='16' width='16' class='pull-right' id='imageAttente' />");

                var objet = {
                    utilisateur_id: $("#idUser").val(),
                    secteur_id: "null",
                    domaine_id: "null",
                    sous_domaine_id: "null",
                    projet_id: "null",
                    contrat_id: "null"
                };

                switch(tab[0])
                {
                    case 'secteur':
                        objet.secteur_id = tab[1];
                        break;
                    case 'domaine':
                        objet.domaine_id = tab[1];
                        break;
                    case 'sousDomaine':
                        objet.sous_domaine_id = tab[1];
                        break;
                    case 'projet':
                        objet.projet_id = tab[1];
                        break;
                    case 'contrat':
                        objet.contrat_id = tab[1];
                        break;
                }
                
                $.post("API/addAbonnement.php", objet, function(data){
                    console.log("addAbo: " + data);
                    var reponse = JSON.parse(data);
                    $("#imageAttente").replaceWith(elt);
                    if(reponse){
                        actualiserAbonnements();
                    }else{
                        alert("Une erreur s'est produite, veuillez réessayer plus tard");
                    }

                });
            });
        });
        
    }
    
    actualiserAbonnements();
});