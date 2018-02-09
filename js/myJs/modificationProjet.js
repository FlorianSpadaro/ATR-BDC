$(function(){
    var listeSecteurs = JSON.parse($("#listeSecteurs").text());

    $("#summernote").summernote({
        height: 300,
        lang: 'fr-FR'
    });
    
    $("#envoiMail").bootstrapToggle();
    
    var summerNoteVide = $("#summernote").summernote('code');
    
    function actualiserHautFormulaire(idSd, tabIdsDoms){
        listeSecteurs.forEach(function(secteur){
            if(secteur.id == $("#secteurProjet").val())
            {
                var htmlDomaine = "";
                secteur.domaines.forEach(function(domaine){
                    if(tabIdsDoms != null && tabIdsDoms.indexOf(domaine.id) != -1)
                    {
                        htmlDomaine += "<option id='" + domaine.id + "' value='" + domaine.id + "' selected >" + domaine.libelle + "</option>";
                    }
                    else{
                        htmlDomaine += "<option id='" + domaine.id + "' value='" + domaine.id + "'>" + domaine.libelle + "</option>";
                    }
                });
                $("#domainesProjet").html(htmlDomaine);
                $("#domainesProjet").trigger("chosen:updated");

                var htmlSousDomaine = "";
                secteur.domaines.forEach(function(domaine){
                    htmlSousDomaine += "<optgroup label='" + domaine.libelle + "' >"
                    console.log(domaine);
                    domaine.sousDomaines.forEach(function(sd){
                        if(idSd != null && idSd == sd.id)
                        {
                            htmlSousDomaine += "<option id='" + sd.id + "' value='" + sd.id + "' selected >" + sd.libelle + "</option>";
                        }
                        else{
                            htmlSousDomaine += "<option id='" + sd.id + "' value='" + sd.id + "'>" + sd.libelle + "</option>";
                        }
                    });
                    htmlSousDomaine += "</optgroup>";
                });
                $("#sousDomaineProjet").html(htmlSousDomaine);
                $("#sousDomaineProjet").trigger("chosen:updated");
            }
        });
    }
    
    var tabSuppression = [];
    $(".btnSuprPj").click(function(){
        var idPj = $(this).attr("id").split("-")[1];
        tabSuppression.push(idPj);
        $("#pjConserve-" + idPj).remove();
        $(this).closest(".element").hide("fade");
        if($(".pjConserve").length == 0)
            {
                $("#pjActuellesSuppr").show();
            }
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
                            var idProjet = $("#idProjet").val();

                            $.post("API/modifierProjetById.php", {titre: titre, contenu: contenu, description: description, projet_id: idProjet}, function(data){
                                var reponse = JSON.parse(data);
                                if(reponse)
                                    {
                                        if(typeProjet == "projetGenerique")
                                            {
                                                $.post("API/removeDomainesProjet.php", {projet_id: idProjet}, function(data){
                                                });
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



                                                        if(tabSuppression.length > 0)
                                                        {
                                                            var i = 0;
                                                            tabSuppression.forEach(function(tbSuppr){
                                                                i++;
                                                                $.post("API/removePieceJointeProjetById.php", {piece_jointe_id: tbSuppr, projet_id: $("#idProjet").val()}, function(data){
                                                                    if(i == tabSuppression.length)
                                                                        {
                                                                            if(myDropzone.getUploadingFiles().length == 0 && myDropzone.getQueuedFiles().length == 0)
                                                                                {
                                                                                   if($("#envoiMail").is(":checked"))
                                                                                   {
                                                                                       $.post("API/getUtilisateursAbonnesByProjetId.php", {projet_id: idProjet}, function(data){
                                                                                            var users = JSON.parse(data);
                                                                                            if(users != null)
                                                                                                {
                                                                                                    var utilisateurs = JSON.stringify(users);

                                                                                                    $.post("API/mailNotifModificationProjet.php", {utilisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                                                        var reponse = JSON.parse(data);
                                                                                                        if(reponse)
                                                                                                            {
                                                                                                                document.location.href = "projet.php?id=" + idProjet;
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
                                                                                            if(users != null)
                                                                                                {
                                                                                                    var utilisateurs = JSON.stringify(users);

                                                                                                    $.post("API/mailNotifModificationProjet.php", {utlisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                                                        var reponse = JSON.parse(data);
                                                                                                        if(reponse)
                                                                                                            {
                                                                                                                document.location.href = "projet.php?id=" + idProjet;
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
                                                                                });

                                                                                myDropzone.processQueue();
                                                                            }
                                                                        }
                                                                });
                                                            });
                                                        }
                                                    else{
                                                        if(myDropzone.getUploadingFiles().length == 0 && myDropzone.getQueuedFiles().length == 0)
                                                            {
                                                                if($("#envoiMail").is(":checked"))
                                                               {
                                                                   $.post("API/getUtilisateursAbonnesByProjetId.php", {projet_id: idProjet}, function(data){
                                                                        var users = JSON.parse(data);
                                                                        if(users != null)
                                                                            {
                                                                                var utilisateurs = JSON.stringify(users);

                                                                                                    $.post("API/mailNotifModificationProjet.php", {utilisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                                                        console.log(data);
                                                                                                        var reponse = JSON.parse(data);
                                                                                                        if(reponse)
                                                                                                            {
                                                                                                                document.location.href = "projet.php?id=" + idProjet;
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
                                                                        if(users != null)
                                                                            {
                                                                                var utilisateurs = JSON.stringify(users);

                                                                                                    $.post("API/mailNotifModificationProjet.php", {utlisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                                                        var reponse = JSON.parse(data);
                                                                                                        if(reponse)
                                                                                                            {
                                                                                                                document.location.href = "projet.php?id=" + idProjet;
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
                                                            });

                                                            myDropzone.processQueue();
                                                        }
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



                                                    if(tabSuppression.length > 0)
                                                    {
                                                        var i = 0;
                                                        tabSuppression.forEach(function(tbSuppr){
                                                            i++;
                                                            $.post("API/removePiecesJointesProjet.php", {piece_jointe_id: tbSuppr, projet_id: $("#idProjet").val()}, function(data){
                                                                if(i == tabSuppression.length)
                                                                    {
                                                                        if(myDropzone.getUploadingFiles().length == 0 && myDropzone.getQueuedFiles().length == 0)
                                                                            {
                                                                               if($("#envoiMail").is(":checked"))
                                                                               {
                                                                                   $.post("API/getUtilisateursAbonnesByProjetId.php", {projet_id: idProjet}, function(data){
                                                                                        var users = JSON.parse(data);
                                                                                        if(users != null)
                                                                                            {
                                                                                                var utilisateurs = JSON.stringify(users);

                                                                                                    $.post("API/mailNotifModificationProjet.php", {utlisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                                                        var reponse = JSON.parse(data);
                                                                                                        if(reponse)
                                                                                                            {
                                                                                                                document.location.href = "projet.php?id=" + idProjet;
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
                                                                                        if(users != null)
                                                                                            {
                                                                                                var utilisateurs = JSON.stringify(users);

                                                                                                    $.post("API/mailNotifModificationProjet.php", {utlisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                                                        var reponse = JSON.parse(data);
                                                                                                        if(reponse)
                                                                                                            {
                                                                                                                document.location.href = "projet.php?id=" + idProjet;
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
                                                                            });

                                                                            myDropzone.processQueue();
                                                                        }
                                                                    }
                                                            });
                                                        });
                                                    }
                                                else{
                                                    if(myDropzone.getUploadingFiles().length == 0 && myDropzone.getQueuedFiles().length == 0)
                                                        {
                                                            if($("#envoiMail").is(":checked"))
                                                           {
                                                               $.post("API/getUtilisateursAbonnesByProjetId.php", {projet_id: idProjet}, function(data){
                                                                    var users = JSON.parse(data);
                                                                    if(users != null)
                                                                        {
                                                                            var utilisateurs = JSON.stringify(users);

                                                                                                    $.post("API/mailNotifModificationProjet.php", {utlisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                                                        var reponse = JSON.parse(data);
                                                                                                        if(reponse)
                                                                                                            {
                                                                                                                document.location.href = "projet.php?id=" + idProjet;
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
                                                                    if(users != null)
                                                                        {
                                                                            var utilisateurs = JSON.stringify(users);

                                                                                                    $.post("API/mailNotifModificationProjet.php", {utlisateurs: utilisateurs, projet_id: idProjet}, function(data){
                                                                                                        var reponse = JSON.parse(data);
                                                                                                        if(reponse)
                                                                                                            {
                                                                                                                document.location.href = "projet.php?id=" + idProjet;
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
                                                        });

                                                        myDropzone.processQueue();
                                                    }
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
    
    $("#btnReinitialiser").click(function(){
        window.location.reload();
    });
    
    $("#secteurProjet").change(function(){
        actualiserHautFormulaire(null, null);
    });

    $("#domainesProjet").change(function(){
        console.log("TEST");
    });
    
    
    $("[name='typeProjet']").change(function(){
        if($(this).val() == "projetGenerique")
            {
                $("#divProjetGenerique").show("fade");
                $("#divProjetSpecifique").hide();
                $("#domainesProjet").chosen();
                $("#domainesProjet").trigger("chosen:updated");
            }
        else if($(this).val() == "projetSpecifique")
            {
                $("#divProjetSpecifique").show("fade");
                $("#divProjetGenerique").hide();
                $("#sousDomaineProjet").chosen();
                $("#sousDomaineProjet").trigger("chosen:updated");
            }
    });
    
    var idProjet = $("#idProjet").val();
    $.post("API/getProjetById.php", {projet_id: idProjet}, function(data){
        var projet = JSON.parse(data);
        if(projet != null)
            {
                $.post("API/getSecteurIdByProjetId.php", {projet_id: projet.id}, function(data2){
                    var idSecteurProjet = JSON.parse(data2);
                    if(idSecteurProjet != null)
                        {
                            $("#secteurProjet").val(idSecteurProjet);
                            if(projet.sous_domaine != null)
                                {
                                    actualiserHautFormulaire(projet.sous_domaine.id, null);
                                    $("#projetSpecifique").click();
                                }
                            else{
                                var listeIdsDomaines = [];
                                projet.domaines.forEach(function(dom){
                                    listeIdsDomaines.push(dom.id);
                                });
                                actualiserHautFormulaire(null, listeIdsDomaines);
                                $("#projetGenerique").click();
                            }
                            
                        }
                });
                
                $("#titreNouveauProjet").val(projet.titre);
                $("#descriptionNouveauProjet").val(projet.description);
                $("#summernote").summernote("code", projet.contenu);
                
            }
    });
});