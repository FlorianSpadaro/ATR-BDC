$(function () {
    
    $("#listContratHeader").chosen({width: "inherit", width: "100%",placeholder_text_multiple:"Tous contrats"});
    
    $(".contratSelectHeader").click(function(e){
        e.stopPropagation();
        e.preventDefault();
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
        else
        {
            alert("Une erreur s'est produite, veuillez réessayer plus tard");
        }
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
});
