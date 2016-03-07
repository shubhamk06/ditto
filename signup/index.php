<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("../assets/php/header.php");
$exception = false;

if (ditto::checkSession(true) === true) ditto::redirect("/dash/");

If (array_key_exists("email", $_POST)) {
  #Check if email is present
  $check = $db->prepare("SELECT * FROM users WHERE email=?");
  $check->execute([$_POST["email"]]);
  $check = $check->fetchAll(PDO::FETCH_ASSOC);

  try {
    #User needs to be created
    if (count($check) == 0) {
      $salt = ditto::createSalt();
      $password = hash("sha512", $salt . $_POST["password"]);

      #Add user
      $insert = $db->prepare(
        "INSERT INTO users (id, email, password, salt, dateRegistered) "
        . "VALUES (?, ?, ?, ?, ?)"
      );
      $insert->execute($report = [
        $userUUID = ditto::uuid(),
        $_POST["email"],
        $password,
        $salt,
        time()
      ]);
      $inserted = $insert->rowCount();

      #Make sure the user was inserted
      if ($inserted != 1)
        throw new Exception(
          "User could not be created."
        );
    } else {
      #Get the user's salt, to hash the password
      $salt = $check[0];
      $userUUID = $salt["id"];
      $salt = $salt["salt"];
      $password = hash("sha512", $salt . $_POST["password"]);
      
      #Verify that the password was correct
      $check = $db->prepare("SELECT * FROM users WHERE email=? AND password=?");
      $check->execute([$_POST["email"], $password]);
      $check = $check->fetchAll(PDO::FETCH_ASSOC);

      #If the user+password cannot be found
      if (count($check) != 1)
        throw new Exception(
          "Password provided was incorrect."
        );
    }
  } catch (Exception $e) {
    #Display an error
    echo "<div class='ribbon dark mini'><div class='container'>";
    echo $e->getMessage();
    echo "</div></div>";
    $exception = true;
  }

  #Log user in
  if (!$exception) {
    #Create user blob
    $blob = $db->prepare(
      "INSERT INTO blobs (id, user, date, hash) VALUES (?, ?, ?, ?)"
    );

    #Insert blob for user login
    $blob->execute(
      [
        $blobUUID = ditto::uuid(),
        $userUUID,
        time(),
        $hash = hash(
          "sha512",
          time().$_POST["email"].$userUUID.ditto::uuid().ditto::createSalt()
        )
      ]
    );

    #Set cookie
    setcookie(
      "ditto-session",
      $hash,
      strtotime('+30 days'),
      "/",
      "zbee.me"
    );

    #Move the user to a different page
    ditto::redirect("/dash/");
  }
}
?>

<div class="ribbon">
  <div class="container">
    <h1>Sign Up or Log In</h1>
    <form action="" method="post">
      <input type="email" placeholder="Email" name="email">
      <br><br>
      <input type="password" placeholder="Password" name="password">
      <br><br>
      <a class="button" href="../">Cancel</a>
      <input type="submit" class="button" value="Submit">
    </form>
  </div>
</div>

<?php
require("../assets/php/footer.php");
?>