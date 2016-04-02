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

if (isset($_POST['ccnum'], $_POST['ccmonth'], $_POST['ccyear'], $_POST['cccode'])) {
	header('Location: /?status=ok');
	exit;
}

if (isset($_POST['name'], $_POST['job'], $_POST['comment'])) {
	$comments[] = [
		'avatar' => 'michals.png',
		'name' => $_POST['name'],
		'job' => $_POST['job'],
		'comment' => $_POST['comment'],
	];
	file_put_contents($commentsFile, json_encode($comments, JSON_PRETTY_PRINT), LOCK_EX);
	header('Location: /');
	exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>Pokladny Pokl.cz</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="i/screen.css" type="text/css">
</head>
<body>
<div id="container">
	<div id="logo"><img src="i/logo.png"></div>
	<h1 id="header">Pokladny pro podnikatele</h1>
	<div id="desc"><p>Kupte si naši spolehlivou pokladnu, sníží administrativní zátěž a zrychlí prodej. Kupte dvě a dostane mapu pokladu zcela zdarma! Objednávejte ještě dnes.</p></div>
	<div id="cc">
<?php
if (isset($_GET['status']) && $_GET['status'] === 'ok') {
?>
		<p><strong>Děkujeme za platbu!</strong></p>
		<p><a href="/">Koupit další</a></p>
<?php
} else {
?>
		<form action="" method="post">
			<p><label>Číslo karty:</label><input type="text" name="ccnum" class="mediumRare"></p>
			<p><label>Expirace:</label><input type="text" name="ccmonth" placeholder="MM" class="short"><input type="text" name="ccyear" placeholder="RR" class="short"></p>
			<p><label>Kontrolní kód:</label><input type="text" name="cccode" class="medium"></p>
			<p><label>🔒</label><input type="submit" value="Zaplatit"></p>
		</form>
<?php
}
?>
	</div>
	<div class="clear"></div>
	<h3>Komentáře zákazníků</h3>
<?php
foreach (array_merge($commentsInitial, $comments) as $comment) {
	printf('<div class="avatar"><img src="i/%s"></div><div class="comment"><strong>%s</strong> (%s)</p><p>%s</p></div><hr>',
		htmlspecialchars($comment->avatar),
		htmlspecialchars($comment->name),
		htmlspecialchars($comment->job),
		htmlspecialchars($comment->comment)
	);
}

if ($signedIn) {
?>
	<h3>Přidejte komentář</h3>
	<div id="add">
		<form action="" method="post">
			<p><label>Jméno:</label><input type="text" name="name"></p>
			<p><label>Pozice:</label><input type="text" name="job"></p>
			<p><label>Komentář:</label><textarea name="comment"></textarea></p>
			<p><label>Děkujeme!</label><input type="submit" value="Přidat komentář" class="short"></p>
		</form>
	</div>
<?php
} else {
	echo '<p><em>Pro přidání komentáře se prosím přihlaste</em></p>';
}
?>
<p><hr><small>Provozuje <a href="https://www.michalspacek.cz/">Michal Špaček</a>, <a href="https://twitter.com/spazef0rze">@spazef0rze</a>. Tento web slouží pouze ke studijním účelům, nenabádám k trestné činnosti.</small></p>
</div>
</body>
</html>
