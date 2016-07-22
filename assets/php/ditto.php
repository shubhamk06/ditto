<?php

#Ditto library
class ditto {

  /**
   * Function leveraging openssl to create a random 128-char string
   *
   * @return string
   */
  public static function createSalt () {
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
      . ($strt = bin2hex(openssl_random_pseudo_bytes(strlen($str) / 8)))
      . strlen($strt) * self::opensslRand(4, 128)
    );
  }

  /**
   * Function leveraging openssl to create random data
   *
   * @param int $min
   * @param int $max
   *
   * @return int
   */
  static function opensslRand ($min = 0, $max = 1000) {
    $range = $max - $min;
    if ($range < 1) {
      return $min;
    }
    $log    = log($range, 2);
    $bytes  = (int) ($log / 8) + 1;
    $bits   = (int) $log + 1;
    $filter = (int) (1 << $bits) - 1;
    do {
      $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
      $rnd = $rnd & $filter;
    } while ($rnd >= $range);

    return $min + $rnd;
  }

  /**
   * Function to cleanly report data
   *
   * @param $what
   */
  public static function report ($what) {
    echo "<div class='ribbon dark'><div class='container'>";
    echo "<pre style='text-align:left;font-size:14px'>";
    var_dump($what);
    echo "</pre></div></div>";
  }

  /**
   * Function to require a login
   * @return bool|string
   */
  public static function requireLogin () {
    #If a user is not logged in, move them
    if (strlen($user = self::checkSession()) != 36) {
      self::redirect("/?loginrequired");
    }

    return $user;
  }

  /**
   * Function to check if the user is logged in
   *
   * @param bool $simple
   *
   * @return bool|string
   */
  public static function checkSession ($simple = false) {
    #Verify MySQL will work
    global $db;
    if (!is_object($db)) {
      return "noPDO";
    }

    #Check session blob presence
    if (!array_key_exists("ditto-session", $_COOKIE)) {
      return "noCookie";
    }
    if (strlen($_COOKIE["ditto-session"]) != 128) {
      return "badSession";
    }

    #Check that blob is in database
    $check = $db->prepare("SELECT * FROM blobs WHERE hash=?");
    $check->execute([$_COOKIE["ditto-session"]]);
    $check = $check->fetchAll(PDO::FETCH_ASSOC);
    if (count($check) != 1) {
      return "nonexistantBlob";
    }

    #Check that the blob is within time limits
    if ($check[0]["date"] <= strtotime("-30 days")) {
      return "oldSession";
    }

    #If no errors were triggered then return the user ID or true
    return $simple == true ? true : $check[0]["user"];
  }

  /**
   * Function to redirect the user
   *
   * @param $url
   *
   * @return bool
   */
  public static function redirect ($url) {
    if (!headers_sent()) {
      header("HTTP/1.1 301 Moved Permanently");
      header("Location: $url");

      return true;
    } else {
      return false;
    }
  }

  /**
   * Function to return user information
   *
   * @param $userID
   *
   * @return string
   */
  public static function getUser ($userID) {
    #Verify MySQL will work
    global $db;
    if (!is_object($db)) {
      return "noPDO";
    }

    #Check that the user ID is good
    if (strlen($userID) != 36) {
      return "badID";
    }

    #Check that user is in database
    $check = $db->prepare("SELECT * FROM users WHERE id=?");
    $check->execute([$userID]);
    $check = $check->fetchAll(PDO::FETCH_ASSOC);
    if (count($check) != 1) {
      return "nonexistantUser";
    }

    #Return user data
    return $check[0];
  }

  /**
   * Function to enter data for a user
   *
   * @param integer $dataType
   * @param string  $userID
   * @param array   $data
   *
   * @return bool|string
   */
  public static function enterData ($dataType, $userID, $data) {
    #Verify MySQL will work
    global $db;
    if (!is_object($db)) {
      return "noPDO";
    }

    #Check that the user ID is good
    if (strlen($userID) != 36) {
      return "badID";
    }

    #Check that user is in database
    $check = $db->prepare("SELECT * FROM users WHERE id=?");
    $check->execute([$userID]);
    $check = $check->fetchAll(PDO::FETCH_ASSOC);
    if (count($check) != 1) {
      return "nonexistantUser";
    }
    #Set up data to report
    $report = [
      $id = ditto::uuid(),
      $userID,
      $dataType,
      time(),
      json_encode($data)
    ];

    #Check that there's not a data point with similar data input recently
    $check = $db->prepare(
      "SELECT * FROM dataPoints WHERE user=? AND date>? AND data=?"
    );
    $check->execute([$userID, time() - 60 * 59, $report[4]]);
    $check = $check->fetchAll(PDO::FETCH_ASSOC);
    if (count($check) > 0) {
      return "tooRecentDataPoint";
    }

    #Insert into database
    $insert = $db->prepare(
      "INSERT INTO dataPoints (id, user, type, date, data) "
      . "VALUES (?, ?, ?, ?, ?)"
    );
    $insert->execute($report);

    #Check if insert worked
    $inserted = $insert->rowCount();

    return $inserted !== 1 ? false : true;
  }

  /**
   * Function to create a uuid v4
   *
   * @return string
   */
  static function uuid () {
    return sprintf(
      '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0x0fff) | 0x4000,
      mt_rand(0, 0x3fff) | 0x8000,
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff)
    );
  }

  /**
   * Function to get data points
   *
   * @param string             $userID
   * @param integer|bool       $dataType
   * @param array|integer|bool $timeFrame
   *
   * @return array|string
   */
  public static function getDataPoints (
    $userID,
    $dataType = false,
    $timeFrame = false
  ) {
    #Verify MySQL will work
    global $db;
    if (!is_object($db)) {
      return "noPDO";
    }

    #Check that the user ID is good
    if (strlen($userID) != 36) {
      return "badID";
    }

    #Check that user is in database
    $check = $db->prepare("SELECT * FROM users WHERE id=?");
    $check->execute([$userID]);
    $check = $check->fetchAll(PDO::FETCH_ASSOC);
    if (count($check) != 1) {
      return "nonexistantUser";
    }

    #Set up query
    $query = "SELECT * FROM dataPoints WHERE user=?";
    $input = [$userID];

    #Search for specific data type if provided
    if ($dataType !== false && $dataType !== null) {
      if (!is_numeric($dataType)) {
        return "badDataType";
      }

      $query .= " AND `type`=?";
      array_push($input, $dataType);
    }

    #Limit time frame if provided
    if ($timeFrame !== false && is_array($timeFrame)) {
      if (is_array($timeFrame)) {
        $query .= " AND `date`>? AND `date`<?";
        array_push($input, $timeFrame[0]);
        array_push($input, $timeFrame[1]);
      }
    } elseif (is_numeric($timeFrame)) {
      $query .= " AND `date`>?";
      array_push($input, $timeFrame);
    }

    #Perform query
    $get = $db->prepare($query . " ORDER BY date DESC");
    $get->execute($input);

    #Check if query worked
    $found = $get->rowCount();
    if ($found < 1) {
      return "noDataPoints";
    } else {
      return $get->fetchAll(PDO::FETCH_ASSOC);
    }
  }
}
