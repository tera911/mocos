<?php

session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
$text = filter_input(INPUT_POST, "tweet", FILTER_SANITIZE_STRING);
echo $text;

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$req = $connection->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json","POST",array("status"=> $text));

$result = json_decode($req);

echo "<pre>";
var_dump($result);
