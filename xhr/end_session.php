<?php

/**
 * terminates the session and updates the logout time, if a user was logged in
 */
require_once('core/TLPA.class.php');
require_once('core/Helper.class.php');

if (!TLPA::OPENSESS($_POST['sessionId'], $_POST['sessionSecret'])) {
    TLPA::FD('6');
}

if (isset($_SESSION['user']) && isset($_SESSION['sessionNo'])) {
    Helper::updLogoutNow($_SESSION['user'], $_SESSION['sessionNo']);
}

session_unset();
session_destroy();

TLPA::RFIN();
?>