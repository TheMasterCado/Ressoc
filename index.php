<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="google-signin-client_id" content="374009514589-ie80vs5damvpplc85uni3ep2emi64f0b.apps.googleusercontent.com">
  <link rel="shortcut icon" type="image/ico" href="./Images/favicon.ico"/>
  <link rel="stylesheet" type="text/css" href="./CSS/index.css">
  <title>Login Ressoc</title>
</head>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-116236338-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-116236338-1');
</script>
<body>
  <script src="https://apis.google.com/js/platform.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
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
        else
          window.location.replace("./feed.php");
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
