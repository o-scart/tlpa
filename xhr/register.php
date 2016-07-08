<?php

/**
 * handles the registration process
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('core/TLPA.class.php');
require_once('core/Helper.class.php');

//sanitize username (only letters, digits, underscore and dash)
$user = $_POST['user'];
if (Helper::valStringNotEmpty($user)) {
    $user = strtolower(Helper::sanString($user));
} else {
    TLPA::FD('8');
}

if (!preg_match("/^[a-z0-9_\-]+$/", $user)) {
    //invalid character found, abort
    TLPA::FD('8');
}

//check if username exists
if (Helper::userExists($user)) {
    //user exists, abort
    TLPA::FD('7');
}

//username free, check sanity of form
$mail = $_POST['mail'];
$sec_q = $_POST['secQuestion'];
$sec_a = $_POST['secAnswer'];

if (Helper::valStringNotEmpty($mail) & Helper::valStringNotEmpty($sec_q) & Helper::valStringLen($sec_a, 128)) {
    $mail = Helper::sanEmail($mail);
    $sec_q = Helper::sanString($sec_q);
} else {
    TLPA::FD('8');
}

if (!Helper::valEmail($mail)) {
    TLPA::FD('8');
}

if ((strlen($sec_q) == 0)) {
    TLPA::FD('8');
}

//enter in database
$keyfile = Helper::genKF();

$salt = Helper::genSalt($user);

$state = TLPA::DB()->prepare("INSERT INTO users(user, mail, sec_question, sec_answer, keyfile, salt)
	VALUES(:user, :mail, :secquest, :secansw, :kf, :salt)");

$sec_a = TLPA::SECHASH($sec_a, $salt);
$keyfile_hash = TLPA::SECHASH($keyfile, $salt);

$state->execute(array(
    ':user' => $user,
    ':mail' => $mail,
    ':secquest' => $sec_q,
    ':secansw' => $sec_a,
    ':kf' => $keyfile_hash,
    ':salt' => $salt));

//result check
$state = TLPA::DB()->prepare("SELECT * FROM users WHERE user=:user AND mail=:mail AND sec_question=:secquest AND sec_answer=:secansw AND keyfile=:kf AND salt=:salt");
$state->execute(array(
    ':user' => $user,
    ':mail' => $mail,
    ':secquest' => $sec_q,
    ':secansw' => $sec_a,
    ':kf' => $keyfile_hash,
    ':salt' => $salt));
$res = $state->fetch(PDO::FETCH_ASSOC);

if (!$res) {
    //no entry found, something gone wrong
    TLPA::FD('1');
}

//create a folder for user images
$pathname = "../uploads/" . $user . "/";
if (!mkdir($pathname)) {
    //could not create folder
    TLPA::FD('1');
}

//create dummy images to prevent read errors, if user does not set image stage
$count = 0;
for ($row = 0; $row < 3; $row++) {
    for ($col = 0; $col < 3; $col++) {
        $filename = $user . $count . ".jpg";
        $dummy = imagecreatetruecolor(200, 200);
        $bg = imagecolorallocate($dummy, rand(0,255), rand(0,255), rand(0,255));
        imagefilledrectangle($dummy, 0, 0, 200, 200, $bg);
        imagejpeg($dummy, $pathname . $filename);
        imagedestroy($dummy);
        $count++;
    }
}

//all fine
TLPA::NEWSESS();

$_SESSION['authStage'] = 3; //fully logged in
$_SESSION['user'] = $user;

Helper::enterLog($user, $_SERVER['REMOTE_ADDR']);

$_SESSION['sessionNo'] = Helper::getLastLogNo($user);

TLPA::RSET('sessionId', session_id());
TLPA::RSET('sessionSecret', $_SESSION['secret']);
Helper::sendKF($user, $keyfile);

TLPA::RFIN();
?>