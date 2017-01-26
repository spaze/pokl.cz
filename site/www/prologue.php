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

require_once __DIR__ . '/language.php';

$dataDir = __DIR__ . '/../data/';
$cookieFile = $dataDir . 'cookie.json';
$commentsInitialFile = $dataDir . "comments.initial-{$l}.json";
$commentsFile = $dataDir . 'comments.json';

$cookie = json_decode(file_get_contents($cookieFile));
$signedIn = (isset($_COOKIE[$cookie->name]) && $_COOKIE[$cookie->name] === $cookie->value);
$commentsInitial = json_decode(file_get_contents($commentsInitialFile));
$comments = (json_decode(file_get_contents($commentsFile)) ?: []);

