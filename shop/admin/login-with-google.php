<?php
require_once __DIR__ . '/../vendor/autoload.php'; 
$oauthConfig = require_once '../config/oauth.php'; 

// Enable a Content Security Policy (CSP) header
header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none'");
// Set X-Content-Type-Options header to 'nosniff'
header("X-Content-Type-Options: nosniff");
// Remove or suppress the X-Powered-By header
header_remove("X-Powered-By");
header_remove("Server");

session_start([
    'cookie_httponly' => true,  
    'cookie_samesite' => 'Lax', // Set to 'Strict' if needed
]);

use League\OAuth2\Client\Provider\Google;

$googleProvider = new Google([
    'clientId'     => $oauthConfig['google']['client_id'],
    'clientSecret' => $oauthConfig['google']['client_secret'],
    'redirectUri'  => $oauthConfig['google']['redirect_uri'],
]);

if (!isset($_GET['code'])) {
    // $authUrl = $googleProvider->getAuthorizationUrl();
    $authUrl = $googleProvider->getAuthorizationUrl([
        'scope' => ['openid', 'email', 'profile'],
    ]);
    
    $_SESSION['oauth2state'] = $googleProvider->getState();
    header('Location: ' . $authUrl);
    exit;
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
} else {
    // Exchange authorization code for an access token
    $token = $googleProvider->getAccessToken('authorization_code', [
        'code' => $_GET['code'],
    ]);

    // Get the user's profile information using the access token
    $user = $googleProvider->getResourceOwner($token);

    // Check if the user's email matches an email in your database
    $email = $user->getEmail();


    $conn = new mysqli("localhost", "root", "", "db_shop");


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM googleuser WHERE adminEmail = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $_SESSION['google_login'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        
    }

    $conn->close();
}
?>
