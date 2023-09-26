<?php
// Remove or suppress the X-Powered-By header
header_remove("X-Powered-By");
header("Content-Security-Policy: default-src 'self'; script-src 'self' trusted-scripts.com");



// Rest of your PHP code here...
?>

<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<?php include '../classess/Brand.php';?>

<?php
$brand = new Brand();
$insertBrand = ""; // Initialize the error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brandName = $_POST['brandName'];

    // Validate the brand name using a regular expression
    if (filter_var($brandName, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[A-Za-z0-9]+$/")))) {
        // Brand name is valid, proceed with insertion
        $insertBrand = $brand->brandInsert($brandName);
    } else {
        // Brand name is invalid, display an error message
        $insertBrand = "Brand Name is not valid. It should contain only letters and numbers.";
    }
}
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
                            <input type="text" name="brandName" placeholder="Enter Brand Name..." class="medium" />
                        </td>
                    </tr>
                    <tr> 
                        <td>
                            <input type="submit" name="submit" Value="Save" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php include 'inc/footer.php';?>
