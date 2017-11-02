$(function () {
    $("#formulaireHabilElec").click(function(e){
        e.preventDefault();
        $.post("API/getDernierFormulaireByUtilisateurId.php", {utilisateur_id: $("#user_id").val()}, function(data){
            var formulaire = JSON.parse(data);
            if(formulaire != null)
            {
                var dte = new Date();
                var dteForm = new Date(formulaire.date.slice(0, 4), formulaire.date.slice(5, 7), formulaire.date.slice(8, 10));
                if(formulaire.brouillon == false && formulaire.valider != false && dte < dteForm)
                {
                    if(formulaire.date_expiration == null)
                    {
                        alert("Vous avez déjà répondu à ce formulaire. Merci d'attendre qu'un administrateur valide vos réponses");
                    }
                    else{
                        alert("Votre formulaire a été validé. Fin de validité: " + formulaire.date_expiration.slice(0, 4) + "/" + formulaire.date_expiration.slice(5, 7) + "/" + formulaire.date_expiration.slice(8, 10));
                    }
                }
                else{
                    document.location.href = "habilitationElectrique.php";
                }
            }
            else{
                document.location.href = "habilitationElectrique.php";
            }
        });
    });

    $("#searchBarOption").hide();
    $("#searchBar").focus(function(e){
        $(this).animate({width:"741px"},500);
    });
    $("#searchBar").click(function(e){
        e.stopPropagation();
        e.preventDefault();
    });
    
    $("#listContratHeader").chosen({width: "inherit", width: "100%",placeholder_text_multiple:"Tous contrats"});
    
    $(".contratSelectHeader").click(function(e){
        e.stopPropagation();
        e.preventDefault();
    });
    $("body").click(function(){
        $("#searchBar").animate({width:"200px"},500);
        $("#searchBarOption").hide();
        $("#searchBar").val("");
    });
    
    var userId = document.getElementById("user_id").value;
    $.post("API/getNbMessagesNonLuByUtilisateurId.php", {utilisateur_id: userId}, function(data){
        var nbNonLu = parseInt(JSON.parse(data));
        $("#nbMessages").text(nbNonLu);
        $.post("API/getNbNotifsNonVuesByUtilisateurId.php", {utilisateur_id: userId}, function(data2){
            var notifsNonLu = parseInt(JSON.parse(data2));
            $("#nbNotifs").text(notifsNonLu);
            nbNonLu += notifsNonLu;
            if(nbNonLu > 0)
            {
                $("#totalNotif").text(nbNonLu).show();
            }
        });
    });

    $("#attenteConnexion").hide();

    $("#boutonConnexion").click(function (e) {
        e.preventDefault();
        $("#attenteConnexion").show();

        var connexion = {
            login: $("#login").val(),
            mdp: $("#mdp").val()
        };

        $.post("API/connexion.php", connexion, function (data) {
            $("#attenteConnexion").hide();
            var user = JSON.parse(data);

            if (user === null) {
                $("#connexionFooter").css("color", "red").text("Login ou mot de passe incorrect");
            } else {
                document.getElementById("user_id").value = user.id;
                $("#formConnexion").submit();
            }
        });
    });

    $("#dropdownMonCompte").mouseover(function () {
        $(this).dropdown("toggle");
    });

    $('.dropdown-submenu a.test').on("click", function (e) {
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
    
    $(".newDomaine").click(function(){
        var idSecteur = $(this).attr("id").split("-")[1];
        $("#idSecteurNewDomaine").val(idSecteur);
    });
    
    $("#btnAnnulerNewDomaine").click(function(){
        $("#libelleNewDomaine").val("");
        $("#idSecteurNewDomaine").val("");
        $("#descriptionNewDomaine").val("");
    });
    
    $("#btnValiderNewDomaine").click(function(e){
        e.preventDefault();
        if($("#libelleNewDomaine").val() == "")
            {
                alert("Veuillez choisir un libellé");
            }
        else{
            var libelle = $("#libelleNewDomaine").val();
            var idSecteur = $("#idSecteurNewDomaine").val();
            var idUser = $("#user_id").val();
            if($("#descriptionNewDomaine").val() == "")
                {
                    var description = null;
                }
            else{
                var description = $("#descriptionNewDomaine").val();
            }
            $.post("API/addDomaine.php", {libelle: libelle, utilisateur_id: idUser, description: description, secteur_id: idSecteur}, function(data){
                var reponse = JSON.parse(data);
                if(reponse){
                    window.location.reload();
                }
                else{
                    alert("Une erreur s'est produite, veuillez réessayer plus tard");
                }
            });
        }
    });
   
    $.post("API/getContratByDomaineId.php",function(data){
        var contrats = JSON.parse(data);
        if(contrats != null)
        {
            contrats.forEach(function(contrat){
                $("#domaine" + contrat.domaine_id).addClass("contrat_" + contrat.contrat_id);
            })
        }
        /*else
        {
            alert("Une erreur s'est produite, veuillez réessayer plus tard");
        }*/
    });
    $("#listContratHeader").change(function(){
        var list_contrats = []
        list_contrats = $("#listContratHeader").val();
        $("#secteur2 .domaine_").hide()
        if(list_contrats == null){
            $("#secteur2 .domaine_").show()  
        }
        else
        {
            list_contrats.forEach(function(list_contrat){
                $("#secteur2 .contrat_" + list_contrat).show();
            })
        }
    })
    $(".domaine_").click(function(){
        if($("#listContratHeader").val() != null){
            var new_href = $(this).children().attr("href") + "&contrats=" + $("#listContratHeader").val();
            $(this).children().attr("href",new_href);
        }
       console.log($(this).children().attr("href"));
    })
    $("#searchBar").on("input",function(){
        $.post("API/getSearchProjetBySearchBar.php",{search_text: $("#searchBar").val()}, function(data){
            var searchResult = JSON.parse(data);
            var i = 1;
            var resultSearch = "<optgroup label='Projet:'>";
            $("#searchBarOption").html(null);
            if(searchResult != null)
                {
                    for( i ; i <= searchResult.length;i++){
               resultSearch += '<option class="searchOption" projet="'+searchResult[i - 1].id+'">'+searchResult[i - 1].titre+'</option>';
                     
            }
                }
            else
                {
                    resultSearch += '<option class="searchOption noResultOption" disabled>Pas de résultats</option>';
                }
            $("#searchBarOption").append( resultSearch + '</optgroup><optgroup label="Autres:"><option value="'+$("#searchBar").val()+'" id="searchOptionProjetId" class="searchOption searchOptionProjet" projet="projet">Rechercher "'+$("#searchBar").val()+'" dans le contenu des projets</option></optgroup>');
           

            $("#searchBarOption").attr('size',5);
            if($("#searchBar").val() != "")
                {
                    $("#searchBarOption").fadeIn("fast")
                    if(searchResult != null){
                        $("#searchBarOption").attr('size',searchResult.length + 3)
                    }
                    else
                    {
                         $("#searchBarOption").attr('size',4)
                    }
                    
                }
            else
                {
                    $("#searchBarOption").hide()
                }
            
        })
         $.post("API/getSearchProjetByProjectSearch.php",{search_text: $("#searchBar").val()}, function(data){
             var result = JSON.parse(data);
             if(result == null){
                 console.log("0");
                 $("#searchOptionProjetId").append(" (0)");
             }
             else
            {
                console.log(result.length);
                $("#searchOptionProjetId").append(" (" + result.length + ")");
            }
             
         });
    });
    

        $("#searchBarOption").click(function() {
            var projet_id = $('option:selected', this).attr('projet');
            if (projet_id == "projet" || projet_id == "actu") 
            {
                if(projet_id == "projet")
                {
                    document.location.href = "projets.php?searchbar=" + $("#searchBar").val();
                }
            } else 
            {
                document.location.href = "projet.php?id=" + projet_id;
            }

        })
});
