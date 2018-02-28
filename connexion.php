<?php
  require('bd.php');
  session_start();
  $id = intval($_GET['id']);
  $_SESSION['id'] = $id;
  $sql = "SELECT count(*) AS nb FROM utilisateur WHERE loginID={$id};";
  $resultat = $db->query($sql)->fetch();
  $_SESSION['newUser'] = $resultat['nb'] == 0;
  if($_SESSION['newUser']) {
    header("Location: ./nouvelUtilisateur.php");
    echo $_SESSION['newUser'];
  }
  else {
    header("Location: ./feed.php?id={$_GET['id']}");
  }
 ?>
