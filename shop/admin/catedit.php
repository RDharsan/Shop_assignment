<?php
session_start();

// Enable a Content Security Policy (CSP) header
header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none'");
// Remove or suppress the X-Powered-By header
header_remove("X-Powered-By");
// Set X-Content-Type-Options header to 'nosniff'
header("X-Content-Type-Options: nosniff");

include 'inc/header.php';
include 'inc/sidebar.php';
include '../classess/Category.php';

// Function to generate a CSRF token
function generateCSRFToken() {
    $token = bin2hex(random_bytes(32)); // Generate a random token
    $_SESSION['csrf_token'] = $token;  // Store it in the session
    return $token;
}

if (!isset($_GET['catid']) || $_GET['catid'] == NULL) {
    echo "<script>window.location='catlist.php';</script>";
} else {
    $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['catid']);
}

$cat = new Category();
$updateCat = ""; // Initialize the error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        $catName = $_POST['catName'];

        // Validate the category name using a regular expression
        if (preg_match('/^[a-zA-Z]+$/', $catName)) {
            $updateCat = $cat->catUpdate($catName, $id);
        } else {
            $updateCat = '<span class="error">Category Name should contain only letters.</span>';
        }
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
        <h2>Update Category</h2>
        <div class="block copyblock"> 
        <?php
        if (isset($updateCat)) {
            echo $updateCat;
        }
        ?>
        <?php
        $getCat = $cat->getCatById($id);
        if ($getCat) {
            while ($result = $getCat->fetch_assoc()) {
        ?>   
            <form action="" method="post">
                <table class="form">                    
                    <tr>
                        <td>
                            <input type="text" name="catName" value="<?php echo $result['catName'];?>" class="medium" />
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
<?php include 'inc/footer.php'; ?>
