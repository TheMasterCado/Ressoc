<?php
session_start();
if(isset($_SESSION['id'])) {
  if(!isset($_GET['id']))
    header("Location: ./feed.php");
  if(isset($_SESSION['newUser']))
    header("Location: ./nouvelUtilisateur.php");
}
else
  header("Location: ./index.php");
require 'bd.php';
$id = $_GET['id'];
//publication
$sql = "SELECT pk_publication, texte, description, specialite.nom AS specialite FROM publication
        INNER JOIN type_publication ON fk_type_publication = pk_type_publication
        LEFT JOIN specialite ON fk_specialite = fk_specialite
        WHERE pk_publication = :id;";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $id]);
$publicationRaw = $stmt->fetch();
$sql = "SELECT * FROM vote
        WHERE fk_publication = :pub;";
$stmt = $db->prepare($sql);
$stmt->execute([':pub' => $publicationRaw['pk_publication']]);
$votes = $stmt->fetch();
$points = 0;
$voteCurrentUser = 0;
foreach ($votes as $pos => $vote) {
  $points += $vote['valeur'];
  if($vote['fk_utilisateur'] == $currentUser['pk_utilisateur'])
    $voteCurrentUser = $vote['valeur'];
}
$publication = [];
$publication = [
  'pk_publication' => $publicationRaw['pk_publication'],
  'texte' => $publicationRaw['texte'],
  'specialite' => $publicationRaw['specialite'],
  'description' => $publicationRaw['description'],
  'points' => $points,
  'voteCurrentUser' => $voteCurrentUser
];
//Tous les commentaires
$sql = "SELECT pk_publication, fk_publication, description, texte, prenom, nom FROM publication
        INNER JOIN type_publication ON fk_type_publication = pk_type_publication
        INNER JOIN utilisateur ON fk_utilisateur = pk_utilisateur
        WHERE fk_publication = :id
        ORDER BY pk_publication DESC;";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $_GET['id']]);
$commentairesRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$commentaires = [];
//Points des Commentaires
foreach ($commentairesRaw as $i => $row) {
  $sql = "SELECT * FROM vote
          WHERE fk_publication = :pub;";
  $stmt = $db->prepare($sql);
  $stmt->execute([':pub' => $row['pk_publication']]);
  $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $points = 0;
  $voteCurrentUser = 0;
  foreach ($votes as $pos => $vote) {
    $points += $vote['valeur'];
    if($vote['fk_utilisateur'] == $currentUser['pk_utilisateur'])
      $voteCurrentUser = $vote['valeur'];
  }
  $commentaires[] = [
    'pk_publication' => $row['pk_publication'],
    'texte' => $row['texte'],
    'description' => $row['description'],
    'prenom' => $row['prenom'],
    'nom' => $row['nom'],
    'points' => $points,
    'voteCurrentUser' => $voteCurrentUser
  ];
}
if(isset($_GET['ordre']))
  switch ($_GET['ordre']) {
    case 'points':
      usort($publications, "compareRowsPoints");
      break;
  }
//Infos sur OP
$sql = "SELECT prenom, nom, pk_utilisateur, image, fk_specialite, nb_session FROM utilisateur WHERE pk_utilisateur =
        (SELECT fk_utilisateur FROM publication WHERE pk_publication = :id);";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $_GET['id']]);
$feedDe = $stmt->fetch();
$titre = "Publication de";
$titre2 = $feedDe['prenom']." ".$feedDe['nom'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $titre." ".$titre2 ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="./CSS/feed.css">
  <link rel="stylesheet" href="./CSS/sidenav.css">
  <link rel="stylesheet" href="./CSS/publication.css">
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

  function traiterNouveauCom() {
    if ($("#nouveauCom").val().trim().length == 0) {
      alert("Un commentaire ne doit pas être vide");
    }
    else {
      $.post("./mc_creerPublication.php", {
        'contenu' : $("#nouveauCom").val(),
        'parent'  : <?= $publication['pk_publication'] ?>}, function(data) {
          location.reload(true);
        });
      }
  }
  </script>
  <?php require 'sidenav.php'; ?>
  <div id="main">
    <div id="publication-originale">
      <div class='card <?= ($publication['description'] == 'Question') ? 'border-question' : 'border-texte' ?>'>
        <div class="card-body">
          <h6 class="card-subtitle mb-3 text-muted">
            <strong><?= $publication['points'] ?></strong> points - par <?= $feedDe['prenom'] . " " . $feedDe['nom'] ?>
            <span class="stay-right">Catégorie: <strong><?php
              (empty($publication['specialite']) ? "Aucune" : $publication['specialite']).
                (($publication['description'] == 'Question') ? " | QUESTION" : "") ?></strong>
            </span>
          </h6>
          <hr>
          <p class="card-text"><?= str_replace("\n", "<br>", $publication['texte']) ?></p>
          <hr>
          <span>
            <a href="javascript:void(null);" valeur="<?= ($publication['voteCurrentUser'] == 1) ? "0" : "1" ?>"
               class="card-link vert <?= ($publication['voteCurrentUser'] == 1) ? "selected" : "" ?>"
               onclick="traiterPoints(<?= $publication['pk_publication'] ?>, this)">Bien (+1)</a>
              <a href="javascript:void(null);" valeur="<?= ($publication['voteCurrentUser'] == -1) ? "0" : "-1" ?>"
                 class="card-link rouge <?= ($publication['voteCurrentUser'] == -1) ? "selected" : "" ?>"
                 onclick="traiterPoints(<?= $publication['pk_publication'] ?>, this)">Mauvais (-1)</a>
              </span>
            </div>
          </div>
        </div>
    <div id="commentaires">
      <?php
      foreach ($commentaires as $pos => $commentaire) {
      ?>
      <div class='card<?= ($commentaire['description'] == 'BonneReponse') ? ' border-bonneReponse' : '' ?>'>
        <div class="card-body">
          <h6 class="card-subtitle mb-3 text-muted">
            <strong><?= $commentaire['points'] ?></strong> points - par <?= $commentaire['prenom'] . " " . $commentaire['nom'] ?>
          </h6>
          <hr>
          <p class="card-text"><?= $commentaire['texte'] ?></p>
          <hr>
          <span>
            <a href="javascript:void(null);" valeur="<?= ($commentaire['voteCurrentUser'] == 1) ? "0" : "1" ?>"
               class="card-link vert <?= ($commentaire['voteCurrentUser'] == 1) ? "selected" : "" ?>"
               onclick="traiterPoints(<?= $commentaire['pk_publication'] ?>, this)">Bien (+1)</a>
            <a href="javascript:void(null);" valeur="<?= ($commentaire['voteCurrentUser'] == -1) ? "0" : "-1" ?>"
               class="card-link rouge <?= ($commentaire['voteCurrentUser'] == -1) ? "selected" : "" ?>"
               onclick="traiterPoints(<?= $commentaire['pk_publication'] ?>, this)">Mauvais (-1)</a>
          </span>
        </div>
      </div>
      <?php } ?>
      <div class='card'>
        <div class="card-body">
          <textarea id="nouveauCom" rows="3" class="card-text form-control" placeholder="Entrez votre commentaire."></textarea>
          <hr>
          <a href="javascript:void(null);" class="card-link stay-right" onclick="traiterNouveauCom()">Répondre</a>
        </div>
      </div>
    </div>
  </div>

  <?php require "maPage.php"; ?>
</body>
</html>
