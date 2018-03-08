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
//Infos de l'utilisateur propriétaire du feed
$sql = "SELECT prenom, nom, pk_utilisateur FROM utilisateur WHERE loginID = '".$_GET['id']."';";
$feedDe = $db->query($sql)->fetch();
//Infos de l'utilisateur connecté
$sql = "SELECT prenom, nom, pk_utilisateur FROM utilisateur WHERE loginID = '".$_SESSION['id']."';";
$currentUser = $db->query($sql)->fetch();
//Toutes les publications avec les votes associés
$sql = "SELECT * FROM publication WHERE
        fk_utilisateur = ".$feedDe['pk_utilisateur']." AND fk_publication IS NULL;";
$publications = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
foreach ($publication as $pos => $publication) {
  $sql = "SELECT * FROM vote WHERE fk_publication = ".$publication['pk_publication'].";";
  $votes = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  $publication['points'] = 0;
  $publication['voteCurrentUser'] = 0;
  foreach ($votes as $pos => $vote) {
    $publication['points'] += $vote['valeur'];
    if($vote['fk_utilisateur'] == $currentUser['pk_utilisateur'])
      $publication['voteCurrentUser'] = $vote['valeur'];
  }
}
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
  function traiterPoints(valeur, pk_publication, el) {
    $.post("./mc_traitervotes.php", {
      pub: pk_publication,
      val: valeur
    }, function(data) {
        $(el).toggleClass("selected");
        $(el).siblings(".card-link").toggleClass("selected");
        var points = parseInt($(el).parent().siblings(".card-subtitle").children("span").text(), 10);
        if(valeur != 0)
          points += valeur;
        else
          if($(el).text() == "Bien (+1)")
            points -= 1;
          else
            points += 1;
        $(el).parent().siblings(".card-subtitle").children("span").text(points);
      }
    });
  }
  </script>
  <div id="sidenav">
    <h6>Feed de <?= $feedDe['prenom']." ".$feedDe['nom'] ?></h6>
    <?php if($_GET['id'] == $_SESSION['id']) { ?>
      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#nouvellePublication">Nouvelle publication</button>
    <?php } ?>

    <a class="btn btn-info btn-sm" href="./feed.php?id=<?= $_SESSION['id'] ?>">Mon feed</a>
    <a class="btn btn-info btn-sm" href="./signOut.php">Se déconnecter</a>
    <div id="sidenav-footer">
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
            <span><?= $publication['points'] ?></span>points - par <?= $feedDe['prenom'] . " " . $feedDe['nom'] ?>
          </h6>
          <p class="card-text"><?= $publication['texte'] ?></p>
          <span class="lien-vote">
            <a href="#" class="card-link <?= ($publication['voteCurrentUser'] == 1) ? "selected" : "text-success" ?>"
               onclick="traiterPoints(<?= ($publication['voteCurrentUser'] == 1) ? "0" : "1" ?>,
                        <?= $publication['pk_publication'] ?>, this)">Bien (+1)</a>
            <a href="#" class="card-link <?= ($publication['voteCurrentUser'] == -1) ? "selected" : "text-danger" ?>"
               onclick="traiterPoints(<?= ($publication['voteCurrentUser'] == -1) ? "0" : "-1" ?>,
                        <?= $publication['pk_publication'] ?>, this)">Mauvais (-1)</a>
          </span>
          <a href="#" class="card-link">Commentaires</a>
        </div>
      </div>
    <?php } ?>
  </div>

  <?php require "maPage.php"; ?>
</body>
</html>
