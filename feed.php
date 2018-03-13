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
$sql = "SELECT prenom, nom, pk_utilisateur, image, fk_specialite, nb_session FROM utilisateur WHERE loginID = :id;";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $_GET['id']]);
$feedDe = $stmt->fetch();
//Toutes les publications
$sql = "SELECT * FROM publication WHERE
        fk_utilisateur = :pk_utilisateur AND fk_publication IS NULL
        ORDER BY pk_publication DESC;";
$stmt = $db->prepare($sql);
$stmt->execute([':pk_utilisateur' => $feedDe['pk_utilisateur']]);
$publications = $stmt->fetchAll(PDO::FETCH_ASSOC);
$titre = "Feed de";
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
    <?php
    foreach ($publications as $pos => $publication) {
      $sql = "SELECT * FROM vote WHERE fk_publication = :pk_publication;";
      $stmt = $db->prepare($sql);
      $stmt->execute([':pk_publication' => $publication['pk_publication']]);
      $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
          <strong><?= $points ?></strong> points - par <?= $feedDe['prenom'] . " " . $feedDe['nom'] ?>
          <span class="stay-right">Catégorie: <strong><?php
            if(!empty($publication['fk_specialite'])) {
              $sql = "SELECT nom FROM specialite WHERE pk_specialite = ".$publication['fk_specialite'].";";
              $specialite = $db->query($sql)->fetch();
              echo $specialite['nom'];
            }
            else
              echo "Aucune";
            ?></strong></span>
            </h6>
        <p class="card-text"><?= str_replace("\n", "<br>", $publication['texte']) ?></p>
        <span>
          <a href="javascript:void(null);" valeur="<?= ($voteCurrentUser == 1) ? "0" : "1" ?>" class="card-link vert <?= ($voteCurrentUser == 1) ? "selected" : "" ?>"
             onclick="traiterPoints(<?= $publication['pk_publication'] ?>, this)">Bien (+1)</a>
          <a href="javascript:void(null);" valeur="<?= ($voteCurrentUser == -1) ? "0" : "-1" ?>" class="card-link rouge <?= ($voteCurrentUser == -1) ? "selected" : "" ?>"
             onclick="traiterPoints(<?= $publication['pk_publication'] ?>, this)">Mauvais (-1)</a>
        </span>
            <a href="./publication.php?id=<?= $publication['pk_publication'] ?>" class="card-link stay-right">Commentaires</a>
      </div>
    </div>
    <?php } ?>
  </div>

  <?php require "maPage.php"; ?>
</body>
</html>
