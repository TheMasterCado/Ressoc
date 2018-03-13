<?php
  $dbName = 'alecado';
  $username = 'alecado';
  $password = 'cado';
  try
  {
    $db = new PDO('mysql:host=206.167.23.182;dbname='. $dbName, $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOexception $e)
  {
    echo 'Erreur SQL : ' . $e->getMessage() . '<br />';
  }
?>
