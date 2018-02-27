<?php
  require 'bd.php';
  $sql = "INSERT INTO utilisateur (utilisateur.nom, utilisateur.prenom, utilisateur.nbSessions, utilisateur.loginId) VALUES ('".$_POST['nom']."', '".$_POST['prenom']."', '"$_POST['nbSessions']"', 1);";
        $stmt = $db->prepare($sql);
        $stmt->execute();

 ?>
