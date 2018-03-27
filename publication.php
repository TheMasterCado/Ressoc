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
require 'php_utils.php';
$id = $_GET['id'];
//Infos de l'utilisateur connecté
$sql = "SELECT prenom, nom, pk_utilisateur FROM utilisateur WHERE loginID = :id;";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $_SESSION['id']]);
$currentUser = $stmt->fetch();
//publication
$sql = "SELECT pk_publication, texte, fk_utilisateur, UNIX_TIMESTAMP(timestamp) AS timestamp, description, specialite.nom AS specialite FROM publication
        INNER JOIN type_publication ON fk_type_publication = pk_type_publication
        LEFT JOIN specialite ON fk_specialite = pk_specialite
        WHERE pk_publication = :id;";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $id]);
$publicationRaw = $stmt->fetch();
$sql = "SELECT * FROM vote
        WHERE fk_publication = :pub;";
$stmt = $db->prepare($sql);
$stmt->execute([':pub' => $publicationRaw['pk_publication']]);
$votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$points = 0;
$voteCurrentUser = 0;
foreach ($votes as $pos => $vote) {
  $points += $vote['valeur'];
  if($vote['fk_utilisateur'] == $currentUser['pk_utilisateur'])
    $voteCurrentUser = $vote['valeur'];
}
$publication = [
  'pk_publication' => $publicationRaw['pk_publication'],
  'texte' => $publicationRaw['texte'],
  'specialite' => $publicationRaw['specialite'],
  'description' => $publicationRaw['description'],
  'timestamp' => $publicationRaw['timestamp'],
  'fk_utilisateur' => $publicationRaw['fk_utilisateur'],
  'points' => $points,
  'voteCurrentUser' => $voteCurrentUser
];
//Tous les commentaires
$sql = "SELECT pk_publication, fk_publication, UNIX_TIMESTAMP(timestamp) AS timestamp, description, texte, prenom, nom, loginID
        FROM publication
        INNER JOIN type_publication ON fk_type_publication = pk_type_publication
        INNER JOIN utilisateur ON fk_utilisateur = pk_utilisateur
        WHERE fk_publication = :id
        ORDER BY timestamp DESC;";
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
    'timestamp' => $row['timestamp'],
    'prenom' => $row['prenom'],
    'nom' => $row['nom'],
    'loginID' => $row['loginID'],
    'points' => $points,
    'voteCurrentUser' => $voteCurrentUser
  ];
}
$ordre = isset($_GET['ordre']) ? $_GET['ordre'] : "date";
switch ($ordre) {
    case 'points':
      usort($commentaires, "compareRowsPoints");
      break;
    case 'hot':
      usort($commentaires, "compareRowsHotness");
      break;
}
$sql = "SELECT COUNT(*) AS nb
        FROM publication
        INNER JOIN type_publication ON fk_type_publication = pk_type_publication
        WHERE fk_publication = :id AND description = 'BonneReponse';";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $_GET['id']]);
$nbBonneReponse = $stmt->fetch();
//Infos sur OP
$sql = "SELECT prenom, nom, loginID, pk_utilisateur, image, fk_specialite, nb_session FROM utilisateur WHERE pk_utilisateur =
        (SELECT fk_utilisateur FROM publication WHERE pk_publication = :id);";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $id]);
$feedDe = $stmt->fetch();
$titre = "Publication de";
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
      ga('send', {
        hitType: 'event',
        eventCategory: 'commentaire',
        eventAction: 'creation'
      });
      $.post("./mc_creerPublication.php", {
        'contenu' : $("#nouveauCom").val(),
        'parent'  : '<?= $publication['pk_publication'] ?>'}, function(data) {
          ga('send', 'pageview', "/publication.php/commentaireCree");
          location.reload(true);
        });
      }
  }

  function traiterBonneReponse(pk_commentaire, description, el){
    $.post("./mc_traiterReponse.php", {
      'pk_commentaire' : pk_commentaire,
      'type_publication' : description,
      'pk_publication'  : <?= $publication['pk_publication'] ?>}, function(data) {
        location.reload(true);
      });
  }
  var toDelete = null;
  var goAway = false;
  function traiterSuppression() {
    if(toDelete != null) {
      $.post("./mc_traiterSuppression.php", {
        'pk_publication' : toDelete}, function(data) {
          if(goAway)
            window.location.replace("./feed.php");
          else
            location.reload(true);
      });
    }
  }

  function preparerSuppression(pk_publication, goaw) {
    toDelete = pk_publication;
    goAway = goaw;
  }
  </script>
  <?php require 'sidenav.php'; ?>
  <div id="main">
    <div id="publication-originale">
      <div class='card <?= ($publication['description'] == 'Question') ? 'border-question' :
                              (($publication['description'] == 'QuestionRepondue') ? 'border-bonneReponse' : 'border-texte') ?>'>
        <div class="card-body">
          <h6 class="card-subtitle mb-3 text-muted">
            <strong><?= $publication['points'] ?></strong> points - par <?= $feedDe['prenom'] . " " . $feedDe['nom'] . " - " ?>
            <span class="timestamp"><?= time_ago($publication['timestamp']) ?></span>
            <?php if($feedDe['loginID'] == $_SESSION['id']) { ?>
            <a href="javascript:void(null);" onclick="preparerSuppression(<?= $publication['pk_publication'] ?>, true)">
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
            <?php if($publication['description'] == 'QuestionRepondue') { ?>
            <a href="#bonne-reponse" class="card-link stay-right">
              Aller à la bonne réponse
            </a>
            <?php } ?>
          </div>
          </div>
        </div>
        <div id="filter">
           <div class="stay-right floating-element">
             <label for="ordre">Classer par</label>
             <select name="ordre" class="discreet-dropdown" id="ordre">
               <option value="date" <?= ($ordre == "date") ? "selected" : "" ?>>date</option>
               <option value="points" <?= ($ordre == "points") ? "selected" : "" ?>>points</option>
               <option value="hot" <?= ($ordre == "hot") ? "selected" : "" ?>>popularité</option>
             </select>
             <span class="separateur-vertical"> | </span>
            <button class="button-link-small btn-link"
                onclick="window.location.replace('./publication.php?id=' + '<?= $id ?>' +
                        '&ordre=' + $('#ordre').val());">Appliquer</button>
          </div>
        </div>
        <div id="commentaires">
      <?php
      foreach ($commentaires as $pos => $commentaire) {
      ?>
      <div class='card<?= ($commentaire['description'] == 'BonneReponse') ? ' border-bonneReponse\' id=\'bonne-reponse' : '' ?>'>
        <div class="card-body">
          <h6 class="card-subtitle mb-3 text-muted">
            <strong><?= $commentaire['points'] ?></strong> points - par <?= $commentaire['prenom'] . " " . $commentaire['nom'] . " - " ?>
            <span class="timestamp"><?= time_ago($commentaire['timestamp']) ?></span>
            <?php if($commentaire['loginID'] == $_SESSION['id']) { ?>
            <a href="javascript:void(null);" onclick="preparerSuppression(<?= $commentaire['pk_publication'] ?>, false)">
              <img src="./Images/glyphicons/png/glyphicons-17-bin.png" class="glyph"
                  data-toggle="modal" data-target="#confirmationSuppression">
            </a>
            <?php } ?>
            <span class="stay-right"><strong><?= (($publication['description'] == 'BonneReponse') ? "Bonne réponse" : "") ?></strong></span>
            <?php
            if(($publication['description'] == 'Question' || $publication['description'] == 'QuestionRepondue') &&
                $currentUser['pk_utilisateur'] == $publication['fk_utilisateur'] && ($nbBonneReponse['nb'] == 0 ||
                $commentaire['description'] == 'BonneReponse')){
              ?>
            <a href="javascript:void(null);" onclick="traiterBonneReponse(<?= $commentaire['pk_publication'] ?>, '<?= $commentaire['description'] ?>')">
              <img src=<?= ($commentaire['description'] == 'BonneReponse') ? "./Images/glyphicons/png/glyphicons-208-remove.png" :
                        "./Images/glyphicons/png/glyphicons-153-check.png" ?> class="glyph">
            </a>
            <?php
          }
             ?>
          </h6>
          <hr>
          <div class="card-text"><?= $commentaire['texte'] ?></div>
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
</body>
</html>
