<?php
/**
 * example file for how to handle the authetication links
 */
if (isset($_GET['otp'])) {
    $db = new PDO('mysql:host=localhost;dbname=tlpa;charset=utf8', 'user', 'password');
    $state = $db->prepare("SELECT user, expire FROM auth WHERE otp = :otp");
    $state->execute(array(':otp' => $_GET['otp']));
    $res = $state->fetch(PDO::FETCH_ASSOC);
    if (!$res) {
        //no entry found
        die('<h1>No Authentication Found</h1>');
    }
    if ($res['expire'] > date('Y-m-d H:i:s', time())) {
        echo '<h1>' . $res['user'] . ' has logged in !</h1>';
    } else {
        echo '<h1>Authetication has expired!</h1>';
    }
    $state = $db->prepare("DELETE FROM auth WHERE user = :user AND otp = :otp");
    $state->execute(array(':user' => $res['user'], ':otp' => $_GET['otp']));
} else {
    echo '<h1>You are not logged in!</h1>';
}
?>
