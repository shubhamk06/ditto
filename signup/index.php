<?php
require("../assets/php/header.php");

If (array_key_exists("email", $_POST)) {
  #Check if email is present
  $check = $db->prepare("SELECT * FROM users WHERE email=?");
  $check->execute([$_POST["email"]]);
  $check = $check->fetchAll(PDO::FETCH_ASSOC);

  #User needs to be created
  if (count($check) == 0) {
    $salt = $ditto->createSalt();
    $password = hash("sha512", $salt . $_POST["password"]);

    #Add user
    $insert = $db->prepare(
      "INSERT INTO users (id, email, password, salt, dateRegistered) "
      . "VALUES (?, ?, ?, ?, ?)"
    );
    $insert->execute($report = [
      $id = $ditto->uuid(),
      $_POST["email"],
      $password,
      $salt,
      time()
    ]);
    $inserted = $insert->rowCount();
  } else {
    
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