<?php
header("Location: https://ditto.zbee.me/dash/");
die();
?>

  <meta http-equiv="Location" content="https://ditto.zbee.me/dash/">
  <script>
    window.location.replace("https://ditto.zbee.me/dash/");
    window.location.href = "https://ditto.zbee.me/dash/";
  </script>

  <div class="ribbon">
    <div class="container">
      <h1 class="bigbold">
        We're Sorry!
      </h1>
      <h1>
        The Page You Are Looking For Was Not Found.
      </h1>
      We apologize for the inconvenience. You can return to your dashboard now
      if you have not been redirected.
      <Br>
      <Br>
      <a class="ribbon" href="/dash/">Return to Dashboard</a>
    </div>
  </div>

<?php exit(); ?>