$(function(){
    var nbNvContrat = 0;
    var nbMdContrat = 0;
    $("#btnCreationNvContrat").click(function(){
        if(nbNvContrat == 0)
            {
                nbNvContrat++;
                $("#listeMiniaturesNouveauContrat").load("listeMiniatures.php", {nc: true}, function(){
                //$('input[name=miniature]:first').delay(200).click();
            });
                
            }
        else{
            $("#listeMiniaturesNouveauContrat").unload(function(){
            $("#listeMiniaturesNouveauContrat").load("listeMiniatures.php", {nc: true}, function(){
                //$('input[name=miniature]:first').delay(200).click();
            });
        });
        }
        
    });
    
    $("#btnModifierContrat").click(function(){
        if(nbMdContrat == 0)
            {
                $("#listeMiniatures").load("listeMiniatures.php", {nc: false}, function(){
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
            }
        else{
            $("#listeMiniatures").unload(function(){
            $("#listeMiniatures").load("listeMiniatures.php", {nc: false}, function(){
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
        });
        }
        
        
    });
    
   $("#ajouterNouvelleMiniatureNC").click(function(e){
        e.preventDefault();
        
        $(".erreurNouvelleMiniatureNC").hide();
        
        if($("#nomNouvelleMiniatureNC").val() == "")
            {
                $("#erreurNomMiniatureNC").show();
            }
        else if($("#fichierMiniatureNC").val() == "")
            {
                $("#erreurFichierMiniatureNC").show();
            }
        else{
            var fichier = document.getElementById("fichierMiniatureNC").files[0];
            if(fichier.type.split("/")[0] != "image")
                {
                    $("#fichierMiniatureNC").val("");
                    alert("Veuillez choisir une image");
                }
            else{
                if(fichier.size <= 1000000)
                    {
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "API/addMiniature.php");

                        var form = new FormData();
                        form.append("nom", $("#nomNouvelleMiniatureNC").val());
                        form.append("miniature", fichier);

                        xhr.send(form);
                        
                        xhr.addEventListener('load', function() {
                            $("#listeMiniaturesNouveauContrat").load("listeMiniatures.php", {}, function(){
                                $('input[name=miniature]:first').click();
                                $("#annulerAjoutMiniatureNC").click();
                            });
                            /*$.post("API/getMiniatures.php", {}, function(data){
                                var miniatures = JSON.parse(data);
                                if(miniatures != null)
                                    {
                                        document.getElementById("listeMiniaturesNouveauContrat").innerHTML = "";
                                        miniatures.forEach(function(miniature){
                                            var divElt = document.createElement("div");
                                            divElt.classList += "radio";

                                            var labelElt = document.createElement("label");
                                            labelElt.classList += "radio";
                                            labelElt.setAttribute("for", "miniature-" + miniature.id);

                                            var inputElt = document.createElement("input");
                                            inputElt.type = "radio";
                                            inputElt.setAttribute("name", "miniature");
                                            inputElt.value = "miniature-" + miniature.id;
                                            inputElt.id = inputElt.value;

                                            var imgElt = document.createElement("img");
                                            imgElt.setAttribute("width", "50px");
                                            imgElt.setAttribute("height", "50px");
                                            imgElt.src = miniature.url;


                                            labelElt.appendChild(inputElt);
                                            labelElt.appendChild(imgElt);

                                            divElt.appendChild(labelElt);

                                            document.getElementById("listeMiniaturesNouveauContrat").appendChild(divElt);
                                            $(labelElt).click();
                                        });
                                    }
                                $("#annulerAjoutMiniatureNC").click();
                            });*/
                        });
                    }
                else{
                    $("#fichierMiniatureNC").val("");
                    alert("Veuillez choisir un fichier de 1 Mo ou moins");
                }
            }
        }
    });
    
    $("#annulerAjoutMiniatureNC").click(function(e){
        e.preventDefault();
        $("#divAjouterMiniatureNC").hide("fade");
        $("#btnAjouterMiniatureNC").show("fade");
        $("#fichierMiniatureNC").val("");
        $("#nomNouvelleMiniatureNC").val("");
        $(".erreurNouvelleMiniatureNC").hide();
    });
    
    $("#btnAjouterMiniatureNC").click(function(e){
        e.preventDefault();
        $(this).hide("fade");
        $("#divAjouterMiniatureNC").show("fade");
    });
    
    
    
    //ANCIEN
    
    $("#ajouterNouvelleMiniature").click(function(e){
        e.preventDefault();
        
        $(".erreurNouvelleMiniature").hide();
        
        if($("#nomNouvelleMiniature").val() == "")
            {
                $("#erreurNomMiniature").show();
            }
        else if($("#fichierMiniature").val() == "")
            {
                $("#erreurFichierMiniature").show();
            }
        else{
            var fichier = document.getElementById("fichierMiniature").files[0];
            if(fichier.type.split("/")[0] != "image")
                {
                    $("#fichierMiniature").val("");
                    alert("Veuillez choisir une image");
                }
            else{
                if(fichier.size <= 1000000)
                    {
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "API/addMiniature.php");

                        var form = new FormData();
                        form.append("nom", $("#nomNouvelleMiniature").val());
                        form.append("miniature", fichier);

                        xhr.send(form);
                        
                        xhr.addEventListener('load', function() {
                            $("#listeMiniatures").load("listeMiniatures.php", {}, function(){
                                var id = $("#listeContrats .active").attr("id").split("-")[1];
                                $.post("API/getContratById.php", {contrat_id: id}, function(data){
                                    var contrat = JSON.parse(data);
                                    if(contrat != null)
                                        {
                                            $("#libelleContrat").val(contrat.libelle);
                                            $("#miniature-" + contrat.miniature.id).click();
                                        }
                                });
                                $("#annulerAjoutMiniature").click();
                            });
                            /*$.post("API/getMiniatures.php", {}, function(data){
                                var miniatures = JSON.parse(data);
                                if(miniatures != null)
                                    {
                                        document.getElementById("listeMiniatures").innerHTML = "";
                                        miniatures.forEach(function(miniature){
                                            var divElt = document.createElement("div");
                                            divElt.classList += "radio";

                                            var labelElt = document.createElement("label");
                                            labelElt.classList += "radio";
                                            labelElt.setAttribute("for", "miniature-" + miniature.id);

                                            var inputElt = document.createElement("input");
                                            inputElt.type = "radio";
                                            inputElt.setAttribute("name", "miniature");
                                            inputElt.value = "miniature-" + miniature.id;
                                            inputElt.id = inputElt.value;

                                            var imgElt = document.createElement("img");
                                            imgElt.setAttribute("width", "50px");
                                            imgElt.setAttribute("height", "50px");
                                            imgElt.src = miniature.url;


                                            labelElt.appendChild(inputElt);
                                            labelElt.appendChild(imgElt);

                                            divElt.appendChild(labelElt);

                                            document.getElementById("listeMiniatures").appendChild(divElt);
                                            $(labelElt).click();
                                        });
                                    }
                                $("#annulerAjoutMiniature").click();
                            });*/
                        });
                    }
                else{
                    $("#fichierMiniature").val("");
                    alert("Veuillez choisir un fichier de 1 Mo ou moins");
                }
            }
        }
    });
    
    $("#annulerAjoutMiniature").click(function(e){
        e.preventDefault();
        $("#divAjouterMiniature").hide("fade");
        $("#btnAjouterMiniature").show("fade");
        $("#fichierMiniature").val("");
        $("#nomNouvelleMiniature").val("");
        $(".erreurNouvelleMiniature").hide();
    });
    
    $("#btnAjouterMiniature").click(function(e){
        e.preventDefault();
        $(this).hide("fade");
        $("#divAjouterMiniature").show("fade");
    });
    
    //$('input[name=miniature]:first').click();
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
        $('input[name=miniature]:first').click();
        $("#libelleNouveauContrat").val("");
        $("#validerNouveauContrat").prop("disabled", true);
    });
    
    $("#validerNouveauContrat").click(function(e){
        e.preventDefault();
        var libelle = $("#libelleNouveauContrat").val();
        var idUser = $("#user_id").val();
        var idMiniature = $('input[name=miniature]:checked').val().split("-")[1];
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