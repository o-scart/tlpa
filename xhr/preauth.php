<?php

/**
 * handles the authentication process, gets the necessary information for a given stage
 */
require_once('core/TLPA.class.php');
require_once('core/Helper.class.php');

if (!TLPA::OPENSESS($_POST['sessionId'], $_POST['sessionSecret'])) {
    TLPA::FD('6');
}

TLPA::RSET('authStage', $_SESSION['authStage']);

switch ($_SESSION['authStage']) {
    case 0:
        //password
        TLPA::RSET('stageKnowledge', NULL);
        break;
    case 1:
        //colourset
        TLPA::RSET('stageKnowledge', NULL);
        break;
    case 2:
        //picture
        TLPA::RSET('stageKnowledge', Helper::getImage($_SESSION['user']));
        break;
}

TLPA::RFIN();
?>