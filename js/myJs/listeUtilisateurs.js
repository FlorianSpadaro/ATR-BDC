$(function(){
    $("#listeUtilisateurs").tablesorter();
    
    $('[data-toggle="tooltip"]').tooltip();
    $(".modifierUser").tooltip();
    
    $(".submitGererAbo").click(function(e){
        e.preventDefault();
        $(this).closest("form").submit();
    });
    
    $("#reinitialiserMdpUser").click(function(){
        var repUser = confirm("Voulez-vous vraiment réinitialiser le mot de passe de cet utilisateur?\nUn nouveau mot de passe sera alors créé aléatoirement et envoyé par mail à l'utilisateur");
        if(repUser)
            {
                $("#attenteReinitialiserMdp").show();
                $("#reinitialiserMdpUser").prop("disabled", true);
                
                $.post("API/motDePasseAleatoire.php", {nb_caracteres: 10}, function(data){
                    var mdp = JSON.parse(data);
                    $.post("API/modifierMdpByUtilisateurId.php", {utilisateur_id: $("#idUtilisateurModif").val(), mdp: mdp}, function(data2){
                        console.log(data2);
                        var reponse = JSON.parse(data2);
                        if(reponse)
                            {
                                alert("Mot de passe réinitialisé");
                                $("#attenteReinitialiserMdp").hide();
                                $("#reinitialiserMdpUser").prop("disabled", false);
                            }
                        else{
                            alert("Une erreur s'est produite, veuillez réessayer plus tard");
                        }
                    });
                });
            }
    });
    
    $(".titreTab").click(function(e){
        e.preventDefault();
    });
    
    $(".supprimerUser").click(function(e){
        e.preventDefault();
        var id = $(this).closest("tr").attr("id").split("-")[1];
        $.post("API/getUtilisateurById.php", {user_id: id}, function(data2){
            var user = JSON.parse(data2);
            if(user != null)
                {
                    var repUser = confirm("Voulez-vous vraiment supprimer l'utilisateur " + user.nom.toUpperCase() + " " + user.prenom.charAt(0).toUpperCase() + user.prenom.slice(1).toLocaleLowerCase() + " ?");
                    if(repUser)
                        {
                            $.post("API/removeUtilisateurById.php", {utilisateur_id: id}, function(data){
                                var reponse = JSON.parse(data);
                                if(reponse)
                                    {
                                        document.location.href = "listeUtilisateurs.php";
                                    }
                                else{
                                    alert("Une erreur s'est produite, veuillez réessayer plus tard");
                                }
                            });
                        }
                }
        });
    });
    
    $(".modifierUser").click(function(){
        var id = $(this).closest("tr").attr("id").split("-")[1];
        $("#idUtilisateurModif").val(id);
        $.post("API/getUtilisateurById.php", {user_id: id}, function(data){
            var user = JSON.parse(data);
            var nom = user.nom.toUpperCase();
            var prenom = user.prenom.charAt(0).toUpperCase() + user.prenom.slice(1).toLowerCase();
            document.getElementById("photoUtilisateurModif").src = user.photo;
            $("#nomUtilisateurModif").val(nom);
            $("#prenomUtilisateurModif").val(prenom);
            $("#emailUtilisateurModif").val(user.email);
            $("#fonctionUtilisateurModif").val("fonction-" + user.fonction.id);
            $("#niveauUtilisateurModif").text(user.fonction.niveau.libelle);
        });
    });
    
    $("#fonctionUtilisateurModif").change(function(){
        var id = $(this).val().split("-")[1];
        $.post("API/getFonctionById.php", {fonction_id: id}, function(data){
            var fonction = JSON.parse(data);
            $("#niveauUtilisateurModif").text(fonction.niveau.libelle);
        });
    });
    
    $("#ajouterFonctionUtilisateurModif").click(function(e){
        e.preventDefault();
        $(this).hide("fade");
        $("#divAjouterFonctionModif").show("fade");
    });
    
    $("#annulerNouvelleFonctionModif").click(function(e){
        e.preventDefault();
        $("#divAjouterFonctionModif").hide("fade");
        $("#ajouterFonctionUtilisateurModif").show("fade");
        $("#libelleFonctionModif").val("");
        $("#niveauNouvelleFonctionModif option:first").prop("selected", true);
    });
    
    $("#validerNouvelleFonctionModif").click(function(e){
        e.preventDefault();
        $("#erreurLibelleNouvelleFonctionModif").hide();
        if($("#libelleFonctionModif").val() == "")
            {
                $("#erreurLibelleNouvelleFonctionModif").show();
            }
        else{
            var libelle = $("#libelleFonctionModif").val();
            var idNiveau = $("#niveauNouvelleFonctionModif").val().split("-")[1];
            $.post("API/addFonction.php", {libelle: libelle, niveau_id: idNiveau}, function(data){
                var idFonction = JSON.parse(data);
                var optionElt = document.createElement("option");
                optionElt.value = "fonction-" + idFonction;
                optionElt.textContent = $("#libelleFonctionModif").val();
                document.getElementById("fonctionUtilisateurModif").appendChild(optionElt);
                $("#fonctionUtilisateurModif").val("fonction-" + idFonction);
                $("#niveauUtilisateurModif").text();
                $.post("API/getNiveauById.php", {niveau_id: idNiveau}, function(data){
                    var niveau = JSON.parse(data);
                    $("#niveauUtilisateurModif").text(niveau.libelle);
                    $("#annulerNouvelleFonctionModif").click();
                });
            });
        }
    });
    
    $("#nouvellePhotoUtilisateurModif").change(function(){
        var reader = new FileReader();
        
        var fichier = document.getElementById("nouvellePhotoUtilisateurModif").files[0];
        if(fichier.type.split("/")[0] == "image")
            {
                reader.addEventListener('load', function(){
                    document.getElementById("photoUtilisateurModif").src = this.result;
                });
                reader.readAsDataURL(fichier);
            }
        else{
            alert("Veuillez choisir une image");
            $("#nouvellePhotoUtilisateurModif").val("");
        }
    });
    
    $("#annulerModifUtilisateur").click(function(){
        $("#nouvellePhotoUtilisateurModif").val("");
        $(".erreurModifUser").hide();
    });
    
    $("#validerModifUtilisateur").click(function(e){
        e.preventDefault();
        $(".erreurModifUser").hide();
        if($("#nomUtilisateurModif").val() == "")
            {
                $("#erreurNomUserModif").show("fade");
            }
        else if($("#prenomUtilisateurModif").val() == ""){
            $("#erreurPrenomUserModif").show("fade");
        }
        else if($("#emailUtilisateurModif").val() == ""){
            $("#erreurEmailUserModif").show("fade");
        }
        else{
            var idUser = $("#idUtilisateurModif").val();
            var nom = $("#nomUtilisateurModif").val();
            var prenom = $("#prenomUtilisateurModif").val();
            var email = $("#emailUtilisateurModif").val();
            var idFonction = $("#fonctionUtilisateurModif").val().split("-")[1];
            $.post("API/modifierUtilisateur.php", {utilisateur_id: idUser, nom: nom, prenom: prenom, email: email, fonction_id: idFonction}, function(data){
                var reponse = JSON.parse(data);
                if(reponse)
                    {
                        if($("#nouvellePhotoUtilisateurModif").val() != "")
                            {
                                var fichier = document.getElementById("nouvellePhotoUtilisateurModif").files[0];
                                var xhr = new XMLHttpRequest();
                                
                                xhr.open("POST", "API/modifierPhotoUtilisateur.php");
                                
                                xhr.addEventListener("load", function(){
                                    document.location.href = "listeUtilisateurs.php";
                                });
                                
                                var form = new FormData();
                                form.append('photo', fichier);
                                form.append('utilisateur_id', idUser);
                                
                                xhr.send(form);
                            }
                        else{
                            document.location.href = "listeUtilisateurs.php";
                        }
                    }
                else{
                    alert("Une erreur s'est produite, veuillez réessayer plus tard");
                }
            });
        }
    });
    
    
    
    
    //NOUVEL UTILISATEUR
    var idFonction = $("#fonctionUtilisateurNew").val().split("-")[1];
    $.post("API/getFonctionById.php", {fonction_id: idFonction}, function(data){
            var fonction = JSON.parse(data);
            $("#niveauUtilisateurNew").text(fonction.niveau.libelle);
        });
    
    
    $("#fonctionUtilisateurNew").change(function(){
        var id = $(this).val().split("-")[1];
        $.post("API/getFonctionById.php", {fonction_id: id}, function(data){
            var fonction = JSON.parse(data);
            $("#niveauUtilisateurNew").text(fonction.niveau.libelle);
        });
    });
    
    $("#ajouterFonctionUtilisateurNew").click(function(e){
        e.preventDefault();
        $(this).hide("fade");
        $("#divAjouterFonctionNew").show("fade");
    });
    
    $("#annulerNouvelleFonctionNew").click(function(e){
        e.preventDefault();
        $("#divAjouterFonctionNew").hide("fade");
        $("#ajouterFonctionUtilisateurNew").show("fade");
        $("#libelleFonctionNew").val("");
        $("#niveauNouvelleFonctionNew option:first").prop("selected", true);
    });
    
    $("#validerNouvelleFonctionNew").click(function(e){
        e.preventDefault();
        $("#erreurLibelleNouvelleFonctionNew").hide();
        if($("#libelleFonctionNew").val() == "")
            {
                $("#erreurLibelleNouvelleFonctionNew").show();
            }
        else{
            var libelle = $("#libelleFonctionNew").val();
            var idNiveau = $("#niveauNouvelleFonctionNew").val().split("-")[1];
            $.post("API/addFonction.php", {libelle: libelle, niveau_id: idNiveau}, function(data){
                var idFonction = JSON.parse(data);
                var optionElt = document.createElement("option");
                optionElt.value = "fonction-" + idFonction;
                optionElt.textContent = $("#libelleFonctionNew").val();
                document.getElementById("fonctionUtilisateurNew").appendChild(optionElt);
                $("#fonctionUtilisateurNew").val("fonction-" + idFonction);
                $("#niveauUtilisateurNew").text();
                $.post("API/getNiveauById.php", {niveau_id: idNiveau}, function(data){
                    var niveau = JSON.parse(data);
                    $("#niveauUtilisateurNew").text(niveau.libelle);
                    $("#annulerNouvelleFonctionNew").click();
                });
            });
        }
    });
    
    $("#nouvellePhotoUtilisateurNew").change(function(){
        var reader = new FileReader();
        
        var fichier = document.getElementById("nouvellePhotoUtilisateurNew").files[0];
        if(fichier.type.split("/")[0] == "image")
            {
                reader.addEventListener('load', function(){
                    document.getElementById("photoNouvelUtilisateur").src = this.result;
                });
                reader.readAsDataURL(fichier);
            }
        else{
            alert("Veuillez choisir une image");
            $("#nouvellePhotoUtilisateurNew").val("");
        }
    });
    
    $("#annulerNewUtilisateur").click(function(){
        $("#nouvellePhotoUtilisateurNew").val("");
        $(".erreurNewUser").hide();
    });
    
    $("#validerNewUtilisateur").click(function(e){
        e.preventDefault();
        $(".erreurNewUser").hide();
        if($("#nomUtilisateurNew").val() == "")
            {
                $("#erreurNomUserNew").show("fade");
            }
        else if($("#prenomUtilisateurNew").val() == ""){
            $("#erreurPrenomUserNew").show("fade");
        }
        else if($("#emailUtilisateurNew").val() == ""){
            $("#erreurEmailUserNew").show("fade");
        }
        else{
            var nom = $("#nomUtilisateurNew").val();
            var prenom = $("#prenomUtilisateurNew").val();
            var email = $("#emailUtilisateurNew").val();
            var idFonction = $("#fonctionUtilisateurNew").val().split("-")[1];
            
            $.post("API/addUtilisateur.php", {nom: nom, prenom: prenom, email: email, fonction_id: idFonction}, function(data){
                var idUser = JSON.parse(data);
                if(idUser != null)
                    {
                        if($("#nouvellePhotoUtilisateurNew").val() != "")
                            {
                                var fichier = document.getElementById("nouvellePhotoUtilisateurNew").files[0];
                                var xhr = new XMLHttpRequest();
                                
                                xhr.open("POST", "API/modifierPhotoUtilisateur.php");
                                
                                xhr.addEventListener("load", function(){
                                    document.location.href = "listeUtilisateurs.php";
                                });
                                
                                var form = new FormData();
                                form.append('photo', fichier);
                                form.append('utilisateur_id', idUser);
                                
                                xhr.send(form);
                            }
                        else{
                            document.location.href = "listeUtilisateurs.php";
                        }
                    }
                else{
                    alert("Une erreur s'est produite, veuillez réessayer plus tard");
                }
            });
            /*$.post("API/modifierUtilisateur.php", {utilisateur_id: idUser, nom: nom, prenom: prenom, email: email, fonction_id: idFonction}, function(data){
                var reponse = JSON.parse(data);
                if(reponse)
                    {
                        if($("#nouvellePhotoUtilisateurModif").val() != "")
                            {
                                var fichier = document.getElementById("nouvellePhotoUtilisateurModif").files[0];
                                var xhr = new XMLHttpRequest();
                                
                                xhr.open("POST", "API/modifierPhotoUtilisateur.php");
                                
                                xhr.addEventListener("load", function(){
                                    document.location.href = "listeUtilisateurs.php";
                                });
                                
                                var form = new FormData();
                                form.append('photo', fichier);
                                form.append('utilisateur_id', idUser);
                                
                                xhr.send(form);
                            }
                        else{
                            document.location.href = "listeUtilisateurs.php";
                        }
                    }
                else{
                    alert("Une erreur s'est produite, veuillez réessayer plus tard");
                }
            });*/
        }
    });
    
    
});