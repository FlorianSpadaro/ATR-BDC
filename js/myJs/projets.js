$(function(){
    $("#annulerFiltre").click(function(){
        $("#divFiltres").hide("fade");
        $("#btnFiltres").show("fade");
        $(".cacher").addClass("visible").removeClass("cacher").show();
        $(".visible").each(function(){
            if(!$(this).hasClass("active"))
                {
                    $(this).addClass("active");
                }
        });
        $("#labelDomaineFiltre").hide();
        $("#labelSousDomaineFiltre").hide();
    });
    
    $.post("API/getSecteursDomainesSousdomainesContrats.php", function(data){
        var tab = JSON.parse(data);
        if(tab.secteurs != null)
            {
                $("#labelSecteurFiltre").hide();
                tab.secteurs.forEach(function(secteur){
                    var aElt = document.createElement("a");
                    aElt.href = "#";
                    aElt.id = "secteur-" + secteur.id;
                    aElt.classList += "list-group-item active visible";
                    aElt.textContent = secteur.libelle;
                    $(aElt).click(function(e){
                        e.preventDefault();
                        if($(this).hasClass("active"))
                            {
                                $(this).removeClass("active");
                                $(".eltSecteur" + secteur.id).removeClass("active").removeClass("visible").addClass("cacher").hide();
                                var domainesVisibles = $("#filtreListeDomaines .visible");
                                if(domainesVisibles.length == 0)
                                    {
                                        $("#labelDomaineFiltre").show();
                                    }
                                var sousDomainesVisibles = $("#filtreListeSousDomaines .visible");
                                if(sousDomainesVisibles.length == 0)
                                    {
                                        $("#labelSousDomaineFiltre").show();
                                    }
                            }
                        else{
                            $(this).addClass("active");
                            $(".eltSecteur" + secteur.id).addClass("visible").removeClass("cacher").addClass("active").show();
                            var domainesVisibles = $("#filtreListeDomaines .visible");
                                if(domainesVisibles.length > 0)
                                    {
                                        $("#labelDomaineFiltre").hide();
                                    }
                            var sousDomainesVisibles = $("#filtreListeSousDomaines .visible");
                                if(sousDomainesVisibles.length > 0)
                                    {
                                        $("#labelSousDomaineFiltre").hide();
                                    }
                        }
                    });
                    $(aElt).css("border", "1px solid black");
                    document.getElementById("filtreListeSecteurs").appendChild(aElt);
                });
            }
        if(tab.contrats != null)
            {
                $("#labelContratFiltre").hide();
                tab.contrats.forEach(function(contrat){
                    var aElt = document.createElement("a");
                    aElt.href = "#";
                    aElt.id = "contrat-" + contrat.id;
                    aElt.classList += "list-group-item active visible";
                    aElt.textContent = contrat.libelle;
                    $(aElt).click(function(e){
                        e.preventDefault();
                        if($(this).hasClass("active"))
                            {
                                $(this).removeClass("active");
                                
                            }
                        else{
                            $(this).addClass("active");
                        }
                    });
                    $(aElt).css("border", "1px solid black");
                    document.getElementById("filtreListeContrats").appendChild(aElt);
                });
            }
        if(tab.domaines != null)
            {
                $("#labelDomaineFiltre").hide();
                tab.domaines.forEach(function(domaine){
                    var aElt = document.createElement("a");
                    aElt.href = "#";
                    aElt.id = "domaine-" + domaine.id;
                    aElt.classList += "list-group-item active visible eltSecteur" + domaine.secteur.id;
                    aElt.textContent = domaine.libelle;
                    $(aElt).click(function(e){
                        e.preventDefault();
                        if($(this).hasClass("active"))
                            {
                                $(this).removeClass("active");
                                $(".eltDomaine" + domaine.id).removeClass("visible").addClass("cacher").removeClass("active").hide();
                                var sousDomainesVisibles = $("#filtreListeSousDomaines .visible");
                                if(sousDomainesVisibles.length == 0)
                                    {
                                        $("#labelSousDomaineFiltre").show();
                                    }
                            }
                        else{
                            $(this).addClass("active");
                            $(".eltDomaine" + domaine.id).addClass("visible").removeClass("cacher").addClass("active").show();
                            var sousDomainesVisibles = $("#filtreListeSousDomaines .visible");
                                if(sousDomainesVisibles.length > 0)
                                    {
                                        $("#labelSousDomaineFiltre").hide();
                                    }
                        }
                    });
                    $(aElt).css("border", "1px solid black");
                    document.getElementById("filtreListeDomaines").appendChild(aElt);
                });
            }
        if(tab.sousDomaines != null)
            {
                $("#labelSousDomaineFiltre").hide();
                tab.sousDomaines.forEach(function(sousDomaine){
                    var aElt = document.createElement("a");
                    aElt.href = "#";
                    aElt.id = "sousDomaine-" + sousDomaine.id;
                    aElt.classList += "list-group-item active visible eltSecteur" + sousDomaine.secteur.id + " eltDomaine" + sousDomaine.domaine.id;
                    aElt.textContent = sousDomaine.libelle;
                    $(aElt).click(function(e){
                        e.preventDefault();
                        if($(this).hasClass("active"))
                            {
                                $(this).removeClass("active");
                            }
                        else{
                            $(this).addClass("active");
                        }
                    });
                    $(aElt).css("border", "1px solid black");
                    document.getElementById("filtreListeSousDomaines").appendChild(aElt);
                });
            }
    });
    
    var hauteur = $("#btnSubmitRechercheProjet").css("height");
    var largeur = $("#btnSubmitRechercheProjet").css("width");;
    
    $("#btnNum").click(function(e){
        e.preventDefault();
        $(this).hide();
        $("#formNumPage").show("fade");
        $("#numPage").focus().focusout(function(){
            $("#formNumPage").hide("fade");
            $("#btnNum").show();
        });
        $("#formNumPage").submit(function(e){
            e.preventDefault();
            var numPage = parseInt($("#numPage").val());
            var nbPages = parseInt($("#nbPages").val());
            if(numPage < 1)
                {
                    numPage = 1;
                }
            else if(numPage > nbPages)
                {
                    numPage = nbPages;
                }
            document.location.href = "projets.php?p=" + numPage;
        });
    });
    
    $("#btnFiltres").click(function(e){
        e.preventDefault();
        $(this).hide("fade");
        $("#rechercheProjet").show("fade");
        $("#inputRechercheProjet").focus();
        $("#validerRechercheProjet").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            $("#rechercheProjet").submit();
        });
        /*$(document.body).click(function(e){
            if(($(e.target).attr("id") != "inputRechercheProjet") && ($(e.target).attr("id") != "btnRechercheProjet"))
                {
                    $("#rechercheProjet").hide("fade");
                    $("#btnRechercheProjet").show("fade");
                }
        });*/
    });
    
    $("#rechercheProjet").submit(function(e){
        e.preventDefault();
        console.log("OK");
    });
    
    $("#btnFiltres").click(function(){
        $("#btnFiltres").hide("fade");
        $("#divFiltres").show("fade");
    });
});