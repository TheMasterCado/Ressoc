<?php
  require('bd.php');
  session_start();
  $id = intval($_GET['id']);
  $_SESSION['id'] = $id;
  $sql = "SELECT count(*) AS nb FROM utilisateur WHERE loginID={$id};";
  $resultats = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  if($_SESSION['newUser'] = ($resultats[0]['nb'] == 0)) {
    header('Location: /nouvelUtilisateur.php');
  }
  else {
    header("Location: /feed.php?id={$_GET['id']}");
  }
 ?>
