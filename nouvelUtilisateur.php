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
  <!-- Google Analytics -->
  <script>
    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
    ga('create', 'UA-116236338-1', 'auto');
    ga('send', 'pageview');
  </script>
  <script async src='https://www.google-analytics.com/analytics.js'></script>
<!-- End Google Analytics -->
  <meta charset="utf-8">
  <title>Nouvel utilisateur</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="./CSS/nouvelUtilisateur.css">
</head>
<body>
  <script>
    function creerCompte() {
      ga('send', {
        hitType: 'event',
        eventCategory: 'utilisateur',
        eventAction: 'creationCompte'
      });
      document.getElementById("formulaire").submit();
    }
  </script>
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
        <td><a class="bouton btn btn-danger btn-sm" href="./signOut.php">Retour</a></td>
        <td><button class="bouton btn btn-success btn-sm" onclick="creerCompte()">Valider</button></td>
      </tr>
    </table>
  </form>
</body>
</html>
