﻿<?php
// Enable a Content Security Policy (CSP) header
header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none'");
// Set X-Content-Type-Options header to 'nosniff'
header("X-Content-Type-Options: nosniff");
?>
<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<div class="grid_10">
    <div class="box round first grid">
        <h2>Update Copyright Text</h2>
        <div class="block copyblock"> 
         <form>
            <table class="form">					
                <tr>
                    <td>
                        <input type="text" placeholder="Enter Copyright Text..." name="copyright" class="large" />
                    </td>
                </tr>
				
				 <tr> 
                    <td>
                        <input type="submit" name="submit" Value="Update" />
                    </td>
                </tr>
            </table>
            </form>
        </div>
    </div>
</div>
<?php include 'inc/footer.php';?>