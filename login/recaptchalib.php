<?php
	$response = $_POST["g-recaptcha-response"];

	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$secdata = array(
		'secret' => $secretkey,
		'response' => $_POST["g-recaptcha-response"]
	);
	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($secdata)
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);
?>
