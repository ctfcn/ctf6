<?php
require_once('lib.php');

if (!logged_in()) {
	header('Location: login.php');
	die();
}

if(isset($_GET['logout'])){
	session_destroy();
	header('Location: index.php');
	die();
}

if (isset($_POST['title']) && isset($_POST['desc']) && isset($_POST['password'])) {
	post($_POST['title'], $_POST['password'], $_POST['desc']);
	header('Location: index.php');
	die();
}

$hashed_password = '';
if (isset($_GET['password'])) {
    $hashed_password = stupid_hash($_GET['password']);
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Postbox - Home</title>

    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/callout.css" rel="stylesheet">
  </head>

  <body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="#">Postbox</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="/index.php?logout">Logout</a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="container">
      <form class="form-group" action="index.php" method="post">
        <label for="title">Title</label>
        <input id="input-title" name="title" class="form-control"></input>
        <label for="password">Password (leave it empty if you do not need password protection)</label>
        <input id="input-password" name="password" type="password" class="form-control"></input>
        <label for="desc">Description</label>
        <textarea id="input-desc" name="desc" class="form-control"></textarea>
        <button class="btn btn-primary btn-lg btn-block" type="submit">Submit</button>
      </form>
<?php
      foreach(get_posts() as $post) {
?>
      <div class="bd-callout">
        <p style="font-size: 30px;"><?=$post['title']?></p>
<?php
        if ($_SESSION['perm'] !== 'admin' && /* Admin can read everything without a password */
            $post['password'] != '') {
?>
            <p style="color: red;">This post is password protected.</p>
<?php
            if (stupid_hash($post['password']) === $hashed_password) {
?>
            <p style="color: green;">Your password is correct.</p>
            <p class="desc"><?=$post['desc']?></p>
<?php
            } else {
?>
            <form class="form-group" action="index.php" method="get">
                <label for="post-password">Input a password to view this post</label>
                <input id="post-password" name="password" type="password" class="form-control"></input>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
<?php
            }
        }
        else {
?>
        <p class="desc"><?=$post['desc']?></p>
<?php
        }
?>
        <p align="right"><font color="gray">by <?=$post['author']?></font></p>
      </div>
<?php
      }
?>
    </main><!-- /.container -->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  </body>
</html>

