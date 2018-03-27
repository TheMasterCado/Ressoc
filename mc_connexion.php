<?php
  require 'bd.php';
  require_once 'google-api-php-client-2.2.1/vendor/autoload.php';
  session_start();
  $client = new Google_Client(['client_id' => 374009514589-ie80vs5damvpplc85uni3ep2emi64f0b.apps.googleusercontent.com]);
  $payload = $client->verifyIdToken($_POST['token']);
  if ($payload) {
    $id = hash("sha256", $payload['sub']);
    $_SESSION['id'] = $id;
    $sql = "SELECT count(*) AS nb FROM utilisateur WHERE loginID = :id;";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $id]);
    $resultat = $stmt->fetch();
    if($resultat['nb'] == 0) {
      $_SESSION['newUser'] = $_POST;
      echo "NEW";
    }
    else {
      echo "EXISTING";
    }
  } else {
    echo "DENIED";
  }
 ?>
