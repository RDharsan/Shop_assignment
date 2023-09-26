<?php
// Remove or suppress the X-Powered-By header
header_remove("X-Powered-By");
header("Content-Security-Policy: default-src 'self'; script-src 'self' trusted-scripts.com");
?>

<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<?php include '../classess/Category.php';?>

<?php
if (!isset($_GET['catid']) || $_GET['catid'] == NULL) {
    echo "<script>window.location='catlist.php';</script>";
} else {
    $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['catid']);
}

$cat = new Category();
$updateCat = ""; // Initialize the error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $catName = $_POST['catName'];

    // Validate the category name using a regular expression
    if (preg_match('/^[a-zA-Z]+$/', $catName)) {
        $updateCat = $cat->catUpdate($catName, $id);
    } else {
        $updateCat = '<span class="error">Category Name should contain only letters.</span>';
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
        <h2>Update Category</h2>
        <div class="block copyblock"> 

        <?php
        if (isset($updateCat)){
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
