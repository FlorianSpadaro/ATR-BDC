$(function(){
    $('#summernote').summernote({
        height: 300,
        lang: 'fr-FR'
    });
    
    $("#envoiMail").bootstrapToggle();
    
    var tabSuppression = [];
    
    $(".btnSuprPj").click(function(){
        var idPj = $(this).attr("id").split("-")[1];
        tabSuppression.push(idPj);
        $("#pjConserve-" + idPj).remove();
        $(this).closest(".element").hide("fade");
        if($(".pjConserve").length == 0)
            {
                $("#pjActuellesSuppr").show("fade");
            }
    });
    
    var anciennesPj = [];
    $(".pjConserve").each(function(){
        var idPj = $(this).attr("id").split("-")[1];
        anciennesPj.push(idPj);
    });
    
    
    var idActu = $("#idActu").val();
    $.post("API/getActualiteById.php", {actualite_id: idActu}, function(data){
        var actu = JSON.parse(data);
        $("#summernote").summernote('code', actu.contenu);
        
    });
    
    var summerNoteVide = $("#summernote").summernote('code');
    
    $("#summernote").summernote('code');
    
    Dropzone.options.form2 = {
        parallelUploads: 10,
        maxFiles: 10,
        autoProcessQueue: false,
        addRemoveLinks: true,
        dictDefaultMessage: 'Déplacer les fichiers ou cliquer ici pour upload',
        dictRemoveFile: "Supprimer",
        init: function() {
        myDropzone = this;
            
        $("#validerNouvelleActu").click(function(){
            if($("#titreNouvelleActu").val() == "" || $("#summernote").summernote('code') == summerNoteVide || $("#summernote").summernote('code') == "<br>")
                {
                    alert("Veuillez saisir un titre et un contenu");
                }
            else{
                $("#validerNouvelleActu").prop("disabled", true);
                $("#waitValider").show();
                
                var idActu = $("#idActu").val();
                var titre = $("#titreNouvelleActu").val();
                var description = $("#descriptionNouvelleActu").val();
                if(description == "")
                    {
                        description = null;
                    }
                var contenu = $("#summernote").summernote('code');

                $.post("API/modifierActualiteById.php", {titre: titre, contenu: contenu, description: description, actualite_id: idActu}, function(data){
                    var reponse = JSON.parse(data);
                    if(reponse)
                        {
                           if($("#imageEnteteNouvelleActu").val() != "")
                               {
                                   $.post("API/removeImageEnteteActualite.php", {actualite_id: $("#idActu").val()}, function(data){
                                           
                                    });
                                   
                                   var image = document.getElementById("imageEnteteNouvelleActu").files[0];

                                   var xhr = new XMLHttpRequest();
                                   xhr.open('POST', 'API/modifierImageEnteteActualite.php');

                                   var form = new FormData();
                                   form.append("image", image);
                                   form.append("actualite_id", idActu);

                                   xhr.send(form);
                               }
                            if(tabSuppression.length > 0)
                                {
                                    var i = 0;
                                    tabSuppression.forEach(function(tbSuppr){
                                        i++;
                                        $.post("API/removePieceJointeActualite.php", {piece_jointe_id: tbSuppr, actualite_id: $("#idActu").val()}, function(data){
                                            if(i == tabSuppression.length)
                                                {
                                                    if(myDropzone.getUploadingFiles().length == 0 && myDropzone.getQueuedFiles().length == 0)
                                                        {
                                                            if($("#envoiMail").is(":checked"))
                                                            {
                                                                $.post("API/mailNotifModificationActualite.php", {actualite_id: idActu}, function(data){
                                                                    var reponse = JSON.parse(data);
                                                                    if(reponse)
                                                                        {
                                                                            document.location.href = "actualite.php?id=" + idActu;
                                                                        }
                                                                    else{
                                                                        alert("Les mails n'ont pas pu être envoyés aux utilisateurs");
                                                                        $("#validerNouvelleActu").prop("disabled", false);
                                                                        $("#waitValider").hide();
                                                                    }
                                                                });
                                                            }
                                                        else{
                                                            document.location.href = "actualite.php?id=" + idActu;
                                                        }
                                                        }
                                                    else{
                                                        myDropzone.on("complete", function (file) {
                                                          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                                                            if($("#envoiMail").is(":checked"))
                                                            {
                                                                $.post("API/mailNotifModificationActualite.php", {actualite_id: idActu}, function(data){
                                                                    var reponse = JSON.parse(data);
                                                                    if(reponse)
                                                                        {
                                                                            document.location.href = "actualite.php?id=" + idActu;
                                                                        }
                                                                    else{
                                                                        alert("Les mails n'ont pas pu être envoyés aux utilisateurs");
                                                                        $("#validerNouvelleActu").prop("disabled", false);
                                                                        $("#waitValider").hide();
                                                                    }
                                                                });
                                                            }
                                                        else{
                                                            document.location.href = "actualite.php?id=" + idActu;
                                                        }
                                                          }
                                                        });


                                                        myDropzone.on('sending', function(file, xhr, formData){
                                                            formData.append('actualite_id', idActu);
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
                                                $.post("API/mailNotifModificationActualite.php", {actualite_id: idActu}, function(data){
                                                    var reponse = JSON.parse(data);
                                                    if(reponse)
                                                        {
                                                            document.location.href = "actualite.php?id=" + idActu;
                                                        }
                                                    else{
                                                        alert("Les mails n'ont pas pu être envoyés aux utilisateurs");
                                                        $("#validerNouvelleActu").prop("disabled", false);
                                                        $("#waitValider").hide();
                                                    }
                                                });
                                            }
                                        else{
                                            document.location.href = "actualite.php?id=" + idActu;
                                        }
                                    }
                                else{
                                    myDropzone.on("complete", function (file) {
                                      if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                                        if($("#envoiMail").is(":checked"))
                                        {
                                            $.post("API/mailNotifModificationActualite.php", {actualite_id: idActu}, function(data){
                                                var reponse = JSON.parse(data);
                                                if(reponse)
                                                    {
                                                        document.location.href = "actualite.php?id=" + idActu;
                                                    }
                                                else{
                                                    alert("Les mails n'ont pas pu être envoyés aux utilisateurs");
                                                    $("#validerNouvelleActu").prop("disabled", false);
                                                    $("#waitValider").hide();
                                                }
                                            });
                                        }
                                    else{
                                        document.location.href = "actualite.php?id=" + idActu;
                                    }
                                      }
                                    });


                                    myDropzone.on('sending', function(file, xhr, formData){
                                        formData.append('actualite_id', idActu);
                                    });

                                    myDropzone.processQueue();
                                }
                            }
                            
                        }
                    else{
                        alert("Une erreur s'est produite, veuillez réessayer plus tard");
                    }
                });
            }
        });
        }
    };
    
    $("#btnReinitialiser").click(function(){
        window.location.reload();
    });
});