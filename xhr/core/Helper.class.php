<?php

//require_once('TLPA.class.php');
require_once 'phpmailer/class.phpmailer.php';
require_once 'phpmailer/class.smtp.php';

class Helper {
    // --------------------------------------------------------------------------------------
    // General purpose

    /**
     * checks if is not null, is string and not empty
     * @param string $string 
     * @return boolean
     */
    public static function valStringNotEmpty($string) {
        return (isset($string) && is_string($string) && (strlen($string) > 0));
    }

    /**
     * checks if is not null, is string and specific length
     * @param string $string
     * @param int $length
     * @return boolean
     */
    public static function valStringLen($string, $length) {
        return (isset($string) && is_string($string) && (strlen($string) == $length));
    }

    /**
     * validates given mail address
     * @param string $mail
     * @return boolean
     */
    public static function valEmail($mail) {
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }

    /**
     * removes invalid characters from mail, returns sanitized mail
     * @param string $mail
     * @return string
     */
    public static function sanEmail($mail) {
        return filter_var($mail, FILTER_SANITIZE_EMAIL);
    }

    /**
     * sanitizes string
     * @param string $string
     * @return string
     */
    public static function sanString($string) {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }

    // --------------------------------------------------------------------------------------
    // Generator functions    

    /**
     * generates user specific salt hash
     * @param string $user
     * @return string
     */
    public static function genSalt($user) {
        return hash("sha512", $user . randstr(1024));
    }

    /**
     * generates a new keyfile
     * @return string
     */
    public static function genKF() {
        return randstr(2048);
    }

    /**
     * generates otp
     * @return string
     */
    public static function genOTP() {
        return randstr(128);
    }

    /**
     * generates a token for the unlocking link
     * @return string
     */
    public static function genToken() {
        return randstr(64);
    }

    // --------------------------------------------------------------------------------------
    // Send Mail and attachments

    /**
     * Send a Mail to the given user with subject, the message iteself and optional with an attachment
     * @param string $user
     * @param string $subject
     * @param string $body
     * @param string $attach
     * @return boolean false, if unsuccsseful
     */
    private static function sendMail($user, $subject, $body, $attach) {
        $state = TLPA::DB()->prepare("SELECT mail FROM users WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            // no data found in DB
            TLPA::FD('2');
        }
        $mail_set = TLPA::GETMAIL();

        // configure PHPMailer
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $mail_set['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $mail_set['user'];
        $mail->Password = $mail_set['pw'];
        $mail->SMTPSecure = $mail_set['enc'];
        $mail->Port = $mail_set['port'];

        // build Mail
        $mail->setFrom($mail_set['addr'], $mail_set['name']);
        $mail->addAddress($res['mail']);
        $mail->addBCC($mail_set['addr']);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // optionally include attachment
        if (isset($attach)) {
            $mail->addAttachment($attach);
        }
        return $mail->send();
    }

    /**
     * Sends an email with the keyfile attached to the given user
     * @param string $user
     * @param string $kf
     */
    public static function sendKF($user, $kf) {
        file_put_contents('../uploads/' . $user . '/keyfile.txt', $kf);
        $sec = TLPA::GETSEC();
        $subject = 'TLPA Keyfile for ' . $user;
        $body = 'Hello ' . $user . '!' . "\n\nThank you for using the Three Level Authentication System.\n"
                . "You will find your keyfile attached to this message.\n"
                . "With the current settings, you may have \n"
                . $sec['lim_bad_log'] . " unsuccessful Logins and\n"
                . $sec['lim_bad_unl'] . " unsuccessful Unlocks.\n\n"
                . "Sincerly yours,\nthe TLPA-System";
        if (!self::sendMail($user, $subject, $body, '../uploads/' . $user . '/keyfile.txt')) {
            TLPA::FD('1');
        }
        unlink('../uploads/' . $user . '/keyfile.txt');
    }

    /**
     * Sends an email with the unlocking link to the given user
     * @param string $user
     * @param string $link
     */
    public static function sendLink($user, $link) {
        $subject = 'TLPA Unlock-Link for ' . $user;
        $body = 'Hello ' . $user . "!\n\nIt seems you have entered your password incorrectly too many times.\n"
                . "Your account has been temporarily locked.\n"
                . "Click on the following Link to regain access.\n\n"
                . $link . "\n\nSincerly yours,\nthe TLPA-System";
        if (!self::sendMail($user, $subject, $body, NULL)) {
            TLPA::FD('1');
        }
    }

    /**
     * Sends an email with the one time password to the given user
     * @param string $user
     * @param string $otp
     */
    public static function sendOTP($user, $otp) {
        $sec = TLPA::GETSEC();
        $subject = 'TLPA One-Time-Password for ' . $user;
        $body = 'Hello ' . $user . "\n\nYou have requested a password recovery.\n"
                . "Please enter the following one-time-password in the recovery process.\n"
                . "You have " . $sec['val_otp'] . " minutes to enter the password!\nPassword follows:\n\n"
                . $otp . "\n\nSincerly yours,\nthe TLPA-System";
        if (!self::sendMail($user, $subject, $body, NULL)) {
            TLPA::FD('1');
        }
    }

    // --------------------------------------------------------------------------------------
    // Database checks and limits

    /**
     * checks if exists in DB
     * @param string $user
     * @return boolean
     */
    public static function userExists($user) {
        $state = TLPA::DB()->prepare("SELECT user FROM users WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            //user exists
            return true;
        } else {
            // user not found
            return false;
        }
    }

    /**
     * checks if user is temporarily locked
     * @param string $user
     * @return boolean
     */
    public static function issetTempLock($user) {
        $limit = TLPA::GETSEC()['lim_bad_log'];
        $state = TLPA::DB()->prepare("SELECT no_bad_logins FROM users WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            // no data found in DB, unknown user
            TLPA::FD('2');
        }
        return ($res['no_bad_logins'] >= $limit);
    }

    /**
     * checks if user is permanently locked
     * @param string $user
     * @return boolean
     */
    public static function issetPermlock($user) {
        $limit = TLPA::GETSEC()['lim_bad_unl'];
        $state = TLPA::DB()->prepare("SELECT no_bad_unlocks FROM users WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            // no data found in DB, unknown user
            TLPA::FD('2');
        }
        return ($res['no_bad_unlocks'] >= $limit);
    }

    // --------------------------------------------------------------------------------------
    // Database entries

    /**
     * increments bad login count for user
     * @param string $user
     */
    public static function incBadLogin($user) {
        $state = TLPA::DB()->prepare("UPDATE users SET no_bad_logins = no_bad_logins + 1 WHERE user = :user");
        $state->execute(array(':user' => $user));
    }

    /**
     * increments bad unlock count for user
     * @param string $user
     */
    public static function incBadUnlock($user) {
        $state = TLPA::DB()->prepare("UPDATE users SET no_bad_unlocks = no_bad_unlocks + 1 WHERE user = :user");
        $state->execute(array(':user' => $user));
    }

    /**
     * resets the count of bad logins
     * @param string $user
     */
    public static function resetBadLogin($user) {
        $state = TLPA::DB()->prepare("UPDATE users SET no_bad_logins = 0 WHERE user = :user");
        $state->execute(array(':user' => $user));
    }

    /**
     * resets the count of bad unlocks
     * @param string $user
     */
    public static function resetBadUnlock($user) {
        $state = TLPA::DB()->prepare("UPDATE users SET no_bad_unlocks = 0 WHERE user = :user");
        $state->execute(array(':user' => $user));
    }

    /**
     * makes a new entry in the bad_log table
     * @param string $user
     * @param string $ip
     */
    public static function enterBadLog($user, $ip) {
        $state = TLPA::DB()->prepare("INSERT INTO bad_log(user, ip, event_time) VALUES (:user, :ip, NOW())");
        $state->execute(array(':user' => $user, ':ip' => $ip));
    }

    /**
     * makes a new entry in the log table
     * @param string $user
     * @param string $ip
     */
    public static function enterLog($user, $ip) {
        $limit = TLPA::GETSEC()['session'];
        $login = date('Y-m-d H:i:s', time());
        $logout = date('Y-m-d H:i:s', time() + 60 * $limit);
        $state = TLPA::DB()->prepare("INSERT INTO log(user, ip, login, logout) VALUES (:user, :ip, :login, :logout)");
        $state->execute(array(':user' => $user, ':ip' => $ip, ':login' => $login, ':logout' => $logout));
    }

    /**
     * updates the logout time to NOW in the log table
     * @param string $user
     * @param int $no
     */
    public static function updLogoutNow($user, $no) {
        $state = TLPA::DB()->prepare("UPDATE log SET logout = NOW() WHERE user = :user AND no = :no");
        $state->execute(array(':user' => $user, ':no' => $no));
    }

    /**
     * makes new entry in the auth table
     * @param string $user
     * @param string $otp
     * @param int $exp
     */
    public static function enterAuth($user, $otp, $exp) {
        $state = TLPA::DB()->prepare("INSERT INTO auth(user, otp, expire) VALUES (:user, :otp, :exp)");
        $state->execute(array(':user' => $user, ':otp' => $otp, ':exp' => $exp));
    }

    /**
     * cleans up the Auth table by deleting all entries older than timestamp
     * @param int $timestamp
     */
    public static function cleanAuth($timestamp) {
        $state = TLPA::DB()->prepare("DELETE FROM auth WHERE expire < :ts");
        $state->execute(array(':ts' => $timestamp));
    }

    // --------------------------------------------------------------------------------------
    // get database data  

    /**
     * retrieves the user salt from the database
     * @param string $user
     * @return string
     */
    public static function getSalt($user) {
        $state = TLPA::DB()->prepare("SELECT salt FROM users WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            // no data found in DB, unknown user
            TLPA::FD('2');
        }
        return $res['salt'];
    }

    /**
     * return the last login number
     * @param string $user
     * @return int
     */
    public static function getLastLogNo($user) {
        $state = TLPA::DB()->prepare("SELECT MAX(no) AS max_no FROM log WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            // no data found in DB, unknown user
            TLPA::FD('2');
        }
        return $res['max_no'];
    }

    /**
     * get all data form log-table for a given user
     * @param string $user
     * @return array
     */
    public static function getLogData($user) {
        $state = TLPA::DB()->prepare("SELECT login, logout, ip FROM log WHERE user = :user ORDER BY login DESC");
        $state->execute(array(':user' => $user));
        $res = $state->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * get all data form bad_log-table for a given user
     * @param string $user
     * @return array
     */
    public static function getBadLogData($user) {
        $state = TLPA::DB()->prepare("SELECT event_time, ip FROM bad_log WHERE user = :user ORDER BY event_time DESC");
        $state->execute(array(':user' => $user));
        $res = $state->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    // --------------------------------------------------------------------------------------
    // verify functions (auth.php, recover.php)

    /**
     * verifies password hash for given user
     * @param string $user
     * @param string $password
     * @return boolean
     */
    public static function verPassword($user, $password) {
        if (!self::valStringLen($password, 128)) {
            // not a hash
            TLPA::FD('8');
        }
        $state = TLPA::DB()->prepare("SELECT password FROM users WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            // no data found in DB, unknown user
            TLPA::FD('2');
        }
        $password = TLPA::SECHASH($password, self::getSalt($user));
        return ($res['password'] == $password);
    }

    /**
     * verifies colorcode hash for given user
     * @param string $user
     * @param string $color
     * @return boolean
     */
    public static function verColor($user, $color) {
        if (!self::valStringLen($color, 128)) {
            // not a hash
            TLPA::FD('8');
        }
        $state = TLPA::DB()->prepare("SELECT color_code FROM users WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            // no data found in DB
            TLPA::FD('2');
        }
        $color = TLPA::SECHASH($color, self::getSalt($user));
        return ($res['color_code'] == $color);
    }

    /**
     * verifies image
     * @param array $imageHashArray
     * @return boolean
     */
    public static function verImage($imageHashArray) {
        if (!isset($_SESSION['secretHashes'])) {
            TLPA::FD('8');
        }
        $secret = $_SESSION['secretHashes'];
        if (sizeof($secret) != 9 | sizeof($imageHashArray) != 9) {
            TLPA::FD('1');
        }
        for ($i = 0; $i < 9; $i++) {
            if ($secret[$i] != $imageHashArray[$i]) {
                return false;
            }
        }
        return true;
    }

    /**
     * verifies the secure answer for given user
     * @param string $user
     * @param string $sec_a
     * @return boolean
     */
    public static function verSecAnswer($user, $sec_a) {
        if (!self::valStringLen($sec_a, 128)) {
            // not a hash
            TLPA::FD('8');
        }
        $state = TLPA::DB()->prepare("SELECT sec_answer FROM users WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            // no data found in DB
            TLPA::FD('2');
        }
        return ($res['sec_answer'] == TLPA::SECHASH($sec_a, self::getSalt($user)));
    }

    /**
     * verifies the keyfile hash for given user
     * @param string $user
     * @param string $kf
     * @return boolean
     */
    public static function verKF($user, $kf) {
        if (!self::valStringLen($kf, 2048)) {
            // wrong length
            TLPA::FD('8');
        }
        $state = TLPA::DB()->prepare("SELECT keyfile FROM users WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            // no data found in DB
            TLPA::FD('2');
        }
        return ($res['keyfile'] == TLPA::SECHASH($kf, self::getSalt($user)));
    }

    /**
     * verifies the OTP
     * @param string $user
     * @param string $otp
     * @return boolean
     */
    public static function verOTP($user, $otp) {
        if (!self::valStringLen($otp, 128)) {
            // wrong length
            TLPA::FD('8');
        }
        if (!(isset($_SESSION['oTP']) && isset($_SESSION['oTP_expire']))) {
            TLPA::FD('8');
        }
        $equals = ($_SESSION['oTP'] == TLPA::SECHASH($otp, self::getSalt($user)));
        $is_valid = ($_SESSION['oTP_expire'] >= time());
        return ($equals && $is_valid);
    }

    //--------------------------------------------------------------------------------------
    // getter functions (preauth.php, prerecover.php)

    /**
     * prepares images for transmission, saves the right order in hash array
     * @param string $user
     * @return array
     */
    public static function getImage($user) {
        $public = array();
        $secret = array();
        for ($i = 0; $i < 9; $i++) {
            $temp = file_get_contents("../uploads/" . $user . "/" . $user . $i . ".jpg");
            if (!$temp) {
                // file not found
                TLPA::FD('1');
            }
            $out = base64_encode($temp);
            $public[] = $out;
            $secret[] = hash("sha256", $out);
        }
        shuffle($public);
        $_SESSION['secretHashes'] = $secret;
        return $public;
    }

    /**
     * gets the secure question from the database for a given user
     * @param string $user
     * @return string
     */
    public static function getSecQ($user) {
        $state = TLPA::DB()->prepare("SELECT sec_question FROM users WHERE user = :user");
        $state->execute(array(':user' => $user));
        $res = $state->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
            // no data found in DB
            TLPA::FD('2');
        }
        return ($res['sec_question']);
    }

    // --------------------------------------------------------------------------------------
    // udpate passwords and user data (set_auth.php, set_recover)

    /**
     * hashes and sets the password for a given user
     * @param string $user
     * @param string $password
     */
    public static function setPassword($user, $password) {
        $password = TLPA::SECHASH($password, self::getSalt($user));
        $state = TLPA::DB()->prepare("UPDATE users SET password = :pw WHERE user = :user");
        $state->execute(array(':pw' => $password, ':user' => $user));
    }

    /**
     * hashes and sets the color_code for a given user
     * @param string $user
     * @param string $color
     */
    public static function setColor($user, $color) {
        $color = TLPA::SECHASH($color, self::getSalt($user));
        $state = TLPA::DB()->prepare("UPDATE users SET color_code = :color WHERE user = :user");
        $state->execute(array(':color' => $color, ':user' => $user));
    }

    /**
     * splits and saves the images for a given user
     * @param string $user
     * @param string $path_to_image
     */
    public static function setImage($user, $path_to_image) {
        //initial settings
        $dst_path = "../uploads/" . $user . "/";
        $dst_width = 200;
        $dst_height = 200;

        //read image
        $src_im = imagecreatefromjpeg("" . $path_to_image);

        //image must be (3 x dst_weight) * (3 x dst_height), check resolution
        $src_width = imagesx($src_im);
        $src_height = imagesy($src_im);
        if (!(($src_width == 3 * $dst_width) & ($src_height == 3 * $dst_height))) {
            TLPA::FD('8');
        }

        $count = 0;

        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {

                $filename = $user . $count . ".jpg";

                $dst_im = imagecreatetruecolor($dst_width, $dst_height);

                //bool imagecopyresized ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , 
                //int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )

                imagecopyresized($dst_im, $src_im, 0, 0, $col * $dst_width, $row * $dst_height, $dst_width, $dst_height, $dst_width, $dst_height);
                imagejpeg($dst_im, $dst_path . $filename);
                imagedestroy($dst_im);

                $count++;
            }
        }

        unlink($path_to_image);
    }

    /**
     * sets the mail address for a given user
     * @param string $user
     * @param string $mail
     */
    public static function setMail($user, $mail) {
        $state = TLPA::DB()->prepare("UPDATE users SET mail = :mail WHERE user = :user");
        $state->execute(array(':mail' => $mail, ':user' => $user));
    }

    /**
     * hashes the secure answer und sets Q&A fir a given user
     * @param string $user
     * @param string $sec_q
     * @param string $sec_a
     */
    public static function setSecQA($user, $sec_q, $sec_a) {
        $sec_a = TLPA::SECHASH($sec_a, self::getSalt($user));
        $state = TLPA::DB()->prepare("UPDATE users SET sec_question = :secquest, sec_answer = :secansw WHERE user = :user");
        $state->execute(array(':secquest' => $sec_q, ':secansw' => $sec_a, ':user' => $user));
    }

    /**
     * hashes and sets the keyfile for a given user
     * @param string $user
     * @param string $kf
     */
    public static function setKF($user, $kf) {
        $kf = TLPA::SECHASH($kf, self::getSalt($user));
        $state = TLPA::DB()->prepare("UPDATE users SET keyfile = :kf WHERE user = :user");
        $state->execute(array(':kf' => $kf, ':user' => $user));
    }

}

?>
