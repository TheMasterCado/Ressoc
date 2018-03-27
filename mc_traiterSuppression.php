<?php
session_start();
require 'bd.php';
if(!isset($_POST['pk_publication']) || !isset($_SESSION['id']))
  header("Location: ./index.php");
$sql = "SELECT loginID FROM utilisateur
        INNER JOIN publication ON fk_utilisateur = pk_utilisateur
        WHERE pk_publication = :pub;";
$stmt = $db->prepare($sql);
$params = [
  ':pub' => $_POST['pk_publication']
];
$stmt->execute($params);
$author = $stmt->fetch();
if($author['loginID'] != $_SESSION['id'])
  header("Location: ./index.php");
else {
  $sql = "DELETE FROM publication
          INNER JOIN utilisateur ON pk_utilisateur = fk_utilisateur
          WHERE pk_publication = :pub AND loginID = :who;";
  $stmt = $db->prepare($sql);
  $params = [
    ':pub' => $_POST['pk_publication'],
    ':who' => $_SESSION['id']
  ];
  $stmt->execute($params);
}
 ?>
