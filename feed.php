<?php
session_start();
if(isset($_SESSION['id'])) {
  if(isset($_SESSION['newUser']))
  header("Location: ./nouvelUtilisateur.php");
}
else {
  header("Location: ./index.php");
}
require 'bd.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>feed</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  </head>
  <body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="./JS/utils.js"></script>
<?php if($_GET['id'] == $_SESSION['id']) { ?>
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#nouvellePublication">Nouvelle publication</button>
<?php } ?>
<?php
  $sql = "SELECT * FROM publication WHERE fk_utilisateur =
  (SELECT pk_utilisateur FROM utilisateur WHERE loginID = {$_GET['id']} AND fk_publication = NULL);";
  $resultat = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  foreach ($resultat as $pos => $publication) {
    echo $publication['texte'] . "<br>";
  }
?>

<div class="modal fade" id="nouvellePublication" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nouvelle publication</h4>
      </div>
      <div class="modal-body">
        <form id="form_nouvellePublication">
          <textarea name="contenu" rows="4" placeholder="Votre publication..." required></textarea>
          <label for="specialte">Catégorie de la publication (facultatif)</label>
          <input type="text" name="specialite">
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

<?php require "maPage.php"; ?>
  </body>
</html>
