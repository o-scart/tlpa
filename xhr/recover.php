<?php

/**
 * handles the recovery process, verifies inputs
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('core/TLPA.class.php');
require_once('core/Helper.class.php');

if (!TLPA::OPENSESS($_POST['sessionId'], $_POST['sessionSecret'])) {
    TLPA::FD('6');
}

$user = $_SESSION['user'];
$recSecret = $_POST['recSecret'];

switch ($_SESSION['recStage']) {
    case 0:
        //sec_q & sec_a
        if (!Helper::verSecAnswer($user, $recSecret)) {
            Helper::incBadLogin($user);
            Helper::enterBadLog($user, $_SERVER['REMOTE_ADDR']);
            session_unset();
            session_destroy();
            TLPA::FD('5');
        }
        break;
    case 1:
        //keyfile
        list($type, $recSecret) = explode(';', $recSecret);
        list(, $recSecret) = explode(',', $recSecret);
        $recSecret = base64_decode($recSecret);

        if (!Helper::verKF($user, $recSecret)) {
            Helper::incBadLogin($user);
            Helper::enterBadLog($user, $_SERVER['REMOTE_ADDR']);
            session_unset();
            session_destroy();
            TLPA::FD('5');
        }
        break;
    case 2:
        //one time password
        if (!Helper::verOTP($user, $recSecret)) {
            Helper::incBadLogin($user);
            Helper::enterBadLog($user, $_SERVER['REMOTE_ADDR']);
            session_unset();
            session_destroy();
            TLPA::FD('5');
        }
        break;
}

$_SESSION['recStage'] ++;

//all stages complete, user permitted to change password
if ($_SESSION['recStage'] == 3) {
    // enter log when fully logged in
    Helper::enterLog($user, $_SERVER['REMOTE_ADDR']);

    // set the session number to last log entry number
    $_SESSION['sessionNo'] = Helper::getLastLogNo($user);

    //reset the limit_bad counters
    Helper::resetBadLogin($user);
    Helper::resetBadUnlock($user);

    // Keyfile has been used, generate new and send to user
    $kf = Helper::genKF();
    Helper::setKF($user, $kf);
    TLPA::RSET('keyfile', $kf);
    Helper::sendKF($user, $kf);

    $_SESSION['authStage'] = 3;
}

TLPA::RFIN();
?>

