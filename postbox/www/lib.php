<?php
require_once('config.php');
session_start();

function _db($query){
	return (new SimpleXMLElement(file_get_contents(DB_PATH)))->xpath($query);
}

function get_user($username, $password) {
	$hashed_password = hash('md5', $password . SALT);
	$users = _db("/database/users/user[@username='$username' and @password='$hashed_password']");
	if (count($users) > 0) {
		return $users[0];
	}
	else {
		return null;
	}
}

function get_posts() {
    global $ALLOWED_IPS;
	$posts = array();
	$perm = $_SESSION['perm'];

	foreach (_db("/database/posts/post") as $post) {
		$_perm = (string)$post['perm'];

		$_post = [
			'author' => (string)$post['author'],
            'title' => (string)$post['title'],
            'password' => (string)$post['password'],
			'desc' => (string)$post['desc']
		];

		if ($perm === 'admin') {
			if ($_perm === 'admin') {
				array_push($posts, $_post);
			}
			else {
				array_push($posts, $_post);
			}
		}
		else if ($perm === $_perm) array_push($posts, $_post);
	}

	return array_reverse($posts);
}

function logged_in() {
	return !empty($_SESSION['username']);
}

function post($title, $password, $desc) {
	$root = new SimpleXMLElement(file_get_contents(DB_PATH));
	$node = $root->posts->addChild('post');
	$node->addAttribute('author', $_SESSION['username']);
	$node->addAttribute('desc', $desc);
    $node->addAttribute('title', $title);
    $node->addAttribute('password', $password);
	$node->addAttribute('perm', $_SESSION['perm']);
	$root->saveXML(DB_PATH);
}

function stupid_hash($content) {
    $h = "";
    $array = str_split($content);
    $char = $content[0];
    $h_byt = chr(((ord($char) >> 3) | (ord($char) << 5)) & 0xff);
    $h = $h . $h_byt;
    foreach ($array as $char) {
        $h_byt_ = chr(((ord($char) >> 3) | (ord($char) << 5)) & 0xff);
        $h = $h . $h_byt;
    }
    return $h;
}

