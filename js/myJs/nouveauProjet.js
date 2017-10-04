$(function(){
    
    $("#summernote").summernote({
        height: 300,
        lang: 'fr-FR'
    });
    var summerNoteVide = $("#summernote").summernote('code');
    
    $("#envoiMail").bootstrapToggle();
    
    function actualiserHautFormulaire(){
        var idSecteur = $("#secteurProjet").val();
        $.post("API/getDomainesBySecteurId.php", {secteur_id: idSecteur}, function(data){
            var domaines = JSON.parse(data);
            domaines.forEach(function(dom){
                var optionElt = document.createElement("option");
                optionElt.textContent = dom.libelle;
                optionElt.value = dom.id;
                document.getElementById("domainesProjet").appendChild(optionElt);
                
                $.post("API/getSousDomainesByDomaineId.php", {domaine_id: dom.id}, function(data){
                    var sousDomaines = JSON.parse(data);
                    
                    if(sousDomaines != null)
                        {
                            var optgroupElt = document.createElement("optgroup");
                            optgroupElt.label = dom.libelle;

                            sousDomaines.forEach(function(sd){
                                var optionElt = document.createElement("option");
                                optionElt.value = sd.id;
                                optionElt.textContent = sd.libelle;
                                optgroupElt.appendChild(optionElt);
                            });

                            document.getElementById("sousDomaineProjet").appendChild(optgroupElt);
                            $("#sousDomaineProjet").trigger("chosen:updated");
                        }
                });
            });
            $("#domainesProjet").trigger("chosen:updated");
        });
    }
    
    $("[name='typeProjet']").change(function(){
        if($(this).val() == "projetGenerique")
            {
                $("#divProjetGenerique").show("fade");
                $("#divProjetSpecifique").hide();
                $("#domainesProjet").trigger("chosen:updated");
                $("#domainesProjet").chosen();
            }
        else if($(this).val() == "projetSpecifique")
            {
                $("#divProjetSpecifique").show("fade");
                $("#divProjetGenerique").hide();
                $("#domainesProjet").trigger("chosen:updated");
                $("#sousDomaineProjet").chosen();
            }
    });
    
    $("#secteurProjet").change(function(){
        $("#domainesProjet").prop("disabled", true);
        $("#sousDomaineProjet").prop("disabled", true);
        $("#sousDomaineProjet").html("");
        $("#domainesProjet").html("");
        actualiserHautFormulaire();
        $("#domainesProjet").prop("disabled", false);
        $("#sousDomaineProjet").prop("disabled", false);
    });
    
    actualiserHautFormulaire();
    
    
    $("#btnReinitialiser").click(function(){
        window.location.reload();
    });
    
    
    Dropzone.options.form2 = {
        parallelUploads: 10,
        maxFiles: 10,
        autoProcessQueue: false,
        addRemoveLinks: true,
        dictDefaultMessage: 'Déplacer les fichiers ou cliquer ici pour upload',
        dictRemoveFile: "Supprimer",
        init: function() {
        myDropzone = this;
            
        $("#validerNouveauProjet").click(function(){
            $("#validerNouveauProjet").prop("disabled", true);
            $("#waitValider").show();
            if($("#titreNouveauProjet").val() == "" || $("#summernote").summernote('code') == summerNoteVide || $("#summernote").summernote('code') == "<br>")
                {
                    alert("Veuillez saisir un titre et un contenu");
                    $("#validerNouveauProjet").prop("disabled", false);
                    $("#waitValider").hide();
                }
            else{
                if($(".divRadio:visible").length == 0)
                    {
                        alert("Veuillez sélectionner un type de projet (générique ou spécifique)");
                        $("#validerNouveauProjet").prop("disabled", false);
                        $("#waitValider").hide();
                    }
                else{
                    var continu = true;
                    var typeProjet = $("[name='typeProjet']:checked").val();
                    if(typeProjet == "projetGenerique")
                        {
                            var idDomaines = $("#domainesProjet").val();
                            if(idDomaines == null || !(idDomaines.length > 0))
                                {
                                    continu = false;
                                    alert("Veuillez sélectionner au moins un domaine");
                                    $("#validerNouveauProjet").prop("disabled", false);
                                    $("#waitValider").hide();
                                }
                        }
                    else if(typeProjet == "projetSpecifique")
                        {
                            var idSousDomaine = $("#sousDomaineProjet").val();
                            if(idSousDomaine == null || !(idSousDomaine > 0))
                                {
                                    continu = false;
                                    alert("Veuillez sélectionner un sous-domaine");
                                    $("#validerNouveauProjet").prop("disabled", false);
                                    $("#waitValider").hide();
                                }
                        }
                    if(continu)
                        {
                            var titre = $("#titreNouveauProjet").val();
                            var description = $("#descriptionNouveauProjet").val();
                            if(description == "")
                                {
                                    description = null;
                                }
                            var contenu = $("#summernote").summernote('code');
                            var idUser = $("#user_id").val();

                            $.post("API/addProjet.php", {titre: titre, contenu: contenu, description: description, utilisateur_id: idUser}, function(data){
                                var idProjet = JSON.parse(data);
                                if(idProjet != null)
                                    {
                                        if(typeProjet == "projetGenerique")
                                            {
                                                idDomaines.forEach(function(idDom){
                                                    $.post("API/addDomaineProjet.php", {projet_id: idProjet, domaine_id: idDom}, function(data){
                                                        if($("#imageEnteteNouveauProjet").val() != "")
                                           {                                   
                                               var image = document.getElementById("imageEnteteNouveauProjet").files[0];

                                               var xhr = new XMLHttpRequest();
                                               xhr.open('POST', 'API/modifierImageEnteteProjet.php');

                                               var form = new FormData();
                                               form.append("image", image);
                                               form.append("projet_id", idProjet);

                                               xhr.send(form);
                                           }
                                            if(myDropzone.getUploadingFiles().length == 0 && myDropzone.getQueuedFiles().length == 0)
                                                {
                                                   if($("#envoiMail").is(":checked"))
                                                       {
                                                           $.post("API/getUtilisateursAbonnesByProjetId.php", {projet_id: idProjet}, function(data){
                                                                var users = JSON.parse(data);
                                                                if(users != null)
                                                                    {
                                                                        var utilisateurs = JSON.stringify(users);
                                                                        
                                                                        /*var secteur = $("#secteurProjet option:selected").text();
                                                                        var typeProjet = $("[name='typeProjet']:checked").val();
                                                                        var sousDomaine = null;
                                                                        var domaines = [];
                                                                        var tpProj = "";
                                                                        if(typeProjet == "projetSpecifique")
                                                                            {
                                                                                tpProj = "spécifique";
                                                                                sousDomaine = $("#sousDomaineProjet option:selected").text();
                                                                            }
                                                                        else if(typeProjet == "projetGenerique")
                                                                            {
                                                                                tpProj = "générique";
                                                                                $("#domainesProjet option:selected").each(function(){
                                                                                    domaines.push($(this).text());
                                                                                });
                                                                            }
                                                                        var domSd = "";
                                                                        if(sousDomaine != null)
                                                                            {
                                                                                domSd = sousDomaine;
                                                                            }
                                                                        else{
                                                                            domSd = domaines.join(", ");
                                                                        }
                                                                        var titre = "Un projet vient d'être créé";
                                                                        var lien = "projet.php?id=" + idProjet;
                                                                        var description = "Un projet du secteur " + secteur + " a été créé.<br/>Il s'agit d'un projet " + tpProj + " (" + domSd + ")";
                                                                        var contenu ="Bonjour<br/><br/>" + description + "<br/>Pour y accéder, cliquez <a href='" + lien + "'>ICI</a>";*/
                                                                        /*var emails = [];
                                                                        users.forEach(function(user){
                                                                            emails.push(user.email);
                                                                        });
                                                                        emails = emails.join();*/
                                                                        

                                                                        $.post("API/mailNotifNouveauProjet.php", {utilisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                            var reponse = JSON.parse(data);
                                                                            if(reponse)
                                                                                {
                                                                                    /*$.post("API/addNotification.php", {titre: titre, description: description, lien: lien}, function(data){
                                                                                        var idNotif = JSON.parse(data);
                                                                                        var i = 0;
                                                                                        users.forEach(function(user){
                                                                                            $.post("API/addUtilisateurNotification.php", {utilisateur_id: user.id, notification_id: idNotif}, function(data){
                                                                                                i++;
                                                                                                if(i == users.length)
                                                                                                    {*/
                                                                                                        document.location.href = "projet.php?id=" + idProjet;
                                                                                                   /* }
                                                                                            });
                                                                                        });
                                                                                    });*/
                                                                                }
                                                                            else{
                                                                                alert("Erreur: les mails n'ont pas pu être envoyés aux utilisateurs abonnés");
                                                                                $("#validerNouveauProjet").prop("disabled", false);
                                                                                $("#waitValider").hide();
                                                                            }
                                                                        });
                                                                    }
                                                                else{
                                                                    document.location.href = "projet.php?id=" + idProjet;
                                                                }
                                                            });
                                                       }
                                                    else{
                                                        document.location.href = "projet.php?id=" + idProjet;
                                                    }
                                                }
                                            else{
                                                myDropzone.on("complete", function (file) {
                                                  if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                                                   if($("#envoiMail").is(":checked"))
                                                       {
                                                           $.post("API/getUtilisateursAbonnesByProjetId.php", {projet_id: idProjet}, function(data){
                                                                var users = JSON.parse(data);
                                                                console.log(users);
                                                                if(users != null)
                                                                    {
                                                                        var utilisateurs = JSON.stringify(users);
                                                                        /*var secteur = $("#secteurProjet option:selected").text();
                                                                        var typeProjet = $("[name='typeProjet']:checked").val();
                                                                        var sousDomaine = null;
                                                                        var domaines = [];
                                                                        var tpProj = "";
                                                                        if(typeProjet == "projetSpecifique")
                                                                            {
                                                                                tpProj = "spécifique";
                                                                                sousDomaine = $("#sousDomaineProjet option:selected").text();
                                                                            }
                                                                        else if(typeProjet == "projetGenerique")
                                                                            {
                                                                                tpProj = "générique";
                                                                                $("#domainesProjet option:selected").each(function(){
                                                                                    domaines.push($(this).text());
                                                                                });
                                                                            }
                                                                        var domSd = "";
                                                                        if(sousDomaine != null)
                                                                            {
                                                                                domSd = sousDomaine;
                                                                            }
                                                                        else{
                                                                            domSd = domaines.join(", ");
                                                                        }
                                                                        var titre = "Un projet vient d'être créé";
                                                                        var lien = "projet.php?id=" + idProjet;
                                                                        var description = "Un projet du secteur " + secteur + " a été créé.<br/>Il s'agit d'un projet " + tpProj + " (" + domSd + ")";
                                                                        var contenu ="Bonjour<br/><br/>" + description + "<br/>Pour y accéder, cliquez <a href='" + lien + "'>ICI</a>";
                                                                        var emails = [];
                                                                        users.forEach(function(user){
                                                                            emails.push(user.email);
                                                                        });
                                                                        emails = emails.join();*/
                                                                        $.post("API/mailNotifNouveauProjet.php", {utilisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                            var reponse = JSON.parse(data);
                                                                            if(reponse)
                                                                                {
                                                                                    /*$.post("API/addNotification.php", {titre: titre, description: description, lien: lien}, function(data){
                                                                                        var idNotif = JSON.parse(data);
                                                                                        var i = 0;
                                                                                        users.forEach(function(user){
                                                                                            $.post("API/addUtilisateurNotification.php", {utilisateur_id: user.id, notification_id: idNotif}, function(data){
                                                                                                i++;
                                                                                                if(i == users.length)
                                                                                                    {*/
                                                                                                        document.location.href = "projet.php?id=" + idProjet;
                                                                                                    /*}
                                                                                            });
                                                                                        });
                                                                                    });*/
                                                                                }
                                                                            else{
                                                                                alert("Erreur: les mails n'ont pas pu être envoyés aux utilisateurs abonnés");
                                                                                $("#validerNouveauProjet").prop("disabled", false);
                                                                                $("#waitValider").hide();
                                                                            }
                                                                        });
                                                                    }
                                                                else{
                                                                    document.location.href = "projet.php?id=" + idProjet;
                                                                }
                                                            });
                                                       }
                                                       else{
                                                           document.location.href = "projet.php?id=" + idProjet;
                                                       }
                                                  }
                                                });


                                                myDropzone.on('sending', function(file, xhr, formData){
                                                    formData.append('projet_id', idProjet);
                                                    formData.append('libelle', file.name);
                                                });

                                                myDropzone.processQueue();
                                            }
                                                    });
                                                });
                                            }
                                        else if(typeProjet == "projetSpecifique")
                                            {
                                                $.post("API/modifierSousDomaineProjet.php", {projet_id: idProjet, sous_domaine_id: idSousDomaine}, function(data){
                                                    if($("#imageEnteteNouveauProjet").val() != "")
                                           {                                   
                                               var image = document.getElementById("imageEnteteNouveauProjet").files[0];

                                               var xhr = new XMLHttpRequest();
                                               xhr.open('POST', 'API/modifierImageEnteteProjet.php');

                                               var form = new FormData();
                                               form.append("image", image);
                                               form.append("projet_id", idProjet);

                                               xhr.send(form);
                                           }
                                            if(myDropzone.getUploadingFiles().length == 0 && myDropzone.getQueuedFiles().length == 0)
                                                {
                                                   if($("#envoiMail").is(":checked"))
                                                       {
                                                           $.post("API/getUtilisateursAbonnesByProjetId.php", {projet_id: idProjet}, function(data){
                                                                var users = JSON.parse(data);
                                                                if(users != null)
                                                                    {
                                                                        var utilisateurs = JSON.stringify(users);
                                                                        
                                                                        /*var secteur = $("#secteurProjet option:selected").text();
                                                                        var typeProjet = $("[name='typeProjet']:checked").val();
                                                                        var sousDomaine = null;
                                                                        var domaines = [];
                                                                        var tpProj = "";
                                                                        if(typeProjet == "projetSpecifique")
                                                                            {
                                                                                tpProj = "spécifique";
                                                                                sousDomaine = $("#sousDomaineProjet option:selected").text();
                                                                            }
                                                                        else if(typeProjet == "projetGenerique")
                                                                            {
                                                                                tpProj = "générique";
                                                                                $("#domainesProjet option:selected").each(function(){
                                                                                    domaines.push($(this).text());
                                                                                });
                                                                            }
                                                                        var domSd = "";
                                                                        if(sousDomaine != null)
                                                                            {
                                                                                domSd = sousDomaine;
                                                                            }
                                                                        else{
                                                                            domSd = domaines.join(", ");
                                                                        }
                                                                        var titre = "Un projet vient d'être créé";
                                                                        var lien = "projet.php?id=" + idProjet;
                                                                        var description = "Un projet du secteur " + secteur + " a été créé.<br/>Il s'agit d'un projet " + tpProj + " (" + domSd + ")";
                                                                        var contenu ="Bonjour<br/><br/>" + description + "<br/>Pour y accéder, cliquez <a href='" + lien + "'>ICI</a>";*/
                                                                        /*var emails = [];
                                                                        users.forEach(function(user){
                                                                            emails.push(user.email);
                                                                        });
                                                                        emails = emails.join();*/
                                                                        

                                                                        $.post("API/mailNotifNouveauProjet.php", {utilisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                            var reponse = JSON.parse(data);
                                                                            if(reponse)
                                                                                {
                                                                                    /*$.post("API/addNotification.php", {titre: titre, description: description, lien: lien}, function(data){
                                                                                        var idNotif = JSON.parse(data);
                                                                                        var i = 0;
                                                                                        users.forEach(function(user){
                                                                                            $.post("API/addUtilisateurNotification.php", {utilisateur_id: user.id, notification_id: idNotif}, function(data){
                                                                                                i++;
                                                                                                if(i == users.length)
                                                                                                    {*/
                                                                                                        document.location.href = "projet.php?id=" + idProjet;
                                                                                                   /* }
                                                                                            });
                                                                                        });
                                                                                    });*/
                                                                                }
                                                                            else{
                                                                                alert("Erreur: les mails n'ont pas pu être envoyés aux utilisateurs abonnés");
                                                                                $("#validerNouveauProjet").prop("disabled", false);
                                                                                $("#waitValider").hide();
                                                                            }
                                                                        });
                                                                    }
                                                                else{
                                                                    document.location.href = "projet.php?id=" + idProjet;
                                                                }
                                                            });
                                                       }
                                                    else{
                                                        document.location.href = "projet.php?id=" + idProjet;
                                                    }
                                                }
                                            else{
                                                myDropzone.on("complete", function (file) {
                                                  if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                                                   if($("#envoiMail").is(":checked"))
                                                       {
                                                           $.post("API/getUtilisateursAbonnesByProjetId.php", {projet_id: idProjet}, function(data){
                                                                var users = JSON.parse(data);
                                                                console.log(users);
                                                                if(users != null)
                                                                    {
                                                                        var utilisateurs = JSON.stringify(users);
                                                                        /*var secteur = $("#secteurProjet option:selected").text();
                                                                        var typeProjet = $("[name='typeProjet']:checked").val();
                                                                        var sousDomaine = null;
                                                                        var domaines = [];
                                                                        var tpProj = "";
                                                                        if(typeProjet == "projetSpecifique")
                                                                            {
                                                                                tpProj = "spécifique";
                                                                                sousDomaine = $("#sousDomaineProjet option:selected").text();
                                                                            }
                                                                        else if(typeProjet == "projetGenerique")
                                                                            {
                                                                                tpProj = "générique";
                                                                                $("#domainesProjet option:selected").each(function(){
                                                                                    domaines.push($(this).text());
                                                                                });
                                                                            }
                                                                        var domSd = "";
                                                                        if(sousDomaine != null)
                                                                            {
                                                                                domSd = sousDomaine;
                                                                            }
                                                                        else{
                                                                            domSd = domaines.join(", ");
                                                                        }
                                                                        var titre = "Un projet vient d'être créé";
                                                                        var lien = "projet.php?id=" + idProjet;
                                                                        var description = "Un projet du secteur " + secteur + " a été créé.<br/>Il s'agit d'un projet " + tpProj + " (" + domSd + ")";
                                                                        var contenu ="Bonjour<br/><br/>" + description + "<br/>Pour y accéder, cliquez <a href='" + lien + "'>ICI</a>";
                                                                        var emails = [];
                                                                        users.forEach(function(user){
                                                                            emails.push(user.email);
                                                                        });
                                                                        emails = emails.join();*/
                                                                        $.post("API/mailNotifNouveauProjet.php", {utilisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                            var reponse = JSON.parse(data);
                                                                            if(reponse)
                                                                                {
                                                                                    /*$.post("API/addNotification.php", {titre: titre, description: description, lien: lien}, function(data){
                                                                                        var idNotif = JSON.parse(data);
                                                                                        var i = 0;
                                                                                        users.forEach(function(user){
                                                                                            $.post("API/addUtilisateurNotification.php", {utilisateur_id: user.id, notification_id: idNotif}, function(data){
                                                                                                i++;
                                                                                                if(i == users.length)
                                                                                                    {*/
                                                                                                        document.location.href = "projet.php?id=" + idProjet;
                                                                                                    /*}
                                                                                            });
                                                                                        });
                                                                                    });*/
                                                                                }
                                                                            else{
                                                                                alert("Erreur: les mails n'ont pas pu être envoyés aux utilisateurs abonnés");
                                                                                $("#validerNouveauProjet").prop("disabled", false);
                                                                                $("#waitValider").hide();
                                                                            }
                                                                        });
                                                                    }
                                                                else{
                                                                    document.location.href = "projet.php?id=" + idProjet;
                                                                }
                                                            });
                                                       }
                                                       else{
                                                           document.location.href = "projet.php?id=" + idProjet;
                                                       }
                                                  }
                                                });


                                                myDropzone.on('sending', function(file, xhr, formData){
                                                    formData.append('projet_id', idProjet);
                                                    formData.append('libelle', file.name);
                                                });

                                                myDropzone.processQueue();
                                            }
                                                });
                                            }

                                       

                                    }
                                else{
                                    alert("Une erreur s'est produite, veuillez réessayer plus tard");
                                    $("#validerNouveauProjet").prop("disabled", false);
                                    $("#waitValider").hide();
                                }
                            });
                        }
                }
            }
        });
        }
    };
    
});