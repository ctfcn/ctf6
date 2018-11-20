<?php
$note_id = filter_input(INPUT_GET, 'note_id', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9a-f]+$/D'))) or die('note_id?');
$token = filter_input(INPUT_GET, 'token', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9a-f]+$/D'))) or die('token?');


# This is your (only!) writeable directory. Store flags here.
$mydir = '/opt/ctf/note_manager/rw/';


$f = fopen($mydir.$note_id, 'r') or die("fopen");
$data = fgetcsv($f) or die("fgetcsv");
fclose($f) or die("fclose");
?>

<!DOCTYPE html>
<html>
<body>
<?php if (strcmp($token, $data[3]) == 0) { ?>
    <p>Your note was: <?= $data[0]; ?></p>
    <p>Token was: <?= $token; ?></p>
<?php } else { ?>
    <p>Incorrect token</p>
    <p>Token was: <?= $token; ?></p>
<?php } ?>
</body>
</html>
