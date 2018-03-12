<?php
  require('bd.php');
  session_start();
  $id = $_POST['id'];
  $_SESSION['id'] = $id;
  $sql = "SELECT count(*) AS nb FROM utilisateur WHERE loginID = :id;";
  $stmt = $db->prepare($sql);
  $resultat = $stmt->execute([':id' => $id])->fetch();
  if($resultat['nb'] == 0) {
    $_SESSION['newUser'] = $_POST;
    echo "NEW";
  }
  else {
    echo "EXISTING";
  }
 ?>
