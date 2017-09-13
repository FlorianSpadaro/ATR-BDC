$(function(){
    $.post("API/getUtilisateurById.php", {user_id: $("#user_id").val()}, function(data){
        var user = JSON.parse(data);
    });
    
    $("#btnSupprimerActu").click(function(){
        var idActu = $("#idActu").val();
        $.post("API/removeActualiteById.php", {actualite_id: idActu}, function(data){
            var reponse = JSON.parse(data);
            if(reponse){
                document.location.href = "index.php";
            }
            else{
                alert("Une erreur s'est produite, veuillez réessayer plus tard");
            }
        });
    });
});