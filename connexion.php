<?php
  require('bd.php');
  session_start();
  $id = intval($_GET['id']);
  $_SESSION['id'] = $id;
  $sql = "SELECT count(*) AS nb FROM utilisateur WHERE loginID={$id};";
  $resultat = $db->query($sql)->fetch();
  if($resultat['nb'] == 0) {
    $_SESSION['newUser'] = "Yup";
    header("Location: ./nouvelUtilisateur.php");
  }
  else {
    header("Location: ./feed.php?id={$_GET['id']}");
  }
 ?>
