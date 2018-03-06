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
  </head>
  <body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="./JS/utils.js"></script>
  <nav>
<?php if($_GET['id'] == $_SESSION['id']) { ?>
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#nouvellePublication">Nouvelle publication</button>
<?php } ?>
  </nav>
<?php
  $sql = "SELECT * FROM publication WHERE fk_utilisateur =
  (SELECT pk_utilisateur FROM utilisateur WHERE loginID = {$_GET['id']} AND fk_publication = NULL);";
  $resultat = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  foreach ($resultat as $pos => $publication) {
    echo $publication['texte'] . "<br>";
  }
?>



<?php require "maPage.php"; ?>
  </body>
</html>
