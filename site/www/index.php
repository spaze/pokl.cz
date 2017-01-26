<?php
require_once './prologue.php';

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
<html lang="<?= $l('cs') ?>">
<head>
    <meta charset="utf-8">
	<title><?= $l('Pokladny Pokl.cz') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="i/screen.css" type="text/css">
</head>
<body>
<div id="container">
	<div id="logo"><img src="i/logo.png"></div>
	<h1 id="header"><?= $l('Pokladny pro podnikatele') ?></h1>
	<div id="desc"><p><?= $l('Kupte si naši spolehlivou pokladnu, sníží administrativní zátěž a zrychlí prodej. Kupte dvě a dostanete mapu pokladu zcela zdarma! Objednávejte ještě dnes.') ?></p></div>
	<div id="cc">
<?php
if (isset($_GET['status']) && $_GET['status'] === 'ok') {
?>
		<p><strong><?= $l('Děkujeme za platbu!') ?></strong></p>
		<p><a href="/"><?= $l('Koupit další') ?></a></p>
<?php
} else {
?>
		<form action="" method="post">
			<p><label><?= $l('Číslo karty:') ?></label><input type="text" name="ccnum" class="mediumRare"></p>
			<p><label><?= $l('Expirace:') ?></label><input type="text" name="ccmonth" placeholder="<?= $l('MM') ?>" class="short"><input type="text" name="ccyear" placeholder="<?= $l('RR') ?>" class="short"></p>
			<p><label><?= $l('Kontrolní kód:') ?></label><input type="text" name="cccode" class="medium"></p>
			<p><label>🔒</label><input type="submit" value="<?= $l('Zaplatit') ?>"></p>
		</form>
<?php
}
?>
	</div>
	<div class="clear"></div>
	<h3><?= $l('Komentáře zákazníků') ?></h3>
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
	<h3><?= $l('Přidejte komentář') ?></h3>
	<div id="add">
		<form action="" method="post">
			<p><label><?= $l('Jméno:') ?></label><input type="text" name="name"></p>
			<p><label><?= $l('Pozice:') ?></label><input type="text" name="job"></p>
			<p><label><?= $l('Komentář:') ?></label><textarea name="comment"></textarea></p>
			<p><label><?= $l('Děkujeme!') ?></label><input type="submit" value="<?= $l('Přidat komentář') ?>" class="short"></p>
		</form>
	</div>
<?php
} else {
	echo '<p><em>' . $l('Pro přidání komentáře se prosím přihlaste') . '</em></p>';
}
?>
<p><small><?= $l('Provozuje') ?> <a href="https://www.michalspacek.cz/">Michal Špaček</a>, <a href="https://twitter.com/spazef0rze">@spazef0rze</a>. <?= $l('Tento web slouží pouze ke studijním účelům, nenabádám k trestné činnosti.') ?></small></p>
</div>
</body>
</html>
