<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<?php include '../classess/Category.php';?>

<?php
$cat = new Category();
$insertCat = ""; // Initialize the error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $catName = $_POST['catName'];

    // Validate the category name using ctype_alpha
    if (ctype_alpha($catName)) {
        // Category name is valid, proceed with insertion
        $insertCat = $cat->catInsert($catName);
    } else {
        // Category name is invalid, display an error message
        $insertCat = "Category Name should contain only letters.";
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
                            <input type="text" name="catName" placeholder="Enter Category Name..." class="medium" />
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
