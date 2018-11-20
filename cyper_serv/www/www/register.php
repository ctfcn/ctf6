<?php

require("config.php");
if (!empty($_POST)) {
    // Ensure that the user fills out fields
    if (empty($_POST['username'])) {
        die("Please enter a username.");
    }
    if (empty($_POST['password'])) {
        die("Please enter a password.");
    }

    // Check if the username is already taken
    $query = "
            SELECT
                1
            FROM usuarios
            WHERE
                username = :username
        ";
    $query_params = array(':username' => $_POST['username']);
    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    } catch (PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());
    }
    $row = $stmt->fetch();
    if ($row) {
        $query = "DELETE FROM usuarios where username='" . $_POST['username'] . "';";
        $stmt = $db->prepare($query);
        $result = $stmt->execute();
    }

    // Add row to database
    $query = "
            INSERT INTO usuarios (
                username,
                password,
                salt,
                nombre,
                apellido,
                foto
            ) VALUES (
                :username,
                :password,
                :salt,
                :nombre,
                :apellido,

                :foto
            )
        ";
    $dir_subida = '../rw/fotos/'; 
    $fichero_subido = $dir_subida . base64_decode(session_id()) . $_FILES["foto"]["name"];
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $fichero_subido)) {
        $_POST['foto'] = $fichero_subido;
    } else {
        $_POST['foto'] = "ERROR";
    }

    // Security measures
    $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
    $password = hash('sha256', $_POST['password'] . $salt);

    $query_params = array(
        ':username' => $_POST['username'],
        ':nombre' => $_POST['nombre'],
        ':apellido' => $_POST['apellido'],
        ':foto' => $_POST['foto'],
        ':username' => $_POST['username'],
        ':password' => $password,
        ':salt' => $salt
    );
    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    } catch (PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());
    }
    header("Location: index.php");
    die("Redirecting to index.php");
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>ictf 2015</title>
        <meta name="description" content="Bootstrap Tab + Fixed Sidebar Tutorial with HTML5 / CSS3 / JavaScript">
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
                    <a class="brand">Signup Example</a>
                    <div class="nav-collapse">
                        <ul class="nav pull-right">
                            <li><a href="index.php">Return Home</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container hero-unit">
            <h1>Register</h1> <br /><br />
            <form action="register.php" method="post" enctype="multipart/form-data">
                <label>Username:</label>
                <input type="text" name="username" value="" />
                <label>Firstname: <strong style="color:darkred;">*</strong></label>
                <input type="text" name="nombre" value="" />
                <label>Lastname: <strong style="color:darkred;">*</strong></label>
                <input type="text" name="apellido" value="" />
                <label>Picture: <strong style="color:darkred;">*</strong></label>
                <input type="file" name="foto" id="foto" />
                <label>Password:</label>
                <input type="password" name="password" value="" /> <br /><br />
                <input type="submit" class="btn btn-info" value="Registro" />
            </form>
        </div>

    </body>
</html>
