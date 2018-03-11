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
//publication
$sql = "SELECT * FROM publication WHERE pk_publication = ".$_GET['id'].";";
$publication = $db->query($sql)->fetch();
//Tous les commentaires
$sql = "SELECT * FROM publication WHERE
        fk_publication = ".$_GET['id'].";";
$commentaires = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
//Infos sur OP
$sql = "SELECT prenom, nom, pk_utilisateur FROM utilisateur WHERE pk_utilisateur =
        (SELECT fk_utilisateur FROM publication WHERE pk_publication = ".$_GET['id'].");";
$feedDe = $db->query($sql)->fetch();
$titre = "Publication de";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $titre." ".$feedDe['prenom']." ".$feedDe['nom'] ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="./CSS/feed.css">
  <link rel="stylesheet" href="./CSS/sidenav.css">
</head>
<body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script>
  function traiterPoints(pk_publication, el) {
    var valeur = $(el).attr("valeur");
    $.post("./mc_traiterVotes.php", {
      pub: pk_publication,
      val: valeur
    }, function(data) {
        var points = parseInt($(el).parent().siblings(".card-subtitle").children("strong").text(), 10);
        if(valeur != 0)
          var diff = valeur;
        else
          var diff = $(el).hasClass("vert") ? -1 : 1;
        diff *= ($(el).siblings(".selected").length == 0) ? 1 : 2;
        $(el).parent().siblings(".card-subtitle").children("strong").text(points + diff);
        deselectionner($(el).siblings(".card-link").eq(0));
        if($(el).hasClass("selected"))
          deselectionner(el);
        else
          selectionner(el);
      });
  }

  function deselectionner(el) {
    $(el).toggleClass("selected", false);
    $(el).attr("valeur", $(el).hasClass("vert") ? 1 : -1);
  }

  function selectionner(el) {
    $(el).toggleClass("selected", true);
    $(el).attr("valeur", 0);
  }

  </script>
  <?php require 'sidenav.php'; ?>
  <div id="main">
    <div class='card'>
      <div class="card-body">
        <h6 class="card-subtitle mb-3 text-muted">
          <?php
          $sql = "SELECT * FROM vote WHERE fk_publication = ".$_GET['id'].";";
          $votesPub = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
          $pointsPub = 0;
          $voteCurrentUserPub = 0;
          foreach ($votesPub as $pos => $vote) {
            $pointsPub += $vote['valeur'];
            if($vote['fk_utilisateur'] == $currentUser['pk_utilisateur'])
              $voteCurrentUserPub = $vote['valeur'];
          }
           ?>
          <strong><?= $pointsPub ?></strong> points - par <?= $feedDe['prenom'] . " " . $feedDe['nom'] ?>
        </h6>
        <p class="card-text"><?= str_replace("\n", "<br>", $publication['texte']) ?></p>
        <span>
          <a href="javascript:void(null);" valeur="<?= ($voteCurrentUserPub == 1) ? "0" : "1" ?>" class="card-link vert <?= ($voteCurrentUserPub == 1) ? "selected" : "" ?>"
             onclick="traiterPoints(<?= $publication['pk_publication'] ?>, this)">Bien (+1)</a>
          <a href="javascript:void(null);" valeur="<?= ($voteCurrentUserPub == -1) ? "0" : "-1" ?>" class="card-link rouge <?= ($voteCurrentUserPub == -1) ? "selected" : "" ?>"
             onclick="traiterPoints(<?= $publication['pk_publication'] ?>, this)">Mauvais (-1)</a>
        </span>
      </div>
    </div>
    <div id="commentaires">
      <?php
      foreach ($commentaires as $pos => $commentaire) {
        $sql = "SELECT prenom, nom FROM utilisateur WHERE pk_utilisateur = ".$commentaire['fk_utilisateur'].";";
        $auteur = $db->query($sql)->fetch();
        $sql = "SELECT * FROM vote WHERE fk_publication = ".$commentaire['pk_publication'].";";
        $votes = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $points = 0;
        $voteCurrentUser = 0;
        foreach ($votes as $pos => $vote) {
          $points += $vote['valeur'];
          if($vote['fk_utilisateur'] == $currentUser['pk_utilisateur'])
            $voteCurrentUser = $vote['valeur'];
        }
      ?>
      <div class='card'>
        <div class="card-body">
          <h6 class="card-subtitle mb-3 text-muted">
            <strong><?= $points ?></strong> points - par <?= $auteur['prenom'] . " " . $auteur['nom'] ?>
          </h6>
          <p class="card-text"><?= str_replace("\n", "<br>", $commentaire['texte']) ?></p>
          <span>
            <a href="javascript:void(null);" valeur="<?= ($voteCurrentUser == 1) ? "0" : "1" ?>" class="card-link vert <?= ($voteCurrentUser == 1) ? "selected" : "" ?>"
               onclick="traiterPoints(<?= $commentaire['pk_publication'] ?>, this)">Bien (+1)</a>
            <a href="javascript:void(null);" valeur="<?= ($voteCurrentUser == -1) ? "0" : "-1" ?>" class="card-link rouge <?= ($voteCurrentUser == -1) ? "selected" : "" ?>"
               onclick="traiterPoints(<?= $commentaire['pk_publication'] ?>, this)">Mauvais (-1)</a>
          </span>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>

  <?php require "maPage.php"; ?>
</body>
</html>
