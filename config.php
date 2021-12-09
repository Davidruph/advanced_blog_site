<?php
session_start();
//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('1096168035452-5fb7beqpt8ug5k73rfu46if8291m5ktb.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('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');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/fiverr/oneupmeta/index.php');

// to get the email and profile 
$google_client->addScope('email');

$google_client->addScope('profile');
?>