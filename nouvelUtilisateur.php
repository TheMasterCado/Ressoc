<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Nouvel utilisateur</title>
  </head>
  <body>
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
