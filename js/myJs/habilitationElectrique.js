$(function(){
    $.post("API/getDernierFormulaireByUtilisateurId.php", {utilisateur_id: $("#user_id").val()}, function(data){
        var formulaire = JSON.parse(data);
        if(formulaire != null && formulaire.brouillon == true)
        {
            $("#brouillon_id").val(formulaire.id);

            $("#q1_ans1").val(formulaire.q1_ans1);
            $("#q1_ans2").val(formulaire.q1_ans2);
            $("#q1_ans3").val(formulaire.q1_ans3);

            if(formulaire.q2_ans1 == true)
            {
                $("#q2_ans1").click();
            }
            if(formulaire.q2_ans2 == true)
            {
                $("#q2_ans2").click();
            }

            if(formulaire.q3_ans1 == true)
            {
                $("#q3_ans1").click();
            }
            if(formulaire.q3_ans2 == true)
            {
                $("#q3_ans2").click();
            }
            if(formulaire.q3_ans3 == true)
            {
                $("#q3_ans3").click();
            }

            if(formulaire.q4_ans1 == true)
            {
                $("#q4_ans1").click();
            }
            if(formulaire.q4_ans2 == true)
            {
                $("#q4_ans2").click();
            }
            if(formulaire.q4_ans3 == true)
            {
                $("#q4_ans3").click();
            }

            if(formulaire.q5_ans1 == true)
            {
                $("#q5_ans1").click();
            }
            if(formulaire.q5_ans2 == true)
            {
                $("#q5_ans2").click();
            }

            $("#q6_ans1").val(formulaire.q6_ans1);

            if(formulaire.q7_ans1 == true)
            {
                $("#q7_ans1").click();
            }
            else{
                if(formulaire.q7_ans1 == false)
                {
                    $("#q7_ans2").click();
                }
            }
            if(formulaire.q7_ans2 == true)
            {
                $("#q7_ans3").click();
            }
            else{
                if(formulaire.q7_ans2 == false)
                {
                    $("#q7_ans4").click();
                }
            }

            if(formulaire.q8_ans1 == true)
            {
                $("#q8_ans1").click();
            }
            else{
                if(formulaire.q8_ans1 == false)
                {
                    $("#q8_ans2").click();
                }
            }
            if(formulaire.q8_ans2 == true)
            {
                $("#q8_ans3").click();
            }
            else{
                if(formulaire.q8_ans2 == false)
                {
                    $("#q8_ans4").click();
                }
            }

            if(formulaire.q9_ans1 == true)
            {
                $("#q9_ans1").click();
            }
            else{
                if(formulaire.q9_ans1 == false)
                {
                    $("#q9_ans2").click();
                }
            }
            if(formulaire.q9_ans2 == true)
            {
                $("#q9_ans3").click();
            }
            else{
                if(formulaire.q9_ans2 == false)
                {
                    $("#q9_ans4").click();
                }
            }
            if(formulaire.q9_ans3 == true)
            {
                $("#q9_ans5").click();
            }
            else{
                if(formulaire.q9_ans3 == false)
                {
                    $("#q9_ans6").click();
                }
            }

            if(formulaire.q10_ans1 == true)
            {
                $("#q10_ans1").click();
            }
            else{
                if(formulaire.q10_ans1 == false)
                {
                    $("#q10_ans2").click();
                }
            }

            if(formulaire.q11_ans1 == true)
            {
                $("#q11_ans1").click();
            }
            else{
                if(formulaire.q11_ans1 == false)
                {
                    $("#q11_ans2").click();
                }
            }

            if(formulaire.q12_ans1 == true)
            {
                $("#q12_ans1").click();
            }
            else{
                if(formulaire.q12_ans1 == false)
                {
                    $("#q12_ans2").click();
                }
            }
            if(formulaire.q12_ans2 == true)
            {
                $("#q12_ans3").click();
            }
            else{
                if(formulaire.q12_ans2 == false)
                {
                    $("#q12_ans4").click();
                }
            }

            if(formulaire.q13_ans1 == true)
            {
                $("#q13_ans1").click();
            }
            else{
                if(formulaire.q13_ans1 == false)
                {
                    $("#q13_ans2").click();
                }
            }
            if(formulaire.q13_ans2 == true)
            {
                $("#q13_ans3").click();
            }
            else{
                if(formulaire.q13_ans2 == false)
                {
                    $("#q13_ans4").click();
                }
            }
            if(formulaire.q13_ans3 == true)
            {
                $("#q13_ans5").click();
            }
            else{
                if(formulaire.q13_ans3 == false)
                {
                    $("#q13_ans6").click();
                }
            }

            if(formulaire.q14_ans1 != null)
            {
                $("#choix1").val(formulaire.q14_ans1);
            }
            if(formulaire.q14_ans2 != null)
            {
                $("#choix2").val(formulaire.q14_ans2);
            }
            if(formulaire.q14_ans3 != null)
            {
                $("#choix3").val(formulaire.q14_ans3);
            }
            if(formulaire.q14_ans4 != null)
            {
                $("#choix4").val(formulaire.q14_ans4);
            }
            if(formulaire.q14_ans5 != null)
            {
                $("#choix5").val(formulaire.q14_ans5);
            }
            if(formulaire.q14_ans6 != null)
            {
                $("#choix6").val(formulaire.q14_ans6);
            }
            if(formulaire.q14_ans7 != null)
            {
                $("#choix7").val(formulaire.q14_ans7);
            }
        }
    });

    $(".selectQ14").change(function(){
        $(this).children(".firstOption").attr("disabled", true);
        $(".selectQ14").each(function(){
            $(this).children("option").prop("disabled", false);
        });
        $(".selectQ14").each(function(){
            var val = $(this).val();
            $(".selectQ14").each(function(){
                $(this).children("option").each(function(){
                    if($(this).val() == val && !$(this).hasClass("firstOption"))
                    {
                        $(this).attr("disabled", true);
                    }
                });
            });
        });
    });

    function getReponsesUtilisateur()
    {
        var formulaire = {};
        formulaire.q1_ans1 = $("#q1_ans1").val();
        formulaire.q1_ans2 = $("#q1_ans2").val();
        formulaire.q1_ans3 = $("#q1_ans3").val();

        formulaire.q2_ans1 = $("#q2_ans1").prop("checked");
        if(formulaire.q2_ans1 == 1)
        {
            formulaire.q2_ans1 = true;
        }
        else{
            formulaire.q2_ans1 = false;
        }
        formulaire.q2_ans2 = $("#q2_ans1").prop("checked");

        formulaire.q3_ans1 = $("#q3_ans1").prop("checked");
        formulaire.q3_ans2 = $("#q3_ans2").prop("checked");
        formulaire.q3_ans3 = $("#q3_ans3").prop("checked");

        formulaire.q4_ans1 = $("#q4_ans1").prop("checked");
        formulaire.q4_ans2 = $("#q4_ans2").prop("checked");
        formulaire.q4_ans3 = $("#q4_ans3").prop("checked");

        formulaire.q5_ans1 = $("#q5_ans1").prop("checked");
        formulaire.q5_ans2 = $("#q5_ans2").prop("checked");

        formulaire.q6_ans1 = $("#q6_ans1").val();

        formulaire.q7_ans1 = $("#q7_ans1").prop("checked");
        if(formulaire.q7_ans1 == false && $("#q7_ans2").prop("checked") == false)
        {
            formulaire.q7_ans1 = null;
        }
        formulaire.q7_ans2 = $("#q7_ans3").prop("checked");
        if(formulaire.q7_ans2 != true && $("#q7_ans4").prop("checked") != true)
        {
            formulaire.q7_ans2 = null;
        }
        
        formulaire.q8_ans1 = $("#q8_ans1").prop("checked");
        if(formulaire.q8_ans1 != true && $("#q8_ans2").prop("checked") != true)
        {
            formulaire.q8_ans1 = null;
        }
        formulaire.q8_ans2 = $("#q8_ans3").prop("checked");
        if(formulaire.q8_ans2 != true && $("#q8_ans4").prop("checked") != true)
        {
            formulaire.q8_ans2 = null;
        }

        formulaire.q9_ans1 = $("#q9_ans1").prop("checked");
        if(formulaire.q9_ans1 != true && $("#q9_ans2").prop("checked") != true)
        {
            formulaire.q9_ans1 = null;
        }
        formulaire.q9_ans2 = $("#q9_ans3").prop("checked");
        if(formulaire.q9_ans2 != true && $("#q9_ans4").prop("checked") != true)
        {
            formulaire.q9_ans2 = null;
        }
        formulaire.q9_ans3 = $("#q9_ans5").prop("checked");
        if(formulaire.q9_ans3 != true && $("#q9_ans6").prop("checked") != true)
        {
            formulaire.q9_ans3 = null;
        }

        formulaire.q10_ans1 = $("#q10_ans1").prop("checked");
        if(formulaire.q10_ans1 != true && $("#q10_ans2").prop("checked") != true)
        {
            formulaire.q10_ans1 = null;
        }

        formulaire.q11_ans1 = $("#q11_ans1").prop("checked");
        if(formulaire.q11_ans1 != true && $("#q11_ans2").prop("checked") != true)
        {
            formulaire.q11_ans1 = null;
        }

        formulaire.q12_ans1 = $("#q12_ans1").prop("checked");
        if(formulaire.q12_ans1 != true && $("#q12_ans2").prop("checked") != true)
        {
            formulaire.q12_ans1 = null;
        }
        formulaire.q12_ans2 = $("#q12_ans3").prop("checked");
        if(formulaire.q12_ans2 != true && $("#q12_ans4").prop("checked") != true)
        {
            formulaire.q12_ans2 = null;
        }

        formulaire.q13_ans1 = $("#q13_ans1").prop("checked");
        if(formulaire.q13_ans1 != true && $("#q13_ans2").prop("checked") != true)
        {
            formulaire.q13_ans1 = null;
        }
        formulaire.q13_ans2 = $("#q13_ans3").prop("checked");
        if(formulaire.q13_ans2 != true && $("#q13_ans4").prop("checked") != true)
        {
            formulaire.q13_ans2 = null;
        }
        formulaire.q13_ans3 = $("#q13_ans5").prop("checked");
        if(formulaire.q13_ans3 != true && $("#q13_ans6").prop("checked") != true)
        {
            formulaire.q13_ans3 = null;
        }

        formulaire.q14_ans1 = parseInt($("#choix1").children("option:selected").text());
        formulaire.q14_ans2 = parseInt($("#choix2").children("option:selected").text());
        formulaire.q14_ans3 = parseInt($("#choix3").children("option:selected").text());
        formulaire.q14_ans4 = parseInt($("#choix4").children("option:selected").text());
        formulaire.q14_ans5 = parseInt($("#choix5").children("option:selected").text());
        formulaire.q14_ans6 = parseInt($("#choix6").children("option:selected").text());
        formulaire.q14_ans7 = parseInt($("#choix7").children("option:selected").text());

        formulaire.utilisateur_id = $("#user_id").val();
        
        return formulaire;
    }

    $("#brouillon-habilitation").click(function(){

        var formulaire = getReponsesUtilisateur();
        formulaire.brouillon = true;
        formulaire = JSON.stringify(formulaire);

        $.post("API/addHabilitationElectrique.php", {formulaire: formulaire}, function(data){
            var reponse = JSON.parse(data);
            if(reponse)
            {
                if($("#brouillon_id").val() != "NULL")
                {
                    $.post("API/removeHabilitationElectrique.php", {formulaire_id: $("#brouillon_id").val()}, function(data){
                        var reponse = JSON.parse(data);
                        document.location.href = "index.php";
                    });
                }
                else{
                    document.location.href = "index.php";
                }
            }
            else{
                alert("Une erreur s'est produite, veuillez réessayer plus tard");
            }
        });
    });

    $("#valid-habilitation").click(function(){
        var formulaire = getReponsesUtilisateur();
        formulaire.brouillon = false;
        formulaire = JSON.stringify(formulaire);

        $.post("API/addHabilitationElectrique.php", {formulaire: formulaire}, function(data){
            var reponse = JSON.parse(data);
            if(reponse)
            {
                if($("#brouillon_id").val() != "NULL")
                {
                    $.post("API/removeHabilitationElectrique.php", {formulaire_id: $("#brouillon_id").val()}, function(data){
                        var reponse = JSON.parse(data);
                        document.location.href = "index.php";
                    });
                }
                else{
                    document.location.href = "index.php";
                }
            }
            else{
                alert("Une erreur s'est produite, veuillez réessayer plus tard");
            }
        });
    });
});