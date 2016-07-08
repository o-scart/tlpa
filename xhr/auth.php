<?php

/**
 * handles the authentication process, verifies passwords
 */
require_once('core/TLPA.class.php');
require_once('core/Helper.class.php');

if (!TLPA::OPENSESS($_POST['sessionId'], $_POST['sessionSecret'])) {
    TLPA::FD('6');
}

$user = $_SESSION['user'];
$authSecret = $_POST['authSecret'];

switch ($_SESSION['authStage']) {
    case 0:
        //password
        if (!Helper::verPassword($user, $authSecret)) {
            Helper::incBadLogin($user);
            Helper::enterBadLog($user, $_SERVER['REMOTE_ADDR']);
            session_unset();
            session_destroy();
            TLPA::FD('5');
        }
        break;
    case 1:
        //colourset
        if (!Helper::verColor($user, $authSecret)) {
            Helper::incBadLogin($user);
            Helper::enterBadLog($user, $_SERVER['REMOTE_ADDR']);
            session_unset();
            session_destroy();
            TLPA::FD('5');
        }
        break;
    case 2:
        //picture
        if (!Helper::verImage(json_decode($authSecret, true))) {
            Helper::incBadLogin($user);
            Helper::enterBadLog($user, $_SERVER['REMOTE_ADDR']);
            session_unset();
            session_destroy();
            TLPA::FD('5');
        }
        break;
}

$_SESSION['authStage'] ++;

if ($_SESSION['authStage'] == 3) {
    // enter log when fully logged in
    Helper::enterLog($user, $_SERVER['REMOTE_ADDR']);
    // set the session number to last log entry number
    $_SESSION['sessionNo'] = Helper::getLastLogNo($user);
    // reset the limit_bad counter
    Helper::resetBadLogin($user);
    Helper::resetBadUnlock($user);
}

TLPA::RFIN();
?>