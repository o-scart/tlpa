<?php

ini_set('session.use_cookies', '0');

/**
 * recursive function for generating random strings
 * @param int $length
 * @return string
 */
function randstr($length) {
    $upperRand = "";
    if ($length > 62) {
        $upperRand = randstr($length - 62);
    }
    return $upperRand . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); //max length: 62
}

class TLPA {

    //response
    private static $R = array('status' => '0');
    //pdo database connection
    private static $db = NULL;
    //tlpa settings
    private static $sec = NULL;
    //email settings
    private static $mail = NULL;

    /**
     * initializes TLPA
     */
    public static function init() {
        $config = file_get_contents("core/sec/config.json.php");

        $config = json_decode($config, true);

        $dbcon = $config['db'];

        self::$sec = $config['sec'];
        self::$mail = $config['mail'];

        self::$db = new PDO('mysql:host=' . $dbcon['host'] . ';dbname=' . $dbcon['dbname'] . ';charset=utf8', $dbcon['dbuser'], $dbcon['pw']);
    }

    /**
     * sets response field $ak with value $v
     * @param string $ak
     * @param string $v
     */
    public static function RSET($ak, $v) {
        self::$R[$ak] = $v;
    }

    /**
     * unsets array field on $ak
     * @param string $ak
     */
    public static function RDEL($ak) {
        unset(self::$R[$ak]);
    }

    /**
     * gets array field on $ak
     * @param string $ak
     * @return string
     */
    public static function RGET($ak) {
        if ($ak == NULL) {
            return self::$R;
        }
        return self::$R[$ak];
    }

    /**
     * terminates script and writes json response
     */
    public static function RFIN() {
        die(json_encode(self::$R));
    }

    /**
     * response fail die 
     * terminates script with given error codes but doesn't discard other response data
     * @param string $errCode
     */
    public static function RFD($errCode) {
        self::$R['status'] = $errCode;
        self::RFIN();
    }

    /**
     * fail die 
     * terminates with error code, discards all other response data
     * @param string $errCode
     */
    public static function FD($errCode) {
        die(json_encode(array('status' => $errCode)));
    }

    /**
     * gets db connection
     * @return PDO
     */
    public static function DB() {
        return self::$db;
    }

    /**
     * opens new session with authStage = 0 and 20 char additional session secret
     * @return string
     */
    public static function NEWSESS() {
        session_start();

        $s = session_id();

        $_SESSION['secret'] = randstr(20);
        $_SESSION['authStage'] = 0;
        $_SESSION['recStage'] = 0;

        $_SESSION['discard_after'] = time() + 60 * self::$sec['session'];
        self::$R['timeout'] = $_SESSION['discard_after'];

        return $s;
    }

    /**
     * opens previously generated session 
     * returns false if session secret is invalid or if session has expired
     * @param string $sessionId
     * @param string $sessionSecret
     * @return boolean
     */
    public static function OPENSESS($sessionId, $sessionSecret) {
        if ($sessionId == "undefined" || $sessionId == null) {
            return false;
        }
        if (strlen($sessionSecret) != 20) {
            return false;
        }
        session_id($sessionId);
        session_start();
        if ($_SESSION['secret'] != $sessionSecret) {
            return false;
        }
        // check session expiration
        if (!isset($_SESSION['discard_after'])) {
            return false;
        }
        $now = time();
        if ($now > $_SESSION['discard_after']) {
            // session expired
            return false;
        } else {
            // renew expiration date
            $_SESSION['discard_after'] = $now + 60 * self::$sec['session'];
            self::$R['timeout'] = $_SESSION['discard_after'];
        }
        //update logout time for user
        if (isset($_SESSION['user']) && isset($_SESSION['sessionNo'])) {
            $logout = date('Y-m-d H:i:s', $now + 60 * self::$sec['session']);
            $state = self::$db->prepare("UPDATE log SET logout = :logout WHERE user = :user AND no = :no");
            $state->execute(array(':user' => $_SESSION['user'], ':logout' => $logout, ':no' => $_SESSION['sessionNo']));
        }
        return true;
    }

    /**
     * output length: 128 chars(hex encode)
     * @param string $input
     * @param string $salt
     * @return string
     */
    public static function SECHASH($input, $salt) {
        $input = hash("sha512", $input) . self::$sec['salt'] . "5z724!395%&&%&ids86%&/%" . hash("sha256", $input) . $salt;
        return hash("sha512", $input);
    }

    /**
     * returns the tlpa settings
     * @return array
     */
    public static function GETSEC() {
        return self::$sec;
    }

    /**
     * returns the mail settings
     * @return array
     */
    public static function GETMAIL() {
        return self::$mail;
    }

}

TLPA::init();
?>