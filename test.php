<?php
require 'bd.php';
$sql = "SELECT COUNT(*) AS nb FROM specialite WHERE nom = '"."blabla"."';";
$resultat = $db->query($sql)->fetch();
  echo $resultat['nb'];
?>
