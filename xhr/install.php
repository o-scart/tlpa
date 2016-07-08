<!DOCTYPE html>
<html>
    <head>
        <title>TLPA</title>
    </head>
    <style type="text/css">
        h1{
            text-align:center;
            text-decoration: underline;
        }
        form{
            font-size:14px;
        }
        label{
            display:block;
            position:relative;	
        }
        input{
            max-width:350px;
            min-width:20%;
            left: 15em;	
            position: absolute;
        }
        legend{
            padding:10px;
            text-align:center;
            text-decoration:10px;
        }
        fieldset{
            background:#EEEEEE;
            margin:10px 10px;	
        }
    </style>
</head>
<body>
    <h1>Installation & Configuration Script</h1>
    <form method="post" action="install.php">
        <fieldset>
            <legend>Database Information</legend>
            <label for="MySQL Host">MySQL Host:
                <input type="text" name="dbhost"><br></label>
            <label for="Database Name">Database Name:
                <input type="text" name="dbname"><br></label>
            <label for="User">User:
                <input type="text" name="dbuser"><br> </label>
            <label for="Password">Password:
                <input type="text" name="dbpw"><br> </label>
        </fieldset>
        <fieldset>
            <legend>Email Information</legend>
            <label for="SMTP Host">SMTP Host:
                <input type="text" name="mhost"><br></label>
            <label for="MUser">User:
                <input type="text" name="muser"><br></label>
            <label for="Password">Password:
                <input type="text" name="mpw"><br> </label>
            <label for="Encoding">Encoding:
                <input type="text" name="menc"><br></label>
            <label for="Port">Port:
                <input type="text" name="mport"><br></label>
            <label for="Email Adress">Email Address:
                <input type="text" name="maddr"><br></label>
            <label for="Name">Name:
                <input type="text" name="mname"></label>
        </fieldset>   
        <fieldset>
            <legend>TLPA Settings</legend>
            <label for="Session Time">Session Time:
                <input type="text" name="session"><br></label>
            <label for="Limit Bad Logins">Limit Bad Logins:
                <input type="text" name="lim_bad_log"><br></label>
            <label for="Limit Bad Unlocks">Limit Bad Unlocks:
                <input type="text" name="lim_bad_unl"><br></label>
            <label for="Valid Time of OTP">Valid Time of OTP:
                <input type="text" name="val_otp"></label>
            <label for="Valid Time of Auth-Link">Valid Time of Auth-Link:
                <input type="text" name="val_auth"></label>
            <label for="URL">URL:
                <input type="text" name="url"></label>
            <label for="Auth-URL">Auth-URL:
                <input type="text" name="url_auth"></label>
            <label for="Server Salt">Server Salt:
                <input type="text" name="salt"></label>
        </fieldset>
        <fieldset>
            <legend>HTACCESS</legend>
            <label for="User">User:
                <input type="text" name="htuser"><br> </label>
            <label for="Password">Password:
                <input type="text" name="htpw"><br> </label>
        </fieldset>
        <input type="submit" value="Install" name="submit">
    </form>
</body>
</html>
<?php
// ----------------------------------------------------------------------------------------------------------

require_once '/core/phpmailer/class.smtp.php';

/**
 * checks if not null and string
 * @param string $string
 * @return string or die
 */
function chAndRetString($string) {
    if (isset($string) && is_string($string)) {
        return $string;
    } else {
        die('Invalid Input!');
    }
}

/**
 * checks if not null, string and valid email
 * @param string $mail
 * @return string or die
 */
function chAndRetEmail($mail) {
    if (isset($mail) && is_string($mail) && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        return $mail;
    } else {
        die('Invalid Mail!');
    }
}

/**
 * checks if not null, string and encodes as URL
 * @param string $url
 * @return string
 */
function chAndRetURL($url) {
    if (isset($url) && is_string($url)) {
        return rawurlencode($url);
    } else {
        die('Invalid URL!');
    }
}

/**
 * checks if not null, numeric and greater than zero
 * @param string $num
 * @return int or die
 */
function chAndRetInteger($num) {
    if (isset($num) && ctype_digit($num) && (abs($num) > 0)) {
        return abs($num);
    } else {
        die('Not A Number or zero!');
    }
}

/**
 * checks if not null, numeric and smaller than 8
 * @param string $num
 * @return int or die
 */
function chAndRetTinyInt($num) {
    if (isset($num) && ctype_digit($num) && (abs($num) < 8) && (abs($num) > 0)) {
        return abs($num);
    } else {
        die('Not A Number or value not between 1 and 7!');
    }
}

/**
 * checks if not null, string matching tls or empty
 * @param string $enc
 * @return string or die
 */
function chAndRetEnc($enc) {
    if (isset($enc) && is_string($enc) && (($enc == 'tls') | ($enc == ''))) {
        return $enc;
    } else {
        die('Invalid Encryption! Leave blank or enter "tls"');
    }
}

/**
 * Creates a connection to the DB using PDOs
 * @param string $dbhost
 * @param string $dbname
 * @param string $dbuser
 * @param string $dbpw
 * @return PDO connection to the DB or die
 */
function connectDB($dbhost, $dbname, $dbuser, $dbpw) {
    $dbcon = NULL;
    try {
        $dbcon = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname . ';charset=utf8', $dbuser, $dbpw);
    } catch (PDOException $e) {
        die('DB Connection failed: ' . $e->getMessage());
    }
    return $dbcon;
}

/**
 * Creates tables needed for the TLPA-System and checks if succeded IF NOT EXISTS
 * @param PDO $dbcon
 */
function createTables($dbcon) {
    $table_users = "CREATE TABLE IF NOT EXISTS users (user varchar(200) NOT NULL, mail varchar(250) NOT NULL, password varchar(128) NOT NULL DEFAULT 'placeholder', sec_question varchar(250) NOT NULL, sec_answer varchar(128) NOT NULL, keyfile varchar(128) NOT NULL, color_code varchar(128) NOT NULL DEFAULT 'placeholder', salt varchar(128) NOT NULL, no_bad_logins tinyint(3) unsigned NOT NULL DEFAULT '0', no_bad_unlocks tinyint(3) unsigned NOT NULL DEFAULT '0', PRIMARY KEY (user)) ENGINE=InnoDB DEFAULT CHARSET='latin1' COLLATE='latin1_swedish_ci';";
    $table_log = "CREATE TABLE IF NOT EXISTS log (user varchar(200) NOT NULL, no serial NOT NULL, login datetime NOT NULL, logout datetime NOT NULL, ip varchar(46) NOT NULL, PRIMARY KEY (user, no), FOREIGN KEY (user) REFERENCES users(user) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET='latin1' COLLATE='latin1_swedish_ci';";
    $table_bad_log = "CREATE TABLE IF NOT EXISTS bad_log (user varchar(200) NOT NULL, no serial NOT NULL, event_time datetime NOT NULL, ip varchar(46) NOT NULL, PRIMARY KEY (user, no), FOREIGN KEY (user) REFERENCES users(user) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET='latin1' COLLATE='latin1_swedish_ci';";
    $table_auth = "CREATE TABLE IF NOT EXISTS auth (user varchar(200) NOT NULL, otp varchar(128) NOT NULL, expire datetime NOT NULL, PRIMARY KEY (user, otp), FOREIGN KEY (user) REFERENCES users(user) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET='latin1' COLLATE='latin1_swedish_ci';";
    $state = $dbcon->prepare($table_users);
    $ok_users = $state->execute();
    $state = $dbcon->prepare($table_log);
    $ok_log = $state->execute();
    $state = $dbcon->prepare($table_bad_log);
    $ok_bad_log = $state->execute();
    $state = $dbcon->prepare($table_auth);
    $ok_auth = $state->execute();
    if (!($ok_users && $ok_log && $ok_bad_log && $ok_auth)) {
        die('Table creation failed');
    }
}

/**
 * checks, if SMTP connection works
 * @param string $mhost
 * @param string $mport
 * @param string $muser
 * @param string $mpw
 */
function connectSMTP($mhost, $mport, $muser, $mpw) {
    $error = false;
    //Create a new SMTP instance
    $smtp = new SMTP;
    try {
        //Connect to an SMTP server
        if (!$smtp->connect($mhost, $mport)) {
            $error = true;
            echo "Connect failed<br>";
        }
        //Say hello
        if (!$smtp->hello(gethostname())) {
            $error = true;
            echo 'EHLO failed: ' . $smtp->getError()['error'] . "<br>";
        }
        //Get the list of ESMTP services the server offers
        $e = $smtp->getServerExtList();
        //If server can do TLS encryption, use it
        if (array_key_exists('STARTTLS', $e)) {
            $tlsok = $smtp->startTLS();
            if (!$tlsok) {
                $error = true;
                echo 'Failed to start encryption: ' . $smtp->getError()['error'] . "<br>";
            }
            //Repeat EHLO after STARTTLS
            if (!$smtp->hello(gethostname())) {
                $error = true;
                echo 'EHLO (2) failed: ' . $smtp->getError()['error'] . "<br>";
            }
            //Get new capabilities list, which will usually now include AUTH if it didn't before
            $e = $smtp->getServerExtList();
        }
        //If server supports authentication, do it (even if no encryption)
        if (array_key_exists('AUTH', $e)) {
            if (!$smtp->authenticate($muser, $mpw)) {
                $error = true;
                echo 'Authentication failed: ' . $smtp->getError()['error'] . "<br>";
            }
        }
    } catch (Exception $e) {
        echo 'SMTP error: ' . $e->getMessage() . "<br>";
    }
    //Whatever happened, close the connection.
    $smtp->quit(true);

    //if error ocurred, die
    if ($error) {
        die('SMTP Connection failed!');
    }
}

/**
 * crates htaccess file that protects install.php from access
 * @param string $htuser
 * @param string $htpw
 */
function createHTA($htuser, $htpw) {
    $htaccess = "AuthType Basic\n"
            . "AuthName " . '"You Shall Not Pass!"' . "\n"
            . "AuthUserFile " . __DIR__ . '/.htpasswd' . "\n"
            . '<Files "install.php">' . "\n"
            . "Require valid-user\n"
            . '</Files>';
    $htpasswd = $htuser . ':' . $htpw;
    if (!(file_put_contents('.htaccess', $htaccess) && file_put_contents('.htpasswd', $htpasswd))) {
        die('Could not write .htaccess file!');
    }
}

/**
 * establishes a DB connection, creates tables and saves all data in /xhr/core/sec/config.jcon.php
 */
function install() {
    // get all entries and check them
    $dbhost = chAndRetString($_POST['dbhost']);
    $dbname = chAndRetString($_POST['dbname']);
    $dbuser = chAndRetString($_POST['dbuser']);
    $dbpw = chAndRetString($_POST['dbpw']);

    $mhost = chAndRetString($_POST['mhost']);
    $muser = chAndRetString($_POST['muser']);
    $mpw = chAndRetString($_POST['mpw']);
    $menc = chAndRetEnc($_POST['menc']);
    $mport = chAndRetInteger($_POST['mport']);
    $maddr = chAndRetEmail($_POST['maddr']);
    $mname = chAndRetString($_POST['mname']);

    $session = chAndRetInteger($_POST['session']);
    $lim_bad_log = chAndRetTinyInt($_POST['lim_bad_log']);
    $lim_bad_unl = chAndRetTinyInt($_POST['lim_bad_unl']);
    $val_otp = chAndRetInteger($_POST['val_otp']);
    $val_auth = chAndRetInteger($_POST['val_auth']);
    $url = chAndRetURL($_POST['url']);
    $url_auth = chAndRetURL($_POST['url_auth']);
    $salt = chAndRetString($_POST['salt']);

    $htuser = chAndRetString($_POST['htuser']);
    $htpw = chAndRetString($_POST['htpw']);

    // try a DB connection
    $dbcon = connectDB($dbhost, $dbname, $dbuser, $dbpw);
    echo "DB Connection succsessful!<br>";

    // try a SMTP connection
    connectSMTP($mhost, $mport, $muser, $mpw);
    echo "SMTP Connection succsessful!<br>";

    // create tables
    createTables($dbcon);
    echo "Table creation succsessful!<br>";

    // store everything
    $db = array('host' => $dbhost, 'dbname' => $dbname, 'dbuser' => $dbuser, 'pw' => $dbpw);
    $sec = array('session' => $session, 'lim_bad_log' => $lim_bad_log, 'lim_bad_unl' => $lim_bad_unl, 'val_otp' => $val_otp, 'val_auth' => $val_auth, 'url' => $url, 'url_auth' => $url_auth, 'salt' => $salt);
    $mail = array('host' => $mhost, 'user' => $muser, 'pw' => $mpw, 'enc' => $menc, 'port' => $mport, 'addr' => $maddr, 'name' => $mname);
    $config = array('db' => $db, 'sec' => $sec, 'mail' => $mail);
    if (!file_put_contents('./core/sec/config.json.php', json_encode($config))) {
        die('Could not write config file!');
    }
    echo "Config written succsessfully!<br>";

    // create .htaccess
    createHTA($htuser, $htpw);
    echo "htaccess written succsessfully!<br>";

    // complete
    echo "Installation complete!";
}

// do stuff
if (isset($_POST['submit'])) {
    install();
}
?> 