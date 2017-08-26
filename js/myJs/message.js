$(function () {
    $("#reponse").on("keyup", function () {
        if ($("#reponse").val().length > 0 && $("#reponse").val() !== "") {
            $("#nbCarac").text($("#reponse").val().length);
            $("#envoyerReponse").prop("disabled", false);
        } else {
            $("#nbCarac").text("0");
            $("#envoyerReponse").prop("disabled", true);
        }
    });
});


