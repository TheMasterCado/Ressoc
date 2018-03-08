<?php
session_start();
require 'bd.php';
$sql = "SELECT pk_utilisateur FROM utilisateur WHERE loginID = '".$_SESSION['id']."';";
$utilisateur = $db->query($sql)->fetch();
$sql = "SELECT COUNT(*) AS nb FROM vote WHERE fk_publication = ".$_POST['pub']." AND
        fk_utilisateur = '".$utilisateur['pk_utilisateur']."';";
$resultat = $db->query($sql)->fetch();
if($resultat['nb'] == 0)
  $sql = "INSERT INTO vote (vote.fk_publication, vote.fk_utilisateur, vote.valeur)
          VALUES (".$_POST['pub'].", ".$utilisateur['pk_utilisateur'].", ".$_POST['val'].");";
else
  $sql = "UPDATE vote SET valeur = ".$_POST['val']." WHERE
          fk_publication = ".$_POST['pub']." AND fk_utilisateur = ".$utilisateur['pk_utilisateur'].";";
$stmt = $db->prepare($sql);
$stmt->execute();
?>
