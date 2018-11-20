<?php

require("config.php");
$registered=0;
$repeated=0;
if (!empty($_POST)) {

    // Ensure that the user fills out fields
    if (empty($_POST['flag_id'])) {
        die("Please enter a flag id.");
    }
    if (empty($_POST['flag_content'])) {
        die("Please enter a flag content.");
    }

    // Check if the flag_id is already register

    $query = "SELECT * from flags where flag_id='".$_POST['flag_id']."';";

    try {
        $stmt = $flags_db->prepare($query);
        $result = $stmt->execute();
        } catch (PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());
    }
    $row = $stmt->fetch();


    if (($row)) {
			$repeated=1;
	}
    else{
			$row['flag_id']=$_POST["flag_id"];
			$row['flag_token']=$_POST["password"];
			$row['flag_content']=$_POST["flag_content"];
			$row['time']=time();

    // Add row to database
    $query = "
            INSERT INTO flags (
                flag_id,
                flag_token,
                flag_content,
                time
            ) VALUES (
                :flag_id,
                :flag_token,
                :flag_content,
                :time
            )
        ";

    //Voy por aca

		$dir_subida = '../rw/fotos/';
    #$dir_subida = '/opt/repo/sample_web/service/rw/fotos/';

    $fichero_subido = $dir_subida . session_id() . $_FILES["foto"]["name"];

    $query_params = array(
        ':flag_id' => $row['flag_id'],
        ':flag_token' => $row['flag_token'] ,
        ':flag_content' => $row['flag_content'],
        ':time' => $row['time']
    );
    try {
        $stmt = $flags_db->prepare($query);
        $result = $stmt->execute($query_params);
    } catch (PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());

    }

    $_SESSION['user'] = $row;

    $registered=1;

}
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>ictf 2015</title>
        <meta name="description" content="FLAGSET">
        <meta name="author" content="Untame.net">

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="assets/bootstrap.min.js"></script>
        <link href="assets/bootstrap.min.css" rel="stylesheet" media="screen">
        <style type="text/css">
            body { background: url(assets/bglight.png); }
            .hero-unit { background-color: #fff; }
            .center { display: block; margin: 0 auto; }
        </style>
    </head>

    <body>

        <div class="navbar navbar-fixed-top navbar-inverse">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand">FLAG SUBMIT FORM</a>
                    <div class="nav-collapse">
                        <ul class="nav pull-right">
                            <li><a href="index.php">Return Home</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

<?php if ($registered) {

        	echo '<div class="container hero-unit">';
		echo "<h1>You have register a FLAG </h1> <br /><br />";
		echo '<h1>FLAG_ID: '.$row['flag_id'].'</h1>';
        	echo '</div>';

		}
else{ echo
        '<div class="container hero-unit">
            <h1>Register a FLAG </h1> <br /><br />';
	if ($repeated){	echo '<h1>FAILED!</h1><BR><p> FLAG_ID: '.$row['flag_id'].' already registered<BR>';}
	echo'
            <form action="registerFlag.php" method="post" enctype="multipart/form-data">
                <label>FLAG_ID:</label>
                <input type="text" name="flag_id" value="" />
                <label>FLAG_CONTENT: <strong style="color:darkred;">*</strong></label>
                <input type="text" name="flag_content" value="" />
                <label>Picture: <strong style="color:darkred;">*</strong></label>
                <input type="file" name="foto" id="foto" />
                <input type="submit" class="btn btn-info" value="Registro" />
            </form>
        </div>';
	}
        ?>
    </body>
</html>
