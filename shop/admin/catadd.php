<?php
session_start();

// Enable a Content Security Policy (CSP) header
header("Content-Security-Policy: frame-ancestors 'none'");
// Remove or suppress the X-Powered-By header
header_remove("X-Powered-By");

include 'inc/header.php';
include 'inc/sidebar.php';
include '../classess/Category.php';

$cat = new Category();
$insertCat = ""; // Initialize the error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token
    if (
        isset($_POST['csrf_token']) && 
        $_POST['csrf_token'] === $_SESSION['csrf_token']
    ) {
        $catName = $_POST['catName'];

        // Validate the category name using ctype_alpha
        if (ctype_alpha($catName)) {
            // Sanitize the input
            $catName = htmlspecialchars($catName);

            // Category name is valid, proceed with insertion using prepared statements
            $insertCat = $cat->catInsert($catName);

            if ($insertCat === "Category added successfully.") {
                // Clear the CSRF token after a successful submission
                unset($_SESSION['csrf_token']);
            }
        } else {
            // Category name is invalid, display an error message
            $insertCat = "Category Name should contain only letters.";
        }
    } else {
        // Invalid CSRF token, reject the request or take appropriate action
        die("CSRF token validation failed.");
    }
}
?>

<div class="grid_10">
    <div class="box round first grid">
        <h2>Add New Category</h2>
        <div class="block copyblock">
            <?php
            if (!empty($insertCat)) {
                echo '<div class="error">' . $insertCat . '</div>';
            }
            ?>
            <form action="catadd.php" method="post">
                <table class="form">
                    <tr>
                        <td>
                            <input type="text" name="catName" placeholder="Enter Category Name..." class="medium" required />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!-- Add the CSRF token as a hidden field -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="submit" name="submit" value="Save" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php include 'inc/footer.php'; ?>
