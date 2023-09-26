<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<?php include '../classess/Brand.php';?>

<?php
if (!isset($_GET['brandid']) || $_GET['brandid'] == NULL) {
    echo "<script>window.location='brandlist.php';</script>";
} else {
    $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['brandid']);
}

$brand = new Brand();
$updateBrand = ""; // Initialize the error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brandName = $_POST['brandName'];

    // Validate the brand name using a regular expression
    if (preg_match('/^[a-zA-Z0-9]+$/', $brandName)) {
        $updateBrand = $brand->brandUpdate($brandName, $id);
    } else {
        $updateBrand = '<span class="error">Brand Name should contain only letters and numbers.</span>';
    }
}
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
