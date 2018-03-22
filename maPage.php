<?php if($id == $_SESSION['id'] || $id == "ALL") { ?>
  <script>
  var toDelete = null;

  function traiterNouvellePub() {
    if ($("#contenu").val().trim().length <= 1) {
      alert("Une publication ne doit pas être vide");
    } else {
    $.post("./mc_creerPublication.php", {
      'contenu' : $("#contenu").val(),
      'specialite' : $("#specialite").val(),
      'estQuestion' : ($("#estQuestion").is(':checked') ? "oui" : "non")}, function(data) {
        location.reload(true);
    });
   }
  }

  function traiterSuppression() {
    if(toDelete != null) {
      $.post("./mc_traiterSuppression.php", {
        'pk_publication' : toDelete}, function(data) {
          location.reload(true);
      });
    }
  }

  function preparerSuppression(pk_publication) {
    toDelete = pk_publication;
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
          <textarea class="form-control" id="contenu" rows="6" placeholder="Entrez votre publication." required></textarea>
          <br>
          <input id="specialite" class="form-control" type="text" placeholder="Catégorie de la publication(facultatif)">
          <br>
          <input id="estQuestion" name="estQuestion" type="checkbox" value="oui">
          <label for="estQuestion">Ceci est une question.</label>
          <a class="stay-right" href="./publication.php?id=1">Aide avec le formatage du texte</a>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="traiterNouvellePub()">Valider</button>
        </div>
      </div>

    </div>
  </div>

  <div class="modal fade" id="confirmationSuppression" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Confirmation de la suppression</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          Voulez-vous vraiment supprimer cela?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger stay-left" data-dismiss="modal">Non</button>
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="traiterSuppression()">Oui</button>
        </div>
      </div>

    </div>
  </div>

<?php } ?>
