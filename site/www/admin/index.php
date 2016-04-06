<?php
require_once '../prologue.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>Admin Pokl.cz</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/i/screen.css" type="text/css">
</head>
<body>
<div id="container">
	<div id="logo"><img src="/i/logo.png"></div>
	<h1 id="header">Administrace Pokl.cz</h1>
	<div class="clear"></div>
	<h3>Komentáře zákazníků</h3>
<?php
foreach (array_merge($commentsInitial, $comments) as $comment) {
	printf('<div class="avatar"><img src="/i/%s"></div><div class="comment"><strong>%s</strong> (%s)</p><p>%s</p><button>Smazat</button></div><hr>',
		htmlspecialchars($comment->avatar),
		$comment->name,
		$comment->job,
		htmlspecialchars($comment->comment)
	);
}
?>
<p><small>Provozuje <a href="https://www.michalspacek.cz/">Michal Špaček</a>, <a href="https://twitter.com/spazef0rze">@spazef0rze</a>. Tento web slouží pouze ke studijním účelům, nenabádám k trestné činnosti.</small></p>
</div>
</body>
</html>
