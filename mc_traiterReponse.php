<?php
require 'bd.php';
session_start();
if(!isset($_POST['commentaire']) || !isset($_SESSION['id'])){
  header("Location: ./index.php");
}
$sql = "UPDATE publication SET fk_type_publication = (SELECT pk_type_publication from type_publication WHERE description = :description) WHERE pk_publication = :pub;";
$stmt = $db->prepare($sql);
$params = [
  ':pub' => $_POST['pk_commentaire'],
  ':description' => ($_POST['type_publication'] == 'Texte') ? 'BonneReponse' : 'Texte'
];
$stmt->execute($params);

$sql = "UPDATE publication SET fk_type_publication = (SELECT pk_type_publication from type_publication WHERE description = :description) WHERE pk_publication = :pub;";
$stmt = $db->prepare($sql);
$params = [
  ':pub' => $_POST['pk_publication'],
  ':description' => ($_POST['type_publication'] == 'Texte') ? 'QuestionRepondue' : 'Question'
];
$stmt->execute($params);
 ?>
