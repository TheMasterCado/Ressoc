<?php
session_start();
if(isset($_SESSION['id'])) {
  if(!$_SESSION['newUser'])
  header("Location: ./feed.php?id=$_SESSION['id']");
}
else
header("Location: ./index.php");
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <script>
  gapi.load('auth2', function() {
    auth2 = gapi.auth2.init({
      client_id: '374009514589-ie80vs5damvpplc85uni3ep2emi64f0b.apps.googleusercontent.com',
      fetch_basic_profile: true,
      scope: 'profile'
    });
      var profile = auth2.currentUser.get().getBasicProfile();
      $('#prenom').attr('value', profile.getGivenName());
      $('#nom').attr('value', profile.getFamilyName());
      $('#email').attr('value', profile.getEmail());
      $('#id').attr('value', profile.getId());
    });
  });
  </script>
  <h1>Créer un compte</h1>
  <form id="formulaire" class="" action="./mc_creerCompte.php" method="post">
    <label for="prenom">Prénom</label>
    <input id="prenom" type="text" name="prenom">
    <br>
    <label for="nom">Nom</label>
    <input id="nom" type="text" name="nom">
    <br>
    <label for="nbSessions">Nombre de sessions en informatique</label>
    <input type="number" name="nbSessions" min="1" max="6">
    <br>
    <input id="email" type="hidden" name="email">
    <input id="id" type="hidden" name="id">
    <input type="submit" value="Valider">
  </form>
</body>
</html>
