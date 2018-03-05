<?php
require 'bd.php';
$sql = "SELECT COUNT(*) AS nb FROM specialite WHERE nom = '".$_POST['specialite']."';";
$resultat = $db->query($sql)->fetch();
if($resultat['nb'] == 0){
  $sql = "INSERT INTO specialite (specialite.nom) VALUES ('".$_POST['specialite']."');";
  $stmt = $db->prepare($sql);
  $stmt->execute();
}
$sql = "SELECT pk_specialite FROM specialite WHERE nom = '".$_POST['specialite']."'";
$specialite = $db->query($sql)->fetch();
$sql = "INSERT INTO utilisateur (utilisateur.nom, utilisateur.prenom, utilisateur.nb_session, utilisateur.loginId, utilisateur.image, utilisateur.email, utilisateur.specialite)
        VALUES ('".$_POST['nom']."', '".$_POST['prenom']."', ".$_POST['nbSessions'].", ".$_POST['id'].", '".$_POST['image']."', '".$_POST['email']."', '".$specialite['pk_specialite']."');";
 $stmt = $db->prepare($sql);
 $stmt->execute();
 session_start();
 unset($_SESSION['newUser']);
 header("Location: ./feed.php?id={$_SESSION['id']}");
?>
