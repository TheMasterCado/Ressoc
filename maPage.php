<?php if($_GET['id'] == $_SESSION['id']) { ?>
  <script>
  function traiterNouvellePub() {
    $.post("./mc_creerPublication.php", $("#form_nouvellePublication").serialize(), function(data) {
      location.reload(true);
    });
  }
  </script>
  <div class="modal fade" id="nouvellePublication" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Nouvelle publication</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="form_nouvellePublication">
            <textarea class="form-control" name="contenu" rows="4" placeholder="Votre publication" required></textarea>
            <br>
            <input class="form-control" type="text" name="specialite" placeholder="Catégorie de la publication(facultatif)">
            <br>
            <input type="checkbox" name="estQuestion" value="oui">
            <label for="estQuestion">Ceci est une question.</label>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="traiterNouvellePub()">Valider</button>
        </div>
      </div>

    </div>
  </div>

<?php } ?>
