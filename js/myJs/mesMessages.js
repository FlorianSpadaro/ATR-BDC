$(function () {
    $("#supprimerSelection").prop("disabled", true);
    
    $("#allMessagesRecus").click(function(){
        if($(this).is(":checked"))
            {
                $(".selectionMessageRecus").each(function(){
                    $(this).prop("checked", "checked");
                });
                $("#supprimerSelection").prop("disabled", false);
            }
        else{
            $(".selectionMessageRecus").each(function(){
                    $(this).removeProp("checked");
                });
            $("#supprimerSelection").prop("disabled", true);
        }
    });
    
    $(".selectionMessageRecus").click(function(){
        var nb = 0;
        $(".selectionMessageRecus:checked").each(function(){
            nb++;
        });
        if(nb>0)
            {
                $("#allMessagesRecus").prop("checked", "checked");
                $("#supprimerSelection").prop("disabled", false);
            }
        else{
            $("#allMessagesRecus").removeProp("checked");
            $("#supprimerSelection").prop("disabled", true);
        }
    });
    
    $("#supprimerSelection").click(function(){
        var tab = [];
        $(".selectionMessageRecus:checked").each(function(){
            tab.push($(this).attr("id"));
        });
        $("#nbMessagesSelectionne").text(tab.length);
        $('#messageSuppression').modal('show');
        $("#confirmationSuppression").click(function(){
            tab.forEach(function(id){
                $.post("API/deleteMessageById.php", {message_id: id}, function(){
                    $("#"+id).removeProp("checked");
                    $("#messageRecu"+id).hide();
                });
            });
            $("#allMessagesRecus").removeProp("checked");
            $('#messageSuppression').modal('hide');
            /*window.location.reload();*/
        });
    });
    
/*  $(".supprimerMessage").click(function(){
      var idMessage = $(this).attr("id");
      $('#messageSuppression').modal('show');
      $("#confirmationSuppression").click(function(){
          console.log(idMessage);
      });*/
      /*if(confirm("Voulez-vou supprimer ce message?"))
          {
              $.post("API/deleteMessageById.php", {message_id: $(this).attr("id")}, function(data){
                  var reponse = JSON.parse(data);
                  if(data){
                      
                  }
                  else{
                      
                  }
              });
          }*/
  //});
});
