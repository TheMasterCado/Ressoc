<?php
session_start();
if(isset($_SESSION['id'])) {
  if(isset($_SESSION['newUser']))
    header("Location: ./nouvelUtilisateur.php");
}
else {
  header("Location: ./index.php");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>feed</title>
  </head>
  <body>
<?php
echo $_GET['id'];
echo intval($_GET['id']);
  // require 'bd.php';
  // $sql = "SELECT * FROM publication WHERE fk_utilisateur =
  //         (SELECT pk_utilisateur FROM utilisateur WHERE loginID = {$_GET['id']});";
  // $resultat = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  // foreach ($resultat as $pos => $publication) {
  //   echo $publication['texte'];
  // }
?>
  </body>
</html>
