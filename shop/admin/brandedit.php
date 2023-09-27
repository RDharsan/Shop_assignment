<?php
// Enable a Content Security Policy (CSP) header
header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none'");
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

if (!isset($_GET['brandid']) || $_GET['brandid'] == NULL) {
    echo "<script>window.location='brandlist.php';</script>";
} else {
    $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['brandid']);
}

$brand = new Brand();
$updateBrand = ""; // Initialize the error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        $brandName = $_POST['brandName'];

        // Validate the brand name using a regular expression
        if (preg_match('/^[a-zA-Z0-9]+$/', $brandName)) {
            $updateBrand = $brand->brandUpdate($brandName, $id);
        } else {
            $updateBrand = '<span class="error">Brand Name should contain only letters and numbers.</span>';
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

<style>
.error {
    color: red; /* Set the text color to red */
}
</style>

<div class="grid_10">
    <div class="box round first grid">
        <h2>Update Brand</h2>
        <div class="block copyblock"> 
        <?php
        if (isset($updateBrand)){
            echo $updateBrand;
        }
        ?>
        <?php
        $getBrand = $brand->getBrandById($id);
        if ($getBrand) {
            while ($result = $getBrand->fetch_assoc()) {
        ?>   
            <form action="" method="post">
                <table class="form">                    
                    <tr>
                        <td>
                            <input type="text" name="brandName" value="<?php echo $result['brandName'];?>" class="medium" />
                        </td>
                    </tr>
                    <tr> 
                        <td>
                            <!-- Add the CSRF token as a hidden field -->
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            <input type="submit" name="submit" Value="Update" />
                        </td>
                    </tr>
                </table>
            </form>
            <?php } } ?>
        </div>
    </div>
</div>
<?php include 'inc/footer.php';?>
