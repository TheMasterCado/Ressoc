<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="google-signin-client_id" content="374009514589-ie80vs5damvpplc85uni3ep2emi64f0b.apps.googleusercontent.com">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="./CSS/index.css">
  <title>Login Ressoc</title>
</head>
<body>
  <script src="https://apis.google.com/js/platform.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
  <?php if(isset($_GET['signOut'])) { ?>
    $(function() {
      gapi.load('auth2', function() {
        gapi.auth2.init();
      });
      var auth2 = gapi.auth2.getAuthInstance();
      auth2.signOut();
    });
    <?php } ?>
    function onSignIn(googleUser) {
      var profile = googleUser.getBasicProfile();
      $.post("./mc_connexion.php", {
        id: profile.getId(),
        prenom: profile.getGivenName(),
        nom: profile.getFamilyName(),
        email: profile.getEmail(),
        image: profile.getImageUrl()
      }, function(data) {
        if (data == "NEW")
          window.location.replace("./nouvelUtilisateur.php");
        else {
          window.location.replace("./feed.php?id=" + profile.getId());
        }
      });
    }
    </script>
    <div id="content" class="center-block">
      <h1>Ressoc</h1>
      <h6>Un réseau social presque intéressant.</h6>
      <div class="g-signin2" data-onsuccess="onSignIn"></div>
    </div>
  </body>
  </html>
