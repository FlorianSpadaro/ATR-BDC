$(function(){
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
                                $("#sousDomaineProjet").trigger("chosen:updated");
                            });

                            document.getElementById("sousDomaineProjet").appendChild(optgroupElt);
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
                $("#sousDomaineProjet").trigger("chosen:updated");
                $("#domainesProjet").chosen();
            }
        else if($(this).val() == "projetSpecifique")
            {
                $("#divProjetSpecifique").show("fade");
                $("#divProjetGenerique").hide();
                $("#domainesProjet").trigger("chosen:updated");
                $("#sousDomaineProjet").trigger("chosen:updated");
                $("#sousDomaineProjet").chosen();
            }
    });
    
    $("#secteurProjet").change(function(){
        $("#sousDomaineProjet").html("");
        $("#domainesProjet").html("");
        actualiserHautFormulaire();
    });
    
    actualiserHautFormulaire();
});