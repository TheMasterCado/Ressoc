<?php
require 'bd.php';
// if(!isset($_POST['pk_publication']) || $_SESSION['id'] != $_POST['id']){
//   header("Location: ./index.php");
// }
echo $_SESSION['id'];
echo $_POST['id'];
$sql = "DELETE FROM publication WHERE pk_publication = :pub;";
$stmt = $db->prepare($sql);
$params = [
  ':pub' => $_POST['pk_publication']
];
$stmt->execute($params);
 ?>
