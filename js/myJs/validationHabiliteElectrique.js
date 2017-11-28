$(function(){
    $("[type=radio], [type=checkbox]").click(function(e){
        e.preventDefault();
    });

    $.post("API/getFormulaireHabilitationElectriqueById.php", {formulaire_id: $("#formulaire_id").val()}, function(data){
        var formulaire = JSON.parse(data);
        $.post("API/getUtilisateurById.php", {user_id: formulaire.utilisateur_id}, function(data){
            var user = JSON.parse(data);
            $("#divHabilElec").load("titreHabilitationElectrique/habil.php", {nom: user.nom, prenom: user.prenom, fonction: "Chargé d'affaire / Ingenieriste", date: formulaire.date}, function(){
                
            });
        });
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

    $('#enregistrerTitreHabilElec').click(function () {
        var pdf = new jsPDF('p', 'pt', 'letter');
        pdf.addHTML($('#divHabilElec')[0], function () {
            pdf.save('Test.pdf');
        });
    });

    /*var doc = new jsPDF();
    var specialElementHandlers = {
        '#editor': function (element, renderer) {
            return true;
        }
    };
    
    $('#btnImprimer').click(function () {
        doc.addHTML($('#divHabilElec').html(), 15, 15, {
            'width': 170,
                'elementHandlers': specialElementHandlers
        });
        doc.save('titreHabilElec.pdf');
    });*/

    $("#btnImprimer").click(function(){
        var divToPrint= document.getElementById('divHabilElec');

        var newWin=window.open('','Print-Window');
        
          newWin.document.open();
        
          newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        
          newWin.document.close();
        
          setTimeout(function(){newWin.close();},10);
    });
});