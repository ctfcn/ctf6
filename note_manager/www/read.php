<?php
$note_id = filter_input(INPUT_POST, 'note_id', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9a-f]+$/D'))) or die('note_id?');
$password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\w+$/D'))) or die('password?');


# This is your (only!) writeable directory. Store flags here.
$mydir = '/opt/ctf/note_manager/rw/';


$f = fopen($mydir.$note_id, 'r') or die("fopen");
$data = fgetcsv($f) or die("fgetcsv");
fclose($f) or die("fclose");

$token = "";

if (password_verify($password, $data[1]))
	$token = $data[3];

header('HTTP/1.1 302 Temporarily Moved');
header("Location: read2.php?note_id=$note_id&token=$token");
exit;
?>
