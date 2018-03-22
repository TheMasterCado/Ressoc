<?php
  session_start();
  session_destroy();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="374009514589-ie80vs5damvpplc85uni3ep2emi64f0b.apps.googleusercontent.com">
    <title>Sign out</title>
  </head>
  <body>
      <script src="https://apis.google.com/js/platform.js"></script>
    <script>
       function onSignIn(googleUser) {
         var auth2 = gapi.auth2.getAuthInstance();
         auth2.signOut();
         window.location.replace("./index.php");
      }
      </script>
        <div data-width="0" data-height="0" class="g-signin2" data-onsuccess="onSignIn"></div>
  </body>
</html>
