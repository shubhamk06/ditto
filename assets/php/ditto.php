<?php
#Ditto library
class ditto {
  #Function leveraging openssl to create random data
  private function opensslRand($min = 0, $max = 1000) {
    $range = $max - $min;
    if ($range < 1) return $min;
    $log = log($range, 2);
    $bytes = (int) ($log / 8) + 1;
    $bits = (int) $log + 1;
    $filter = (int) (1 << $bits) - 1;
    do {
      $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
      $rnd = $rnd & $filter;
    } while ($rnd >= $range);
    return $min + $rnd;
  }

  #Function leveraging openssl to create a random 128-char string
  public function createSalt () {
    return hash(
      "sha512",
      time()
      . ($str = substr(
        str_shuffle(
          str_repeat(
            "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
            . "`~0123456789!@$%^&*()-_+={}[]\\|:;'\"<,>."
            . bin2hex(openssl_random_pseudo_bytes(64)),
            self::opensslRand(32, 64)
          )
        ),
        1,
        self::opensslRand(2048, 8192)
      ))
      . ($strt = bin2hex(openssl_random_pseudo_bytes(strlen($str)/8)))
      . strlen($strt)*self::opensslRand(4, 128)
    );
  }

  #Function to create a uuid v4
  static function uuid () {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
      mt_rand( 0, 0xffff ),
      mt_rand( 0, 0x0fff ) | 0x4000,
      mt_rand( 0, 0x3fff ) | 0x8000,
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }

  #Function to redirect the user
  public static function redirect($url) {
    if (!headers_sent()) {
      header("HTTP/1.1 301 Moved Permanently");
      header("Location: $url");
      return true;
    } else {
      return false;
    }
  }

  #Function to cleanly report data
  public static function report ($what) {
    echo "<div class='ribbon dark'><div class='container'>";
    echo "<pre style='text-align:left;font-size:14px'>";
    var_dump($what);
    echo "</pre></div></div>";
  }

  #Function to check if the user is logged in
  public static function checkSession ($simple = false) {
    #Verify MySQL will work
    global $db;
    if (!is_object($db)) return "noPDO";

    #Check session blob presence
    if (!array_key_exists("ditto-session", $_COOKIE)) return "noCookie";
    if (strlen($_COOKIE["ditto-session"]) != 128) return "badSession";

    #Check that blob is in database
    $check = $db->prepare("SELECT * FROM blobs WHERE hash=?");
    $check->execute([$_COOKIE["ditto-session"]]);
    $check = $check->fetchAll(PDO::FETCH_ASSOC);
    if (count($check) != 1) return "nonexistantBlob";

    #Check that the blob is within time limits
    if ($check[0]["date"] <= strtotime("-30 days")) return "oldSession";

    #If no errors were triggered then return the user ID or true
    return $simple == true ? true : $check[0]["user"];
  }

  #Function to require a login
  public static function requireLogin () {
    #If a user is not logged in, move them
    if (strlen($user = self::checkSession()) != 36)
      self::redirect("/?loginrequired");

    return $user;
  }

  #Function to return user information
  public static function getUser ($userID) {
    #Verify MySQL will work
    global $db;
    if (!is_object($db)) return "noPDO";

    #Check that the user ID is good
    if (strlen($userID) != 36) return "badID";

    #Check that user is in database
    $check = $db->prepare("SELECT * FROM users WHERE id=?");
    $check->execute([$userID]);
    $check = $check->fetchAll(PDO::FETCH_ASSOC);
    if (count($check) != 1) return "nonexistantUser";

    #Return user data
    return $check[0];
  }

  #Function to enter data for a user
  public static function enterData ($dataType, $userID, $data) {
    #Verify MySQL will work
    global $db;
    if (!is_object($db)) return "noPDO";

    #Check that the user ID is good
    if (strlen($userID) != 36) return "badID";

    #Check that user is in database
    $check = $db->prepare("SELECT * FROM users WHERE id=?");
    $check->execute([$userID]);
    $check = $check->fetchAll(PDO::FETCH_ASSOC);
    if (count($check) != 1) return "nonexistantUser";
    
    $insert = $db->prepare(
      "INSERT INTO dataPoints (id, user, type, date, data) "
      . "VALUES (?, ?, ?, ?, ?)"
    );
    $insert->execute($report = [
      $id = ditto::uuid(),
      $userID,
      $dataType,
      time(),
      json_encode($data)
    ]);
    $inserted = $insert->rowCount();
  }
}