<?php
  require 'bd.php';
  $sql = "INSERT INTO utilisateur (utilisateur.nom, utilisateur.prenom, utilisateur.nb_session, utilisateur.loginId, utilisateur.image, utilisateur.email) VALUES ('".$_POST['nom']."', '".$_POST['prenom']."', '".$_POST['nbSessions']."', 1, 'https://us-east-1.tchyn.io/snopes-production/uploads/2017/10/trump_chin_faux_feature.jpg?resize=865%2C452', 'jondubo@dinfo.cegepthetford.ca');";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  header('Location: /index.php');
 ?>
