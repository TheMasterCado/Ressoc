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
  <link rel="stylesheet" href="./CSS/feed.css">
</head>
<body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="./JS/utils.js"></script>
  <script>
    function signOut() {
      window.location.replace("./index.php?signOut");
    }
  </script>
  <div id="sidenav">
    <?php if($_GET['id'] == $_SESSION['id']) { ?>
      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#nouvellePublication">Nouvelle publication</button>
    <?php } ?>
    <button type="button" class="btn btn-info btn-sm" onclick="signOut()">Se déconnecter</button>
  </div>
  <div id="main">


    <?php
    $sql = "SELECT * FROM publication WHERE fk_utilisateur =
    (SELECT pk_utilisateur FROM utilisateur WHERE loginID = {$_GET['id']} AND fk_publication IS NULL);";
    $resultat = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    foreach ($resultat as $pos => $publication) {
      echo $publication['texte'] . "<br>";
    }
    ?>
  </div>

  <?php require "maPage.php"; ?>
</body>
</html>
