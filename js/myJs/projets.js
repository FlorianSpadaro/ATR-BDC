$(function(){
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
    
    $("#btnRechercheProjet").click(function(e){
        e.preventDefault();
        $(this).hide("fade");
        $("#rechercheProjet").show("fade");
        $("#inputRechercheProjet").focus();
        $("#validerRechercheProjet").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            $("#rechercheProjet").submit();
        });
        $(document.body).click(function(e){
            if(($(e.target).attr("id") != "inputRechercheProjet") && ($(e.target).attr("id") != "btnRechercheProjet"))
                {
                    $("#rechercheProjet").hide("fade");
                    $("#btnRechercheProjet").show("fade");
                }
        });
        /*$("#rechercheProjet").focusout(function(){
            $("#rechercheProjet").hide("fade");
            $("#btnRechercheProjet").show("fade");
        })*/;
    });
    
    $("#rechercheProjet").submit(function(e){
        e.preventDefault();
        console.log("OK");
    });
    
    /*$(document.body).click(function(e){
        console.log($(e.target).attr("id"));
    });
    */
});