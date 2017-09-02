$(function(){
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
});