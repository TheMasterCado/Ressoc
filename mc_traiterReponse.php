<?php
require 'bd.php';
if(!isset($_POST['commentaire'])){
  header("Location: ./index.php");
}
$sql = "UPDATE publication SET fk_type_publication = (SELECT pk_type_publication from type_publication WHERE description = 'BonneReponse') WHERE pk_publication = :pub;";
$stmt = $db->prepare($sql);
$params = [
  ':pub' => $_POST['commentaire']
];
$stmt->execute($params);

 ?>
