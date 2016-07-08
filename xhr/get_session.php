<?php

/**
 * opens a new session, checks, if user in database and if any lock is set on user 
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('core/TLPA.class.php');
require_once('core/Helper.class.php');

$user = strtolower($_POST['user']);

if (!Helper::userExists($user)) {
    // user doesnt exist
    TLPA::FD('2');
}

if (Helper::issetPermlock($user)) {
    // user permanently locked
    TLPA::FD('4');
}

$sessId = TLPA::NEWSESS();

$_SESSION['user'] = $user;

TLPA::RSET('sessionId', $sessId);
TLPA::RSET('sessionSecret', $_SESSION['secret']);

if (Helper::issetTempLock($user)) {
    // user temporarily locked, create and send unlock link
    $token = Helper::genToken();
    $link = rawurldecode(TLPA::GETSEC()['url']) . '#unlock#' . $sessId . '#' . $_SESSION['secret'] . '#' . $token;

    Helper::sendLink($user, $link);

    $_SESSION['tokenHash'] = TLPA::SECHASH($token, Helper::getSalt($user));

    TLPA::FD('3');
}

TLPA::RFIN();
?>