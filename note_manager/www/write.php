<?php
$password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\w+$/D'))) or die('password?');
$content = filter_input(INPUT_POST, 'content', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\w+$/D'))) or die('content?');


# This is your (only!) writeable directory. Store flags here.
$mydir = '/opt/ctf/note_manager/rw/';

date_default_timezone_set("Europe/London");

# In this example, we create a new (randomly-named) file for each flag.
$somerand = openssl_random_pseudo_bytes(8) or die("random");
$note_id = bin2hex($somerand);

$date = date("m/d/Y H:i:s");
$noteid_dateseconds = $note_id . strtotime($date);

$f = fopen($mydir.$note_id, 'x') or die("fopen");
fputcsv($f, array($content, password_hash($password, PASSWORD_DEFAULT), $date, bin2hex($noteid_dateseconds))) or die("fputcsv");
fclose($f) or die("fclose");
?>

<!DOCTYPE html>
<html>
<body>
    <p>Your note is safe with us! You can retrieve it with your password and this note ID: <?= $note_id; ?><br />Date/time (UK) saved: <?php echo $date; ?></p>
</body>
</html>
