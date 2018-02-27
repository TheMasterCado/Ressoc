<?php
session_start();
if(isset($_SESSION['id'])) {
  if(!$_SESSION['newUser'])
    header("Location: /feed.php?id=$_SESSION['id']");
  }
  else
    header("Location: /index.php");
 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Nouvel utilisateur</title>
    <link rel="stylesheet" type="text/css" href="nouvelUtilisateur.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  </head>
  <body>
    <h1>Créer un compte</h1>
    <form class="" action="/mc_creerCompte.php" method="post">
      <label for="prenom">Prénom</label>
      <input type="text" name="prenom">
      <br>
      <label for="nom">Nom</label>
      <input type="text" name="nom">
      <br>
      <label for="nbSessions">Nombre de sessions en informatique</label>
      <input type="number" name="nbSessions" min="1" max="6">
      <br>
      <input type="submit" value="Valider">
    </form>
  </body>
</html>
