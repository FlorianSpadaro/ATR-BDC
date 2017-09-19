$(function () {
    $("#searchBarOption").hide();
    
    function chargerActus(numPremActu, nbActus) {
        $("#notif").prop("disabled", "true");
        $("#chargement").show();
        $.post("API/getActualitesByNum.php", {
            numPremActu: numPremActu,
            nbActus: nbActus
        }, function (data) {
            $("#chargement").hide();
            $("#notif").removeAttr("disabled");

            var actus = JSON.parse(data);
            actus.forEach(function (actu) {
                var modalDiv = document.getElementById("modal");
                modalDiv.innerHTML += '<div class="modal fade" id="infos' + actu.utilisateur.id + '"><div class="modal-dialog"><div class="modal-content"></div></div></div>';

                var divElt = document.createElement("div");
                divElt.classList += "post-preview";
                

                var aElt = document.createElement("a");
                aElt.href = "actualite.php?id=" + actu.id;

                var h2Elt = document.createElement("h2");
                h2Elt.classList += "post-title";
                h2Elt.textContent = actu.titre;
                aElt.appendChild(h2Elt);

                var h3Elt = document.createElement("h3");
                h3Elt.classList += "post-subtitle";
                h3Elt.textContent = actu.description;
                aElt.appendChild(h3Elt);

                divElt.appendChild(aElt);

                pElt = document.createElement("p");
                pElt.classList += "post-meta";
                pElt.innerHTML = "Posté par <a href='infosUtilisateur.php?id=" + actu.utilisateur.id + "' data-toggle='modal' data-target='#infos" + actu.utilisateur.id + "'>" + actu.utilisateur.nom.toUpperCase() + " " + actu.utilisateur.prenom.charAt(0).toUpperCase() + actu.utilisateur.prenom.slice(1).toLowerCase() + "</a><span class='pull-right'>" + actu.date_creation.slice(0, 19) + "</span>";
                divElt.appendChild(pElt);

                var listeActus = document.getElementById("listeActus");
                listeActus.appendChild(divElt);
                listeActus.innerHTML += "<hr>";
            });

            var ulElt = document.createElement("ul");
            ulElt.classList += "pager";

            var liElt = document.createElement("li");
            liElt.classList += "next";

            var buttonElt = document.createElement("button");
            buttonElt.classList += "btn btn-default pull-right";
            buttonElt.innerHTML = "Anciennes actus &rarr;";
            buttonElt.addEventListener("click", function(e){
                $(e.target).hide();
                var i = numPremActu+5;
                chargerActus(i, nbActus, true);
            });

            liElt.appendChild(buttonElt);
            ulElt.appendChild(liElt);
            document.getElementById("listeActus").appendChild(ulElt);
        });
    };
    
    chargerActus(0, 5);
          
        

        $("#searchBar").on("input",function(){
            $.post("API/getSearchProjetBySearchBar.php",{search_text: $("#searchBar").val()}, function(data){
                var searchResult = JSON.parse(data);
                var i = 1;
                var resultSearch = "<optgroup label='Projet:'>";
                $("#searchBarOption").html(null);
                if(searchResult != null)
                    {
                        for( i ; i <= searchResult.length;i++){
                   resultSearch += '<option class="searchOption" projet="'+searchResult[i - 1].id+'">'+searchResult[i - 1].titre+'</option>';
                         
                }
                    }
                else
                    {
                        resultSearch += '<option class="searchOption noResultOption" disabled>Pas de résultats</option>';
                    }
                $("#searchBarOption").append( resultSearch + '</optgroup><optgroup label="Autres:"><option value="'+$("#searchBar").val()+'" id="searchOptionProjetId" class="searchOption searchOptionProjet" projet="projet">Rechercher "'+$("#searchBar").val()+'" dans le contenu des projets</option><option value="'+$("#searchBar").val()+'" class="searchOption searchOptionProjet" projet="actu" id="searchOptionActuId">Rechercher "'+$("#searchBar").val()+'" dans actu</option></optgroup>');
               

                $("#searchBarOption").attr('size',5);
                if($("#searchBar").val() != "")
                    {
                        $("#searchBarOption").fadeIn("fast")
                        if(searchResult != null){
                            $("#searchBarOption").attr('size',searchResult.length + 4)
                        }
                        else
                        {
                             $("#searchBarOption").attr('size',5)
                        }
                        
                    }
                else
                    {
                        $("#searchBarOption").hide()
                    }
                
            })
             $.post("API/getSearchProjetByProjectSearch.php",{search_text: $("#searchBar").val()}, function(data){
                 var result = JSON.parse(data);
                 if(result == null){
                     console.log("0");
                     $("#searchOptionProjetId").append(" (0)");
                 }
                 else
                {
                    console.log(result.length);
                    $("#searchOptionProjetId").append(" (" + result.length + ")");
                }
                 
             });
        });
        

            $("#searchBarOption").click(function() {
                var projet_id = $('option:selected', this).attr('projet');
                if (projet_id == "projet" || projet_id == "actu") 
                {
                    if(projet_id == "projet")
                    {
                        document.location.href = "projets.php?searchbar=" + $("#searchBar").val();
                    }
                } else 
                {
                    document.location.href = "projet.php?id=" + projet_id;
                }

            })
});

