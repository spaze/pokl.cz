<?php
if ($_GET['secret'] ?? '' === 'not') {
	phpinfo();
} else {
	http_response_code(404);
	echo '<strong>Secret</strong> <code>not</code> <em>found</em>';
}
