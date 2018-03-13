<?php
session_start();
require 'bd.php';

function markUp($pattern, $replaceBy, $text, $omit) {
  $newText = "";
  foreach ($omit as $i => $o) {
    $biggerSections = explode($o, $text);
    foreach ($biggerSections as $pos => $section) {
      if($pos % 2 == 0) {
        $sections = explode($pattern, $section);
        foreach ($sections as $pospos => $valeur) {
          if($pospos % 2 == 1)
            $newText .= $replaceBy[0];
          $newText .= $valeur;
          if($pospos % 2 == 1)
            $newText .= $replaceBy[1];
        }
      }
      else {
        $newText .= $section;
      }
    }
  }
  return $newText;
}

function formatEverything($string) {
  $text = markUp("**", ['<strong>', '</strong>'], $string, ["@@"]);
  $text = markUp("//", ['<em>', '</em>'], $text, ["@@"]);
  $text = markUp("~~", ['<del>', '</del>'], $text, ["@@"]);
  $text = markUp("__", ['<ins>', '</ins>'], $text, ["@@"]);
  $text = markUp("^^", ['<sup>', '</sup>'], $text, ["@@"]);
  $text = markUp("##", ['<mark>', '</mark>'], $text, ["@@"]);
  $text = markUp("@@", ['<code>', '</code>'], $text, []);
  return $text;
}

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
