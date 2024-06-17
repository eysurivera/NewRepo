<html>

    <head>
            <title>Log In Form</title>

           
        
            <?php

            include "connect_database.php";


            session_destroy();
            // Check if the session was destroyed and log the status
            $sessionStatus = session_status();
            if ($sessionStatus === PHP_SESSION_NONE) {
                echo "<script>console.log('Sessions Destroyed')</script>";
            }
            
        session_start();

        $sessionStatus = session_status();
        if ($sessionStatus !== PHP_SESSION_NONE) {
            echo "<script>console.log('Sessions start')</script>";
        }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['user']) && isset($_POST['pass'])) {
            $username = $_POST['user'];
            $password = $_POST['pass'];

            $adminQuery = "SELECT * FROM admin_info WHERE username='$username' AND password='$password'";
            $adminResult = mysqli_query($conn, $adminQuery);

            $query = "SELECT * FROM customer_info WHERE email_address='$username'";
            $result = mysqli_query($conn, $query);


            if (mysqli_num_rows($adminResult) > 0) {
                // Admin login successful
                echo "<script>alert('Admin Logged In Successfully!')</script>";
                $_SESSION['admin'] = $username; 
                header("Location: Admin/admin-dashboard.php"); 
                exit();
            }else if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $accountStatus = $row["Status"];

                if ($accountStatus == "Restricted") {
                    echo "<script>alert('Your Account is restricted!');</script>";
                } else {
                    $hashedPassword = $row['password'];
                    $customer_id = $row['customer_id'];

                    if (password_verify($password, $hashedPassword)) {
                        // Passwords match, login successful
                        echo "<script>alert('Logged In Successfully!')</script>"; 
                        $_SESSION['patient_id'] = $customer_id;
                        header("Location: Patient/patient-home-page.php");
                        exit();
                    } else {
                        // Passwords do not match
                        echo "<script>let username1 = document.querySelector('.username');
                        alert('Invalid Username or Password.');</script>";
                    }
                } 
            }else {
                // User not found
                echo "<script>let username1 = document.querySelector('.username');
                alert('Invalid Username or Password.');</script>";
            } 
        }
    } 

    mysqli_close($conn);
?>



     
            
    </head>

        <body>

        
        <form name="LogInForm" method="POST" action="">
    <div id="Form">
        <h1 id="log-head"><center>Log In</center></h1>
       
        <input required name="user" class="username" type="text" placeholder="Email Address" required>

        <input required name="pass" id="passwords" type="password" placeholder="Password" required>
        <input  id="show1" type="checkbox" onclick="showPassword()">
        <a href="forgetpassword.php"><p id="forgot">Forgot Password? </p></a>

        <button id="button-log">Log In</button>
    </div>
</form>

            

        </body>
</html>