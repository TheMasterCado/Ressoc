<?php
require 'bd.php';
if(!isset($_POST['commentaire'])){
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
  ':description' => ($_POST['type_publication'] == 'Texte') ? 'QuestionRepondue' : 'Texte'
];
$stmt->execute($params);
 ?>
