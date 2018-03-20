<?php
session_start();
require 'bd.php';
if(!isset($_POST['pk_publication']) || $_SESSION['id'] != $_POST['id']){
  header("Location: ./index.php");
}
$sql = "DELETE FROM publication WHERE pk_publication = :pub;";
$stmt = $db->prepare($sql);
$params = [
  ':pub' => $_POST['pk_publication']
];
$stmt->execute($params);
 ?>
