<?php
session_start();
require 'bd.php';
if(!isset($_POST['pk_publication'])) {
  header("Location: ./index.php");
}
if($_SESSION['id'] == $_POST['id']) {
  $sql = "DELETE FROM publication WHERE pk_publication = :pub;";
  $stmt = $db->prepare($sql);
  $params = [
    ':pub' => $_POST['pk_publication']
  ];
  $stmt->execute($params);
}
 ?>
