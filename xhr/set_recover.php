<?php

/**
 * sets the recovery information for a given stage
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('core/TLPA.class.php');
require_once('core/Helper.class.php');

if (!TLPA::OPENSESS($_POST['sessionId'], $_POST['sessionSecret'])) {
    TLPA::FD('6');
}

if ($_SESSION['authStage'] < 3) {
    TLPA::FD('9');
}

$user = $_SESSION['user'];

switch ($_POST['setRecovery']) {
    case 0:
        //mail
        $mail = $_POST['mail'];
        if (Helper::valStringNotEmpty($mail)) {
            $mail = Helper::sanEmail($mail);
        }
        if (!Helper::valEmail($mail)) {
            TLPA::FD('8');
        }
        Helper::setMail($user, $mail);
        break;
    case 1:
        //sec_q & sec_a
        $sec_q = $_POST['secQuestion'];
        $sec_a = $_POST['secAnswer'];
        if (Helper::valStringNotEmpty($sec_q) && Helper::valStringLen($sec_a, 128)) {
            $sec_q = Helper::sanString($sec_q);
        } else {
            TLPA::FD('8');
        }
        if ((strlen($sec_q) == 0)) {
            TLPA::FD('8');
        }
        Helper::setSecQA($user, $sec_q, $sec_a);
        break;
    case 2:
        //keyfile
        $kf = Helper::genKF();
        Helper::setKF($user, $kf);
        Helper::sendKF($user, $kf);
        break;
    default: TLPA::FD('8');
}

TLPA::RFIN();
?>

