$(function (){
   $('a').tooltip({ trigger: "hover" });
    $(".click").click();
    
    $("#btnValiderNewSousDomaine").click(function(){
        var libelle = $("#libelleSousDomaineNew").val();
        var description = $("#domaineSousDomaineNew").val();
        if(description == "")
            {
                description = null;
            }
        var idDomaine = $("#idDomaine").val();
        var idUser = $("#user_id").val();
        if(libelle == "")
            {
                alert("Veuillez saisir un libellé");
            }
        else{
            $.post("API/addSousDomaine.php", {domaine_id: idDomaine, libelle, libelle, description: description, utilisateur_id: idUser}, function(data){
                var reponse = JSON.parse(data);
                if(reponse)
                    {
                        window.location.reload();
                    }
                else{
                    alert("Une erreur s'est produite, veuillez réessayer plus tard");
                }
            });
        }
    });
    
    $("#btnNouveauSousDomaine").click(function(){
        $("#libelleSousDomaineNew").val("");
        $("#domaineSousDomaineNew").val("");
    });
    
    $("#btnValiderModifDomaine").click(function(e){
        e.preventDefault();
        if($("#libelleModifDomaine").val() == "")
            {
                alert("Veuilez saisir un libellé");
            }
        else{
            var idDomaine = $("#idDomaine").val();
            var libelle = $("#libelleModifDomaine").val();
            var idSecteur = $("#secteurModifDomaine").val();
            var description = $("#descriptionModifDomaine").val();
            if(description == "")
                {
                    description = null;
                }
            
            $.post("API/modifierDomaineById.php", {domaine_id: idDomaine, libelle: libelle, secteur_id: idSecteur, description: description}, function(data){
                var reponse = JSON.parse(data);
                if(reponse)
                    {
                        window.location.reload();
                    }
                else{
                    alert("Une erreur s'est produite, veuillez réessayer plus tard");
                }
            });
        }
    });
    
    $("#modificationDomaine").click(function(){
        $("#descriptionModifDomaine").val("");
        var idDomaine = $("#idDomaine").val();
        $.post("API/getDomaineById.php", {domaine_id: idDomaine}, function(data){
            var domaine = JSON.parse(data);
        
            $("#libelleModifDomaine").val(domaine.libelle);
            if(domaine.description != null)
                {
                    $("#descriptionModifDomaine").val(domaine.description);
                }
            $("#secteurModifDomaine").val(domaine.secteur.id);
        });
    });
    
    $("#suppressionDomaine").click(function(e){
        e.preventDefault();
        var repUser = confirm("Voulez-vous vraiment supprimer ce domaine? Cela supprimera également tous les projets et sous-domaines qui lui sont liés");
    });
    
    $(".newProjet").click(function(){
        var idSD = $(this).attr("id").split("-")[1];
        $("#sous_domaine_id").val(idSD);
        document.getElementById("formNouveauProjet").reset();
    });
    
    $("#imageEntete").change(function(){
        var type = this.files[0].type.split("/")[0];
        if(type != "image")
            {
                alert("Veuillez choisir une image");
                $("#imageEntete").val("");
            }
    });
    
    $("#contenuProjet").change(function(){
        var type = this.files[0].type.split("/")[1];
        var type = type.toUpperCase();
        if(type != "HTML" && type != "HTML")
            {
                alert("Veuillez choisir un fichier au format .html ou .htm");
                $("#contenuProjet").val("");
            }
    });
    
    $("#btnValiderNewProjet").click(function(e){
        e.preventDefault();
        var idUser = $("#utilisateur_id").val();
        var idSousDomaine = $("#sous_domaine_id").val();
        var titre = $("#titreProjet").val();
        var description = $("#descriptionProjet").val();
        if(description == "")
            {
                description = null;
            }
        var idContrat = $("#contratProjet").val();
        if(idContrat == 0)
            {
                idContrat = null;
            }
        var contenu = document.getElementById("contenuProjet").files[0];
        var imgEntete = document.getElementById("imageEntete").files[0];
        var pjs = document.getElementById("pjProjet").files;
        
        if(titre == "" || contenu == null)
            {
                alert("Attention: le titre et le fichier du contenu sont obligatoires");
            }
        else{
            $.post("API/addProjet.php", {titre: titre, description: description, sous_domaine_id: idSousDomaine, contrat_id: idContrat, utilisateur_id: idUser}, function(data){
                var idProjet = JSON.parse(data);
                if(idProjet != null)
                    {
                        if(imgEntete != null)
                            {
                                var xhr = new XMLHttpRequest();
                                xhr.open('POST', 'API/modifierImageEnteteProjet.php');
                                
                                var form = new FormData();
                                form.append("projet_id", idProjet);
                                form.append("image", imgEntete);
                                
                                xhr.send(form);
                            }
                        if(pjs != null)
                            {
                                for(var i = 0; i < pjs.length; i++)
                                    {
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST', 'API/addPjProjet.php');

                                        var form = new FormData();
                                        form.append("libelle", pjs[i].name);
                                        form.append("projet_id", idProjet);
                                        form.append("pj", pjs[i]);

                                        xhr.send(form);
                                    }
                            }
                        
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'API/modifierContenuProjet.php');
                        
                        xhr.addEventListener("load", function(){
                            window.location.reload();
                        });
                        
                        var form = new FormData();
                        form.append("projet_id", idProjet);
                        form.append("contenu", contenu);
                        
                        xhr.send(form);
                    }
                else{
                    alert("Une erreur s'est produite, veuillez réessayer plus tard");
                }
            });
        }
    });
});