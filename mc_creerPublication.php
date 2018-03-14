<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'bd.php';

require 'php_utils.php';

$formattedText = formatEverything($_POST['contenu']);

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

$sql = "SELECT pk_type_publication FROM type_publication WHERE description = :estQuestion;";
$stmt = $db->prepare($sql);
$stmt->execute([':estQuestion' => ((isset($_POST['estQuestion'])) ? "Question" : "Texte")]);
$fk_type_publication = $stmt->fetch();

$sql = "SELECT pk_utilisateur FROM utilisateur WHERE loginID = :id;";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $_SESSION['id']]);
$fk_utilisateur = $stmt->fetch();

$sql = "INSERT INTO publication (publication.texte, publication.fk_type_publication,
        publication.fk_utilisateur, publication.fk_specialite, publication.fk_publication)
        VALUES (:contenu, :type_pub, :fk_utilisateur, :fk_specialite, :fk_publication);";
$stmt = $db->prepare($sql);
$params = [
  ':contenu' => $formattedText,
  ':type_pub' => $fk_type_publication['pk_type_publication'],
  ':fk_utilisateur' => $fk_utilisateur['pk_utilisateur'],
  ':fk_specialite' => ((isset($specialite)) ? $specialite['pk_specialite'] : NULL),
  ':fk_publication' => ((isset($_POST['parent'])) ? $_POST['parent'] : NULL)
];
$stmt->execute($params);
?>
