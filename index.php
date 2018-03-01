<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="google-signin-client_id" content="374009514589-ie80vs5damvpplc85uni3ep2emi64f0b.apps.googleusercontent.com">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <title>Login Ressoc</title>
</head>
<body>
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <script>
  function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    window.location.replace("./connexion.php?id="+profile.getId());
  }
  </script>
  <div class="g-signin2" data-onsuccess="onSignIn"></div>

  <div class=".modal">
    <p>testing</p>
  </div>
</body>
</html>
