<?php
  require('bd.php');
  $id = intval($_GET['id']);
  $sql = "SELECT count(*) FROM utilisateur WHERE loginID={$id};";
  $resultats = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  if($resultats[0][0] == 0)
    header('Location: /nouvelUtilisateur.php');
  else
    header("Location: /feed.php?id={$_GET['id']}");
 ?>
