<?php

/**
 * handles the recovery process, gets the necessary information for a given stage
 */
require_once('core/TLPA.class.php');
require_once('core/Helper.class.php');


if (!TLPA::OPENSESS($_POST['sessionId'], $_POST['sessionSecret'])) {
    TLPA::FD('6');
}

TLPA::RSET('recStage', $_SESSION['recStage']);

switch ($_SESSION['recStage']) {
    case 0:
        //sec_q & sec_a
        TLPA::RSET('stageKnowledge', Helper::getSecQ($_SESSION['user']));
        break;
    case 1:
        //kf
        TLPA::RSET('stageKnowledge', NULL);
        break;
    case 2:
        //otp
        //check if user is requesting otp fpr the first time
        if (isset($_SESSION['oTP']) && isset($_SESSION['oTP_expire'])) {
            //remind the client of timeout
            TLPA::RSET('stageKnowledge', $_SESSION['oTP_expire']);
        } else {
            //first time, the user requests otp
            $otp = Helper::genOTP();
            $_SESSION['oTP'] = TLPA::SECHASH($otp, Helper::getSalt($_SESSION['user']));
            $val_otp = TLPA::GETSEC()['val_otp'];
            $timeout = (time() + 60 * $val_otp);
            $_SESSION['oTP_expire'] = $timeout;
            Helper::sendOTP($_SESSION['user'], $otp);

            //tell expiration date to the client
            TLPA::RSET('stageKnowledge', $timeout);
        }
        break;
}

TLPA::RFIN();
?>