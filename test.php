<?php
require 'bd.php';
$sql = "SELECT * FROM specialite;";
while($resultat = $db->query($sql)->fetch())
  var_dump($resultat);
?>
