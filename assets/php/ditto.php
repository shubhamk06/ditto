<?php
class ditto {
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
            $this->opensslRand(32, 64)
          )
        ),
        1,
        $this->opensslRand(2048, 8192)
      ))
      . ($strt = bin2hex(openssl_random_pseudo_bytes(strlen($str)/8)))
      . strlen($strt)*$this->opensslRand(4, 128)
    );
  }

  function uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
      mt_rand( 0, 0xffff ),
      mt_rand( 0, 0x0fff ) | 0x4000,
      mt_rand( 0, 0x3fff ) | 0x8000,
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }

  public static function report ($what) {
    echo "<div class='ribbon dark'><div class='container'>";
    echo "<pre style='text-align:left;font-size:14px'>";
    var_dump($what);
    echo "</pre></div></div>";
  }
}