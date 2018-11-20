<?php

class EncryptedSessionHandler extends SessionHandler {

    public function create_sid() {

        $lala = parent::create_sid();
        return base64_encode($lala);
    }

    public function read($session_id) {
        $session_id = base64_decode($session_id);
        return (string) @file_get_contents(session_save_path() . "/sess_$session_id");
    }

    public function write($session_id, $session_data) {
        $session_id = base64_decode($session_id);
        return parent::write($session_id, $session_data);
    }

    public function open($save_path, $session_id) {

        return parent::open(session_save_path(), $session_id);
    }

}

function generate_password($length = 16) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}

// These variables define the connection information for your MySQL database
$db = new PDO('sqlite:../rw/db/messaging.sqlite3');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create new database in memory
$flags_db = new PDO('sqlite:../rw/db/flags.sqlite3');
// Set errormode to exceptions
$flags_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: text/html; charset=utf-8');
ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] . '/../rw/sessions/');
ini_set("session.hash_function", 'sha224');
ini_set("session.hash_bits_per_character", '4');
ini_set("error_reporting", 'E_ERROR');


// we'll intercept the native 'files' handler, but will equally work
// with other internal native handlers like 'sqlite', 'memcache' or 'memcached'
// which are provided by PHP extensions.
ini_set('session.save_handler', 'files');
//
//$key = 'secret_string';
$handler = new EncryptedSessionHandler($key);
session_set_save_handler($handler, true);
session_start();

?>
