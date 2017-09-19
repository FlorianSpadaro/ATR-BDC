$(function(){
    $("#supprimerProjet").click(function(e){
        e.preventDefault();
        var repUser = confirm("Voulez-vous vraiment supprimer ce projet?");
        if(repUser)
            {
                $.post("API/removeProjetById.php", {projet_id: $("#projet_id").val()}, function(data){
                    console.log(data);
                    var reponse = JSON.parse(data);
                    if(reponse)
                        {
                            document.location.href = "index.php";
                        }
                    else{
                        alert("Une erreur s'est produite, veuillez réessayer plus tard");
                    }
                });
            }
    });
});