$(function () {
    var userId = document.getElementById("user_id").value;
    $.post("API/getNbMessagesNonLuByUtilisateurId.php", {utilisateur_id: userId}, function(data){
        var nbNonLu = parseInt(JSON.parse(data));
        $("#nbMessages").text(nbNonLu);
        $.post("API/getNbNotifsNonVuesByUtilisateurId.php", {utilisateur_id: userId}, function(data2){
            var notifsNonLu = parseInt(JSON.parse(data2));
            $("#nbNotifs").text(notifsNonLu);
            nbNonLu += notifsNonLu;
            if(nbNonLu > 0)
            {
                $("#totalNotif").text(nbNonLu).show();
            }
        });
    });

    $("#attenteConnexion").hide();

    $("#boutonConnexion").click(function (e) {
        e.preventDefault();
        $("#attenteConnexion").show();

        var connexion = {
            login: $("#login").val(),
            mdp: $("#mdp").val()
        };

        $.post("API/connexion.php", connexion, function (data) {
            $("#attenteConnexion").hide();
            var user = JSON.parse(data);

            if (user === null) {
                $("#connexionFooter").css("color", "red").text("Login ou mot de passe incorrect");
            } else {
                document.getElementById("user_id").value = user.id;
                $("#formConnexion").submit();
            }
        });
    });

    $("#dropdownMonCompte").mouseover(function () {
        $(this).dropdown("toggle");
    });

    $('.dropdown-submenu a.test').on("click", function (e) {
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
});
