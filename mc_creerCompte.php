<?php
require 'bd.php';
session_start();
if(!isset($_SESSION['id'])) {
  header("Location: ./index.php");
}
if(!empty(trim($_POST['specialite']))) {
  $sql = "SELECT COUNT(*) AS nb FROM specialite WHERE nom = :specialite;";
  $stmt = $db->prepare($sql);
  $stmt->execute([':specialite' => htmlspecialchars($_POST['specialite'], ENT_COMPAT)]);
  $resultat = $stmt->fetch();
  if($resultat['nb'] == 0){
    $sql = "INSERT INTO specialite (specialite.nom) VALUES (:specialite);";
    $stmt = $db->prepare($sql);
    $stmt->execute([':specialite' => htmlspecialchars($_POST['specialite'], ENT_COMPAT)]);
  }
  $sql = "SELECT pk_specialite FROM specialite WHERE nom = :specialite;";
  $stmt = $db->prepare($sql);
  $stmt->execute([':specialite' => htmlspecialchars($_POST['specialite'], ENT_COMPAT)]);
  $specialite = $stmt->fetch();
}
$sql = "INSERT INTO utilisateur (utilisateur.nom, utilisateur.prenom, utilisateur.nb_session,
        utilisateur.loginID, utilisateur.image, utilisateur.email, utilisateur.fk_specialite)
        VALUES (:nom, :prenom, :nbSessions, :id, :image, :email, :fk_specialite);";
$stmt = $db->prepare($sql);
$params = [
  ':prenom' => htmlspecialchars($_POST['prenom'], ENT_COMPAT),
  ':nom' => htmlspecialchars($_POST['nom'], ENT_COMPAT),
  ':nbSessions' => $_POST['nbSessions'],
  ':id' => $_POST['id'],
  ':image' => $_POST['image'],
  ':email' => $_POST['email'],
  ':fk_specialite' => (isset($specialite) ? $specialite['pk_specialite'] : NULL)
];
$stmt->execute($params);
unset($_SESSION['newUser']);
header("Location: ./feed.php");
?>
