<?php
session_start();
require 'bd.php';
if(!isset($_POST['pk_publication'])) {
  header("Location: ./index.php");
}
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
fwrite($myfile, $_SESSION['id']."\n");
fwrite($myfile, $_POST['id']."\n");
fwrite($myfile, ($_SESSION['id'] == $_POST['id']));
fclose($myfile);
if($_SESSION['id'] == $_POST['id']) {
  $sql = "DELETE FROM publication WHERE pk_publication = :pub;";
  $stmt = $db->prepare($sql);
  $params = [
    ':pub' => $_POST['pk_publication']
  ];
  $stmt->execute($params);
}
 ?>
