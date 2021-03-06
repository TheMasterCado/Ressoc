<?php
session_start();
if(isset($_SESSION['id'])) {
  if(isset($_SESSION['newUser']))
    header("Location: ./nouvelUtilisateur.php");
}
else
  header("Location: ./index.php");
require 'bd.php';
require 'php_utils.php';
$id = (isset($_GET['id']) ? $_GET['id'] : "ALL");
//Infos de l'utilisateur propriétaire du feed
$sql = "SELECT prenom, nom, pk_utilisateur, image, fk_specialite, nb_session FROM utilisateur WHERE loginID = :id;";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => (($id == "ALL") ? $_SESSION['id'] : $id)]);
$feedDe = $stmt->fetch();
//Infos de l'utilisateur connecté
$sql = "SELECT prenom, nom, pk_utilisateur FROM utilisateur WHERE loginID = :id;";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $_SESSION['id']]);
$currentUser = $stmt->fetch();
//Toutes les publications
$sql = "SELECT pk_publication, fk_publication, specialite.nom AS nom_specialite, UNIX_TIMESTAMP(timestamp) AS timestamp,
          description, texte, utilisateur.prenom, utilisateur.nom, utilisateur.loginID
        FROM publication
        INNER JOIN type_publication ON fk_type_publication = pk_type_publication
        INNER JOIN utilisateur ON fk_utilisateur = pk_utilisateur
        LEFT JOIN specialite ON publication.fk_specialite = pk_specialite
        WHERE fk_publication IS NULL ".
        (($id == "ALL") ? "" : "AND fk_utilisateur = :pk_utilisateur ").
        (isset($_GET['specialite']) ? "AND specialite.nom LIKE :specialite " : "").
        (isset($_GET['onlyQuestions']) ? "AND (description = 'Question' OR description = 'QuestionRepondue')" : "").
        "ORDER BY timestamp DESC;";
$stmt = $db->prepare($sql);
$params = [];
if($id != "ALL")
  $params += [":pk_utilisateur" => $feedDe['pk_utilisateur']];
if(isset($_GET['specialite']))
  $params += [":specialite" => "%".$_GET['specialite']."%"];
$stmt->execute($params);
$publicationsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$publications = [];
//Points et nb de coms pour les publications
foreach ($publicationsRaw as $i => $row) {
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
  $sql = "SELECT COUNT(*) AS nb FROM publication
          WHERE fk_publication = :pub;";
  $stmt = $db->prepare($sql);
  $stmt->execute([':pub' => $row['pk_publication']]);
  $nbComs = $stmt->fetch();
  $publications[] = [
    'pk_publication' => $row['pk_publication'],
    'texte' => $row['texte'],
    'specialite' => $row['nom_specialite'],
    'description' => $row['description'],
    'timestamp' => $row['timestamp'],
    'prenom' => $row['prenom'],
    'nom' => $row['nom'],
    'loginID' => $row['loginID'],
    'points' => $points,
    'voteCurrentUser' => $voteCurrentUser,
    'nbComs' => $nbComs['nb']
  ];
}
$ordre = isset($_GET['ordre']) ? $_GET['ordre'] : "date";
switch ($ordre) {
  case 'points':
    usort($publications, "compareRowsPoints");
    break;
  case 'hot':
    usort($publications, "compareRowsHotness");
    break;
}
if($id == "ALL")
  $titre = "Feed général";
else
  $titre = "Feed de";
$titre2 = $feedDe['prenom']." ".$feedDe['nom'];
?>
<!DOCTYPE html>
<html>
<head>
<!-- Google Analytics -->
  <script>
    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
    ga('create', 'UA-116236338-1', 'auto');
    ga('send', 'pageview');
  </script>
  <script async src='https://www.google-analytics.com/analytics.js'></script>
<!-- End Google Analytics -->
  <meta charset="utf-8">
  <title><?= $titre." ".$titre2 ?></title>
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
    <div id="filter">
      <div class="stay-right floating-element">
        <label for="ordre">Classer par</label>
        <select name="ordre" class="discreet-dropdown" id="ordre">
          <option value="date" <?= ($ordre == "date") ? "selected" : "" ?>>date</option>
          <option value="points" <?= ($ordre == "points") ? "selected" : "" ?>>points</option>
          <option value="hot" <?= ($ordre == "hot") ? "selected" : "" ?>>popularité</option>
        </select>
        <span class="separateur-vertical"> | </span>
        <input type="text" class="discreet-input" placeholder="Catégorie" id="categorie" value="<?= isset($_GET['specialite']) ? $_GET['specialite'] : "" ?>">
        <span class="separateur-vertical"> | </span>
        <input name="onlyQuestions" type="checkbox" id="onlyQuestions" <?= isset($_GET['onlyQuestions']) ? "checked" : "" ?>>
        <label for="onlyQuestions">Questions seulement</label>
        <span class="separateur-vertical"> | </span>
        <button class="button-link-small btn-link"
                onclick="window.location.replace('./feed.php?id=' + '<?= $id ?>' +
                        '&ordre=' + $('#ordre').val() + (($('#categorie').val().trim() != '')
                         ? '&specialite=' + $('#categorie').val() : '') +
                         ($('#onlyQuestions').is(':checked') ? '&onlyQuestions' : ''));">Appliquer</button>
      </div>
    </div>
    <?php if(empty($publications)) { ?>
    <div id="emptyFeed">
      <h1 class="display-4">Aucune publication</h1>
      <img src="https://media.giphy.com/media/3t7RAFhu75Wwg/giphy.gif" alt="Screaming Dwight">
    </div>
    <?php
    }
    foreach ($publications as $pos => $publication) {
    ?>
    <div class='card <?= ($publication['description'] == 'Question') ? 'border-question' :
                            (($publication['description'] == 'QuestionRepondue') ? 'border-bonneReponse' :'border-texte') ?>'>
      <div class="card-body">
        <h6 class="card-subtitle mb-3 text-muted">
          <strong><?= $publication['points'] ?></strong> points - par <?= $publication['prenom'] . " " . $publication['nom'] . " - " ?>
          <span class="timestamp"><?= time_ago($publication['timestamp']) ?></span>
          <?php if($publication['loginID'] == $_SESSION['id']) { ?>
          <a href="javascript:void(null);" onclick="preparerSuppression(<?= $publication['pk_publication'] ?>)">
            <img src="./Images/glyphicons/png/glyphicons-17-bin.png" class="glyph"
                data-toggle="modal" data-target="#confirmationSuppression">
          </a>
          <?php } ?>
          <span class="stay-right">Catégorie: <strong><?=
            (empty($publication['specialite']) ? "Aucune" : $publication['specialite']).
              (($publication['description'] == 'Question' ||
                $publication['description'] == 'QuestionRepondue') ? " | QUESTION" : "") ?>
            </strong>
          </span>
        </h6>
            <hr>
        <div class="card-text"><?= $publication['texte'] ?></div>
        <hr>
        <span>
          <a href="javascript:void(null);" valeur="<?= ($publication['voteCurrentUser'] == 1) ? "0" : "1" ?>"
             class="card-link vert <?= ($publication['voteCurrentUser'] == 1) ? "selected" : "" ?>"
             onclick="traiterPoints(<?= $publication['pk_publication'] ?>, this)">Bien (+1)</a>
          <a href="javascript:void(null);" valeur="<?= ($publication['voteCurrentUser'] == -1) ? "0" : "-1" ?>"
             class="card-link rouge <?= ($publication['voteCurrentUser'] == -1) ? "selected" : "" ?>"
             onclick="traiterPoints(<?= $publication['pk_publication'] ?>, this)">Mauvais (-1)</a>
        </span>
            <a href="./publication.php?id=<?= $publication['pk_publication'] ?>" class="card-link stay-right">
              Commentaires<?= ($publication['nbComs'] > 0) ? " (" . $publication['nbComs'] . ")" : "" ?>
            </a>
      </div>
    </div>
    <?php } ?>
  </div>

  <?php require "maPage.php"; ?>
</body>
</html>
