<div>
<?php
    if(isset($_POST["nc"]) && $_POST["nc"] == "true")
    {
        ?>
        <input type="hidden" name="nc" id="nc" value="true" />
        <?php
    }
    else{
        ?>
        <input type="hidden" name="nc" id="nc" value="false" />
        <?php
    }
    require_once("API/fonctions.php");
    $listeMiniatures = json_decode(getMiniatures());
    if($listeMiniatures != null)
    {
        foreach($listeMiniatures as $miniature)
        {
            ?>
            <div class="radio col-lg-3" style="display: flex" style="justify-content: space-between">
                <div>
                    <label class="radio"><input type="radio" name="miniature" value="miniature-<?php echo $miniature->id ?>" id="miniature-<?php echo $miniature->id ?>" /> <img width="50px" height="50px" src="<?php echo $miniature->url ?>" /></label>
                </div>
                <div>
                    <button id="btnSupprMiniature-<?php echo $miniature->id ?>" type="button" class="close btnSupprMiniature"><span class="glyphicon glyphicon-remove"></span></button>
                </div>
            </div>
            <?php
        }
    }
?>
</div>

<script>
    //$('input[name=radio]:first').prop("checked", true);
    
    $(".btnSupprMiniature").click(function(){
        var elt = $(this);
        var id = $(this).attr("id").split("-")[1];
        
        $.post("API/getContratsIdByMiniatureId.php", {miniature_id: id}, function(data2){
            
            var listeIdContrats = JSON.parse(data2);
            if(listeIdContrats.length > 0)
                {
                    alert("Impossible de supprimer cette miniature car elle est liée à un contrat");
                }
            else{
                var repUser = confirm("Voulez-vous vraiment supprimer cette miniature?");
                if(repUser)
                    {
                        $.post("API/removeMiniatureById.php", {miniature_id: id}, function(data){
                            var reponse = JSON.parse(data);
                            if(reponse)
                                {
                                   if(elt.closest("div.radio").children("div:first").children("label").children("input:first").prop("checked"))
                                       {
                                           $("div.radio:first").children("div:first").children("label").children("input:first").click();
                                       }
                                    elt.closest("div.radio").hide();
                                }
                            else{
                                alert("Une erreur s'est produite, veuillez réessayer plus tard");
                            }
                        });
                    }
            }
        });
    });
    
    if($("#nc").val() == "true")
        {
           $("div.radio:first").children("div:first").children("label").children("input:first").click();
        }
</script>