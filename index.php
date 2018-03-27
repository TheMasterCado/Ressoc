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
  <meta name="google-signin-client_id" content="374009514589-ie80vs5damvpplc85uni3ep2emi64f0b.apps.googleusercontent.com">
  <link rel="shortcut icon" type="image/ico" href="./Images/favicon.ico"/>
  <link rel="stylesheet" type="text/css" href="./CSS/index.css">
  <title>Login Ressoc</title>
</head>
<body>
  <script src="https://apis.google.com/js/platform.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
    function onSignIn(googleUser) {
      var profile = googleUser.getBasicProfile();
      var id_token = googleUser.getAuthResponse().id_token;
      $.post("./mc_connexion.php", {
        token: id_token,
        prenom: profile.getGivenName(),
        nom: profile.getFamilyName(),
        email: profile.getEmail(),
        image: profile.getImageUrl()
      }, function(data) {
        if (data == "NEW")
          window.location.replace("./nouvelUtilisateur.php");
        else if (data == "DENIED")
          alert("Something something not allowed");
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
