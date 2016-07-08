<?php

/**
 * creates one-time-password links for the master-page-session
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

// clean up the auth table
Helper::cleanAuth(date('Y-m-d H:i:s', time()));

// make new entry
$exp = date('Y-m-d H:i:s', time() + 60 * TLPA::GETSEC()['val_auth']);
$otp = Helper::genOTP();
Helper::enterAuth($_SESSION['user'], $otp, $exp);

// create link
$link = rawurldecode(TLPA::GETSEC()['url_auth']) . '?otp=' . $otp;

// output link
TLPA::RSET('otp_auth', $link);

TLPA::RFIN();
?>