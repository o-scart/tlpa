<?php

/**
 * sets the passwords for a given authentication stage
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
$authSecret = $_POST['authSecret'];

switch ($_POST['authStage']) {
    case 0:
        //password
        if (!Helper::valStringLen($authSecret, 128)) {
            TLPA::FD('8');
        }
        Helper::setPassword($user, $authSecret);
        break;
    case 1:
        //colourset
        if (!Helper::valStringLen($authSecret, 128)) {
            TLPA::FD('8');
        }
        Helper::setColor($user, $authSecret);
        break;
    case 2:
        //picture
        list($type, $argsArray['authSecret']) = explode(';', $_POST['authSecret']);
        list(, $_POST['authSecret']) = explode(',', $_POST['authSecret']);
        $_POST['authSecret'] = base64_decode($_POST['authSecret']);

        $path_to_image = "../uploads/" . $_SESSION['user'] . "/master.jpg";

        file_put_contents($path_to_image, $_POST['authSecret']);

        Helper::setImage($user, $path_to_image);
        break;

    default: TLPA::FD('8');
}

TLPA::RFIN();
?>