$(function(){
    $("[type=radio], [type=checkbox]").click(function(e){
        e.preventDefault();
    });

    $("#btnAccepterFormulaire").click(function(){
        $.post("API/validerHabilitationElectrique.php", {formulaire_id: $("#formulaire_id").val()}, function(data){
            var reponse = JSON.parse(data);
            if(reponse)
            {
                document.location.href = "listeHabilitationElectrique.php";
            }
            else{
                alert("Une erreur s'est produite veuillez réessayer plus tard");
            }
        });
    });

    $("#btnRefuserFormulaire").click(function(){
        $.post("API/refuserHabilitationElectrique.php", {formulaire_id: $("#formulaire_id").val()}, function(data){
            var reponse = JSON.parse(data);
            if(reponse)
            {
                document.location.href = "listeHabilitationElectrique.php";
            }
            else{
                alert("Une erreur s'est produite veuillez réessayer plus tard");
            }
        });
    });
});