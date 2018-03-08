<?php
session_start();
if(isset($_SESSION['id']) && isset($_GET['id'])) {
  if(isset($_SESSION['newUser']))
  header("Location: ./nouvelUtilisateur.php");
}
else {
  header("Location: ./index.php");
}
require 'bd.php';
$sql = "SELECT prenom, nom, pk_utilisateur FROM utilisateur WHERE loginID = '".$_GET['id']."';";
$feedDe = $db->query($sql)->fetch();
$sql = "SELECT prenom, nom, pk_utilisateur FROM utilisateur WHERE loginID = '".$_SESSION['id']."';";
$currentUser = $db->query($sql)->fetch();
$sql = "SELECT * FROM publication WHERE
fk_utilisateur = ".$feedDe['pk_utilisateur']." AND fk_publication IS NULL;";
$publications = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Feed de <?= $feedDe['prenom']." ".$feedDe['nom'] ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="./CSS/feed.css">
</head>
<body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="./JS/utils.js"></script>
  <script>
  function traiterPoints(valeur, pk_publication) {
    //todo
  }
  </script>
  <div id="sidenav">
    <h6>Feed de <?= $feedDe['prenom']." ".$feedDe['nom'] ?></h6>
    <?php if($_GET['id'] == $_SESSION['id']) { ?>
      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#nouvellePublication">Nouvelle publication</button>
    <?php } ?>
    
    <a class="btn btn-info btn-sm" href="./feed.php?id=<?= $_SESSION['id'] ?>">Mon feed</a>
    <a class="btn btn-info btn-sm" href="./signOut.php">Se déconnecter</a>
    <div class="sidenav-footer">
      <h6>Connecté en tant que <br> <?= $currentUser['prenom']." ".$currentUser['nom'] ?></h6>
      </div>
    </div>
    <div id="main">
      <?php
      foreach ($publications as $pos => $publication) {
        ?>
        <div class='card'>
          <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">
              <?= 0 . " points - par " . $feedDe['prenom'] . " " . $feedDe['nom'] ?>
            </h6>
            <p class="card-text"><?= $publication['texte'] ?></p>
            <a href="#" class="card-link text-success" onclick="traiterPoints(1, <?= $publication['pk_publication'] ?>)">Bien (+1)</a>
            <a href="#" class="card-link text-danger" onclick="traiterPoints(-1, <?= $publication['pk_publication'] ?>)">Mauvais (-1)</a>
            <a href="#" class="card-link">Commentaires</a>
          </div>
        </div>
      <?php } ?>
    </div>

    <?php require "maPage.php"; ?>
  </body>
  </html>
