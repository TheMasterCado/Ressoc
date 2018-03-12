<?php
session_start();
require 'bd.php';
$sql = "SELECT pk_utilisateur FROM utilisateur WHERE loginID = :id;";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $_SESSION['id']]);
$utilisateur = $stmt->fetch();
$sql = "SELECT COUNT(*) AS nb FROM vote WHERE fk_publication = :pub AND
        fk_utilisateur = :pk_utilisateur;";
$stmt = $db->prepare($sql);
$stmt->execute([':pub' => $_POST['pub'], ':pk_utilisateur' => $utilisateur['pk_utilisateur']]);
$resultat = $stmt->fetch();
if($resultat['nb'] == 0)
  $sql = "INSERT INTO vote (vote.fk_publication, vote.fk_utilisateur, vote.valeur)
          VALUES (:pub, :pk_utilisateur, :val;";
else
  $sql = "UPDATE vote SET valeur = :val WHERE
          fk_publication = :pub AND fk_utilisateur = :pk_specialite;";
$stmt = $db->prepare($sql);
$params = [
  ':val' => $_POST['val'],
  ':pub' => $_POST['pub'],
  ':pk_utilisateur' => $utilisateur['pk_utilisateur']
];
$stmt->execute();
?>
