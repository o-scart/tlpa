<?php

/**
 * handles the unlock process for a temporary lock
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('core/TLPA.class.php');
require_once('core/Helper.class.php');

if (!TLPA::OPENSESS($_POST['sessionId'], $_POST['sessionSecret'])) {
    TLPA::FD('6');
}

// get the username und token
$user = $_SESSION['user'];
$token = $_POST['token'];

if (TLPA::SECHASH($token, Helper::getSalt($user)) == $_SESSION['tokenHash']) {
    // remove the temp lock and increase the unlock count
    Helper::resetBadLogin($user);
    Helper::incBadUnlock($user);
} else {
    // invalid parameters
    TLPA::FD('8');
}

TLPA::RFIN();
?>