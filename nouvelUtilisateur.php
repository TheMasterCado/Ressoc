<?php
session_start();
if(isset($_SESSION['id'])) {
  if(!isset($_SESSION['newUser']))
  header("Location: ./feed.php?id={$_SESSION['id']}");
}
else
header("Location: ./index.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Nouvel utilisateur</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/ico" href="./Images/favicon.ico"/>
  <link rel="stylesheet" type="text/css" href="./CSS/nouvelUtilisateur.css">
</head>
<body>
<h1>Créer un compte</h1>
<form id="formulaire" class="" action="./mc_creerCompte.php" method="post">
  <label for="prenom">Prénom</label>
  <input type="text" name="prenom" value="<?=$_SESSION['newUser']['prenom']?>" required>
  <br>
  <label for="nom">Nom</label>
  <input type="text" name="nom" value="<?=$_SESSION['newUser']['nom']?>" required>
  <br>
  <label for="nbSessions">Nombre de sessions en informatique</label>
  <input type="number" name="nbSessions" min="1" max="6" required>
  <br>
  <label for="specialite">Spécialité (facultatif)</label>
  <input type="text" name="specialite">
  <br>
  <input type="hidden" name="email" value="<?=$_SESSION['newUser']['email']?>">
  <input type="hidden" name="image" value="<?=$_SESSION['newUser']['image']?>">
  <input type="hidden" name="id" value="<?=$_SESSION['id']?>">
  <input type="submit" value="Valider">
</form>
</body>
</html>
