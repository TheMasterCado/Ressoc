<?php
//Tous les utilisateurs
$sql = "SELECT prenom, nom, loginID FROM utilisateur ORDER BY prenom ASC;";
$allUsers = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
//Infos de l'utilisateur connecté
$sql = "SELECT prenom, nom, pk_utilisateur FROM utilisateur WHERE loginID = '".$_SESSION['id']."';";
$currentUser = $db->query($sql)->fetch();
 ?>
<div id="sidenav">
  <h6><?= $titre ?><br><?= $feedDe['prenom']." ".$feedDe['nom'] ?></h6>
  <img src="<?= $feedDe['image'] ?>">
  <div id="sidenav-buttons">
  <?php if($_GET['id'] == $_SESSION['id']) { ?>
  <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#nouvellePublication">Nouvelle publication</button>
  <?php } ?>

  <a class="btn btn-info btn-sm" href="./feed.php?id=<?= $_SESSION['id'] ?>">Mon feed</a>
  <a class="btn btn-info btn-sm" href="./signOut.php">Se déconnecter</a>
</div>
  <div id="liste-utilisateurs">
    <?php foreach ($allUsers as $pos => $oneUser) { ?>
      <a class="lien-feed-utilisateur" href="./feed.php?id=<?= $oneUser['loginID'] ?>"><?= $oneUser['prenom'] . " " . $oneUser['nom'] ?></a>
    <?php } ?>
  </div>
  <div id="sidenav-footer">
    <h6>Connecté en tant que<br><?= $currentUser['prenom']." ".$currentUser['nom'] ?></h6>
  </div>
</div>
