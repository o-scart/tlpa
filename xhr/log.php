<?php

/**
 * gets log data for a given user
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

// get the data from log databases
TLPA::RSET('log', Helper::getLogData($_SESSION['user']));
TLPA::RSET('bad_log', Helper::getBadLogData($_SESSION['user']));

TLPA::RFIN();
?>
