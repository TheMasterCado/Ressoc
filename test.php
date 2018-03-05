<?php
require 'bd.php';
$sql = "SELECT * FROM specialite;";
$resultat = $db->query($sql)->fetchAll();
  var_dump($resultat);
?>
