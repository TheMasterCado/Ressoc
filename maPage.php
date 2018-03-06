<?php if($_GET['id'] == $_SESSION['id']) { ?>

  <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->
  <script>
    function traiterNouvellePub() {
      $.post("./mc_creerPublication.php", toJSON($("#form_nouvellePublication").serializeArray()), function(data) {
          location.reload(true);
        }
      });
    }
  </script>
  <div class="modal fade" id="nouvellePublication" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nouvelle publication</h4>
        </div>
        <div class="modal-body">
          <form id="form_nouvellePublication">
            <!-- VERIFIER LES CHAMPS NECESSAIRES (titre, message, checkbox question, etc) -->
            <input type="text" name="titre" placeholder="Titre de la publication">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="traiterNouvellePub()">Valider</button>
        </div>
      </div>

    </div>
  </div>

<?php } ?>
