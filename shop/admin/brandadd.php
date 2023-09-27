<?php
// Enable a Content Security Policy (CSP) header
header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none'");
// Set X-Content-Type-Options header to 'nosniff'
header("X-Content-Type-Options: nosniff");
// Remove or suppress the X-Powered-By header
header_remove("X-Powered-By");

session_start();

include 'inc/header.php';
include 'inc/sidebar.php';
include '../classess/Brand.php';

// Function to generate a CSRF token
function generateCSRFToken() {
    $token = bin2hex(random_bytes(32)); // Generate a random token
    $_SESSION['csrf_token'] = $token;  // Store it in the session
    return $token;
}

$brand = new Brand();
$insertBrand = ""; // Initialize the error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        $brandName = $_POST['brandName'];

        // Validate the brand name using a regular expression
        if (filter_var($brandName, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[A-Za-z0-9]+$/")))) {
            // Brand name is valid, proceed with insertion
            $insertBrand = $brand->brandInsert($brandName);
        } else {
            // Brand name is invalid, display an error message
            $insertBrand = "Brand Name is not valid. It should contain only letters and numbers.";
        }

        // After processing, you can unset or regenerate the CSRF token
        unset($_SESSION['csrf_token']);
    } else {
        // Invalid CSRF token, reject the request or take appropriate action
        die("CSRF token validation failed.");
    }
}

// Generate a new CSRF token for each page load
$csrfToken = generateCSRFToken();
?>

<div class="grid_10">
    <div class="box round first grid">
        <h2>Add New Brand</h2>
        <div class="block copyblock"> 
            <?php
            if (!empty($insertBrand)) {
                echo '<div class="error">' . $insertBrand . '</div>';
            }
            ?>
            <form action="" method="post">
                <table class="form">					
                    <tr>
                        <td>
                            <input type="text" name="brandName" placeholder="Enter Brand Name..." class="medium" required />
                        </td>
                    </tr>
                    <tr> 
                        <td>
                            <!-- Add the CSRF token as a hidden field -->
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            <input type="submit" name="submit" Value="Save" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php include 'inc/footer.php';?>
