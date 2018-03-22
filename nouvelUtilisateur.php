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
  <link rel="stylesheet" type="text/css" href="./CSS/nouvelUtilisateur.css">
</head>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-116236338-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-116236338-1');
</script>
<body>
  <h1>Créer un compte</h1>
  <form id="formulaire" class="" action="./mc_creerCompte.php" method="post">
    <table>
      <tr>
        <td><label for="prenom">Prénom :</label></td>
        <td><input type="text" name="prenom" value="<?=$_SESSION['newUser']['prenom']?>" required></td>
      </tr>
      <tr>
        <td><label for="nom">Nom :</label></td>
        <td><input type="text" name="nom" value="<?=isset($_SESSION['newUser']['nom']) ? $_SESSION['newUser']['nom'] : ""?>" required></td>
      </tr>
      <tr>
        <td><label for="nbSessions">Nombre de sessions en informatique :</label></td>
        <td><input type="number" name="nbSessions" min="1" max="6" required></td>
      </tr>
      <tr>
        <td><label for="specialite">Spécialité (facultatif) :</label></td>
        <td><input type="text" name="specialite"></td>
      </tr>
      <input type="hidden" name="email" value="<?=$_SESSION['newUser']['email']?>">
      <input type="hidden" name="image" value="<?=$_SESSION['newUser']['image']?>">
      <input type="hidden" name="id" value="<?=$_SESSION['id']?>">
      <tr>
        <td></td>
        <td><input id="submit" class="btn btn-success btn-sm" type="submit" value="Valider"></td>
      </tr>
    </table>
  </form>
</body>
</html>
