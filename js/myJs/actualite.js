$(function(){
    $.post("API/getUtilisateurById.php", {user_id: $("#user_id").val()}, function(data){
        var user = JSON.parse(data);
    });
});