<?php
  require('bd.php');
  session_start();
  $id = $_POST['id'];
  $_SESSION['id'] = $id;
  $sql = "SELECT count(*) AS nb FROM utilisateur WHERE loginID='{$id}';";
  $resultat = $db->query($sql)->fetch();
  if($resultat['nb'] == 0) {
    $_SESSION['newUser'] = $_POST;
    echo "NEW";
  }
  else {
    echo "EXISTING";
  }
 ?>
