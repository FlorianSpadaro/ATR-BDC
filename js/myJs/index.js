$(function () {
    
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
});

