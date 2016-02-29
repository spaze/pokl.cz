<?php
$https = (!empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'off'));
$headers = array(
	'Server' => 'Clouditycloudycloud',
	'X-Powered-By' => 'Tenderloin, not the SF one',
	'X-Content-Type-Options' => 'nosniff',
	'X-XSS-Protection' => '1; mode=block',
	'X-Frame-Options' => 'deny',
);
if ($https) {
	$headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload';
}

foreach ($headers as $header => $value) {
	header("{$header}: {$value}");
}

// Redirect to secure
if (preg_match('/^(?:([^.]+\.))?([^.]+\.[^.]+)\z/', $_SERVER['HTTP_HOST'], $m)) {
	if ($m[1] !== '' || !$https) {
		$uri = $_SERVER['REQUEST_URI'];
		header("Location: https://{$m[2]}{$uri}", true, 301);
		exit;
	}
}

$dataDir = '../data/';
$cookieFile = $dataDir . 'cookie.json';
$commentsInitialFile = $dataDir . 'comments.initial.json';
$commentsFile = $dataDir . 'comments.json';

$cookie = json_decode(file_get_contents($cookieFile));
$signedIn = (isset($_COOKIE[$cookie->name]) && $_COOKIE[$cookie->name] === $cookie->value);
$commentsInitial = json_decode(file_get_contents($commentsInitialFile));
$comments = (json_decode(file_get_contents($commentsFile)) ?: []);

if (isset($_POST['cc'], $_POST['expiryMonth'], $_POST['expiryYear'], $_POST['code'])) {
	header('Location: /?status=ok');
	exit;
}

if (isset($_POST['name'], $_POST['job'], $_POST['comment'])) {
	$comments[] = [
		'name' => $_POST['name'],
		'job' => $_POST['job'],
		'comment' => $_POST['comment'],
	];
	file_put_contents($commentsFile, json_encode($comments, JSON_PRETTY_PRINT), LOCK_EX);
	header('Location: /');
	exit;
}

if (isset($_GET['status']) && $_GET['status'] === 'ok') {
?>
	<p><strong>Děkujeme za platbu!</strong></p>
	<p><a href="/">Koupit další</a></p>
	<hr>
<?php
} else {
?>
	<form action="" method="post">
		<p><input type="text" name="cc"></p>
		<p><input type="text" name="expiryMonth"><input type="text" name="expiryYear"></p>
		<p><input type="text" name="code"></p>
		<p><input type="submit"></p>
	</form>
	<hr>
<?php
}

foreach (array_merge($commentsInitial, $comments) as $comment) {
	echo "<p><strong>{$comment->name}</strong> ({$comment->job})</p>";
	printf('<p>%s</p><hr>', htmlspecialchars($comment->comment));
}

if ($signedIn) {
?>
	<form action="" method="post">
		<p><input type="text" name="name"></p>
		<p><input type="text" name="job"></p>
		<p><textarea name="comment"></textarea></p>
		<p><input type="submit"></p>
	</form>
<?php
} else {
	echo '<em>Pro přidání komentáře se prosím přihlaste</em>';
}