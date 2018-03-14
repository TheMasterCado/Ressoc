<?php
require 'bd.php';
if(!empty(trim($_POST['specialite']))) {
  $sql = "SELECT COUNT(*) AS nb FROM specialite WHERE nom = :specialite;";
  $stmt = $db->prepare($sql);
  $stmt->execute([':specialite' => $_POST['specialite']]);
  $resultat = $stmt->fetch();
  if($resultat['nb'] == 0){
    $sql = "INSERT INTO specialite (specialite.nom) VALUES (:specialite);";
    $stmt = $db->prepare($sql);
    $stmt->execute([':specialite' => $_POST['specialite']]);
  }
  $sql = "SELECT pk_specialite FROM specialite WHERE nom = :specialite;";
  $stmt = $db->prepare($sql);
  $stmt->execute([':specialite' => $_POST['specialite']]);
  $specialite = $stmt->fetch();
}
$sql = "INSERT INTO utilisateur (utilisateur.nom, utilisateur.prenom, utilisateur.nb_session,
        utilisateur.loginID, utilisateur.image, utilisateur.email, publication.fk_specialite)
        VALUES (:nom, :prenom, :nbSessions, :id, :image, :email, :fk_specialite);";
$stmt = $db->prepare($sql);
$params = [
  ':prenom' => $_POST['prenom'],
  ':nom' => $_POST['nom'],
  ':nbSessions' => $_POST['nbSessions'],
  ':id' => $_POST['id'],
  ':image' => $_POST['image'],
  ':email' => $_POST['email'],
  ':fk_specialite' => (isset($specialite) ? $specialite['pk_specialite'] : NULL)
];
$stmt->execute($params);
session_start();
unset($_SESSION['newUser']);
header("Location: ./feed.php?id={$_SESSION['id']}");
?>
