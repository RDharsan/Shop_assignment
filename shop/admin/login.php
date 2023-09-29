



<?php
    // Enable a Content Security Policy (CSP) header
    header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none'");
    // Set X-Content-Type-Options header to 'nosniff'
    header("X-Content-Type-Options: nosniff");

    // Remove or suppress the X-Powered-By header
    header_remove("X-Powered-By");
    header_remove("Server");

    session_start([
        'cookie_httponly' => true,  // Set the HttpOnly flag
        'cookie_samesite' => 'Lax', // Set to 'Strict' if needed
    ]);

    // login.php (inside the admin folder)

    // Include the OAuth configuration file from the parent directory
    $oauthConfig = require_once '../config/oauth.php';

    // Access Google OAuth configuration
    $googleClientId = $oauthConfig['google']['client_id'];
    $googleClientSecret = $oauthConfig['google']['client_secret'];
    $googleRedirectUri = $oauthConfig['google']['redirect_uri'];

    // The rest of your login.php code here...


    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
    }
    $csrf_token = $_SESSION['csrf_token'];
    ?>

    <!DOCTYPE html>
    <head>
        
    <meta charset="utf-8">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="css/stylelogin.css" media="screen" />
    </head>
    <body>
    <div class="container">
        <section id="content">
            <form action="login.php" method="post">
                <h1>Admin Login</h1>
                <span style="color: red; font-size: 18px;">
                    <?php
                    if (isset($loginchk)) {
                        echo $loginchk;
                    }
                    ?>
                </span>

                <div>
                    <input type="text" placeholder="Email" name="adminUser" required>
                </div>
                <div>
                    <input type="password" placeholder="Password" name="adminPassword" required>
                </div>
                <div>
                    <!-- Include the CSRF token in the form -->
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="submit" value="Log in" />
                </div>
                <div>
                <a href="login-with-google.php">
                
                <img src="../images/icons8-google-16.png" alt="Login with Google" style="width: 5px; display: block; margin: 0 auto;">
                
                <a href="login-with-google.php">Login with google</a>
                </a>
                




                </div>
            </form><!-- form -->
            <div class="button">
                <a href="#">Online shopping</a>
            </div><!-- button -->
        </section><!-- content -->
    </div><!-- container -->

    <?php
    include '../classess/Adminlogin.php';

    $al = new Adminlogin();

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the CSRF token exists in the POST data
    if (!isset($_POST['csrf_token'])) {
        die("CSRF token not found.");
    }

    // Check if the CSRF token matches the one stored in the session
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    // Continue with form data processing
    $adminUser = $_POST['adminUser'];
    $adminPassword = md5($_POST['adminPassword']);
    $loginchk = $al->adminlogin($adminUser, $adminPassword);
}
    ?>
    </body>
    </html>










