<?php
  require 'bd.php';
  $sql = "INSERT INTO utilisateur (utilisateur.nom, utilisateur.prenom, utilisateur.nb_session, utilisateur.loginId, utilisateur.image, utilisateur.email)
          VALUES ('".$_POST['nom']."', '".$_POST['prenom']."', '".$_POST['nbSessions']."', '".$_POST['id']."', '".$_POST['image']."', '".$_POST['email']."');";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  session_start();
  unset($_SESSION['newUser']);
  header('Location: ./feed');
 ?>
