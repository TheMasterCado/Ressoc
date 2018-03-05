<?php
require 'bd.php';
$sql = "SELECT * FROM utilisateur;";
$resultat = $db->query($sql)->fetchAll();
foreach ($resultat as $value) {
  echo $value['email'];
}
?>
