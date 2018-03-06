<?php
session_start();
if(!isset($_SESSION['id']))
  header("Location: ./index.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>feed</title>
  </head>
  <body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="./JS/utils.js"></script>
<?php
  require 'bd.php';
  $sql = "SELECT * FROM publication WHERE loginID={$_SESSION['id']}";
  $resultat = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  foreach ($resultat as $pos => $publication) {
    $publication['texte']
  }
?>



<?php require maPage.php; ?>
  </body>
</html>
