<?php
session_start();
require 'bd.php';
if(!empty(trim($_POST['specialite']))) {
  $sql = "SELECT COUNT(*) AS nb FROM specialite WHERE nom = '".$_POST['specialite']."';";
  $resultat = $db->query($sql)->fetch();
  if($resultat['nb'] == 0){
    $sql = "INSERT INTO specialite (specialite.nom) VALUES ('".$_POST['specialite']."');";
    $stmt = $db->prepare($sql);
    $stmt->execute();
  }
  $sql = "SELECT pk_specialite FROM specialite WHERE nom = '".$_POST['specialite']."'";
  $specialite = $db->query($sql)->fetch();
}
$sql = "SELECT pk_type_publication FROM type_publication WHERE description = '".((isset($_POST['estQuestion'])) ? "Question" : "Texte")."'";
$fk_type_publication = $db->query($sql)->fetch();
$sql = "SELECT pk_utilisateur FROM utilisateur WHERE loginID = '".$_SESSION['id']."';";
$fk_utilisateur = $db->query($sql)->fetch();
$sql = "INSERT INTO publication (publication.texte, publication.fk_type_publication,
        publication.fk_utilisateur".((isset($specialite)) ? ", publication.fk_specialite" : "").")
        VALUES ('".$_POST['contenu']."', ".$fk_type_publication['pk_type_publication'].", ".
        $fk_utilisateur['pk_utilisateur'].((isset($specialite)) ? ", ".$specialite['pk_specialite'] : "").");";
$stmt = $db->prepare($sql);
$stmt->execute();
?>
