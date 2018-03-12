<?php
session_start();
require 'bd.php';
if(!empty(trim($_POST['specialite']))) {
  $sql = "SELECT COUNT(*) AS nb FROM specialite WHERE nom = :specialite;";
  $stmt = $db->prepare($sql);
  $resultat = $stmt->execute([':specialite' => $_POST['specialite']])->fetch();
  if($resultat['nb'] == 0){
    $sql = "INSERT INTO specialite (specialite.nom) VALUES (:specialite);";
    $stmt = $db->prepare($sql);
    $stmt->execute([':specialite' => $_POST['specialite']]);
  }
  $sql = "SELECT pk_specialite FROM specialite WHERE nom = :specialite;";
  $stmt = $db->prepare($sql);
  $specialite = $stmt->execute([':specialite' => $_POST['specialite']])->fetch();
}

$sql = "SELECT pk_type_publication FROM type_publication WHERE description = :estQuestion;";
$stmt = $db->prepare($sql);
$fk_type_publication = $stmt->execute([':estQuestion' => ((isset($_POST['estQuestion'])) ? "Question" : "Texte")])->fetch();

$sql = "SELECT pk_utilisateur FROM utilisateur WHERE loginID = :id;";
$stmt = $db->prepare($sql);
$fk_utilisateur = $stmt->execute([':id' => $_SESSION['id']])->fetch();

$sql = "INSERT INTO publication (publication.texte, publication.fk_type_publication,
        publication.fk_utilisateur, publication.fk_specialite, publication.fk_publication)
        VALUES (:contenu, :type_pub, :fk_utilisateur, :fk_specialite, :fk_publication);";
$stmt = $db->prepare($sql);
$params = [
  ':contenu' => $_POST['contenu'],
  ':type_pub' => $fk_type_publication['pk_type_publication'],
  ':fk_utilisateur' => $fk_utilisateur['pk_utilisateur'],
  ':fk_specialite' => ((isset($specialite)) ? $specialite['pk_specialite'] : NULL),
  ':fk_publication' => ((isset($_POST['parent'])) ? $_POST['parent'] : NULL)
];
$stmt->execute($params);
?>
