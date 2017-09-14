$(function(){
    $('#summernote').summernote({
        height: 300,
        lang: 'fr-FR'
    });
    
    var summerNoteVide = $("#summernote").summernote('code');
    
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
                var idUser = $("#user_id").val();
                var titre = $("#titreNouvelleActu").val();
                var description = $("#descriptionNouvelleActu").val();
                if(description == "")
                    {
                        description = null;
                    }
                var contenu = $("#summernote").summernote('code');

                $.post("API/addActualite.php", {titre: titre, contenu: contenu, utilisateur_id: idUser, description: description}, function(data){
                    var idActu = JSON.parse(data);
                    if(idActu != null)
                        {
                           if($("#imageEnteteNouvelleActu").val() != "")
                               {
                                   var image = document.getElementById("imageEnteteNouvelleActu").files[0];

                                   var xhr = new XMLHttpRequest();
                                   xhr.open('POST', 'API/modifierImageEnteteActualite.php');

                                   var form = new FormData();
                                   form.append("image", image);
                                   form.append("actualite_id", idActu);

                                   xhr.send(form);
                               }
                            if(myDropzone.getUploadingFiles().length == 0 && myDropzone.getQueuedFiles().length == 0)
                                {
                                    document.location.href = "actualite.php?id=" + idActu;
                                }
                            else{
                                myDropzone.on("complete", function (file) {
                                  if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                                    document.location.href = "actualite.php?id=" + idActu;
                                  }
                                });


                                myDropzone.on('sending', function(file, xhr, formData){
                                    formData.append('actualite_id', idActu);
                                });

                                myDropzone.processQueue();
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