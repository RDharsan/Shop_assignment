﻿<?php
// Enable a Content Security Policy (CSP) header
header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none'");
// Set X-Content-Type-Options header to 'nosniff'
header("X-Content-Type-Options: nosniff");

// Remove or suppress the X-Powered-By header
header_remove("X-Powered-By");
?>

<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<div class="grid_10">
    <div class="box round first grid">
        <h2> Dashboard</h2>
        <div class="block">               
            Welcome admin panel        
        </div>
    </div>
</div>
<?php include 'inc/footer.php';?>
