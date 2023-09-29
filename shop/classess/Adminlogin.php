
<?php
$filepath = realpath(dirname(__FILE__));
include_once ($filepath.'/../lib/Session.php');
Session::checkLogin();

include_once ($filepath.'/../lib/Database.php');
include_once ($filepath.'/../helpers/Formate.php');

?>



<?php

class Adminlogin
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function adminlogin($adminUser, $adminPassword)
    {
        $adminUser = $this->fm->validation($adminUser);
        $adminPassword = $this->fm->validation($adminPassword);

        $adminUser = mysqli_real_escape_string($this->db->link, $adminUser);
        $adminPassword = mysqli_real_escape_string($this->db->link, $adminPassword);

        if (empty($adminUser) || empty($adminPassword)) {
            $loginmsg = "Username or Password must not be empty !";
            return $loginmsg;
        } else {
            // Modify the query to use prepared statements for security
            $query = "SELECT * FROM tbl_admin WHERE adminUser = ? AND adminPassword = ?";
            
            // Create a prepared statement
            $stmt = $this->db->link->prepare($query);
            
            // Bind the parameters
            $stmt->bind_param("ss", $adminUser, $adminPassword);

            // Execute the statement
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $value = $result->fetch_assoc();

                Session::set("adminlogin", true);
                Session::set("adminId", $value['adminId']);
                Session::set("adminUser", $value['adminUser']);
                Session::set("adminName", $value['adminName']);

                header("Location: dashboard.php");
            } else {
                $loginmsg = "Username or Password not match !";
                return $loginmsg;
            }

            // Close the prepared statement
            $stmt->close();
        }
    }
}



?>







