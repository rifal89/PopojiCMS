<?php
session_start();
include_once '../../../../po-includes/core/core.php';
require_once '../../../../po-content/component/oauth/twitter/Twitter/twitteroauth.php';

$tableoauthtw = new PoCore();
$currentOauthtw = $tableoauthtw->podb->from('oauth')->fetchAll();
$conkeyOauthtw = $currentOauthtw[1]['oauth_key'];
$consecretOauthtw = $currentOauthtw[1]['oauth_secret'];

define('CONSUMER_KEY', ''.$conkeyOauthtw.'');
define('CONSUMER_SECRET', ''.$consecretOauthtw.'');
define('OAUTH_CALLBACK', ''.WEB_URL.'member/login/twitter');

/* Build TwitterOAuth object with client credentials. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

/* Get temporary credentials. */
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

/* Save temporary credentials to session. */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

/* If last connection failed don't display authorization link. */
switch ($connection->http_code){
	default:
		/* Show notification if something went wrong. */
		header('location:'.WEB_URL.'member/login');
	break;
	case 200:
		/* Build authorize URL and redirect user to Twitter. */
		$url = $connection->getAuthorizeURL($token);
		header('location:'.$url);
	break;
}
?>