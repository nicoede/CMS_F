<?php  
include "includes/db.php"; 
include "includes/header.php"; 
include "includes/navigation.php"; 
include "admin/includes/functions.php";

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if(strcmp($password, $confirm_password) !== 0){
        $errPassword = 'Passwords do not match!';
    }else{
        if(!empty($username) && !empty($email) && !empty($password)){
            $username = mysqli_real_escape_string($connection, $username);
            $email = mysqli_real_escape_string($connection, $email);
            $password = mysqli_real_escape_string($connection, $password);
            
            $query = "SELECT randSalt FROM users";
            $select_randsalt_query = mysqli_query($connection, $query);
            confirm($select_randsalt_query);
            
            
            $row = mysqli_fetch_array($select_randsalt_query);
            $salt = $row['randSalt'];
            $password = crypt($password, $salt);
            
            $query = "INSERT INTO users (username, user_email, user_password, user_role, user_date)";
            $query .= "VALUES('{$username}', '{$email}', '{$password}', 'Subscriber', now() )";
            $register_user_query = mysqli_query($connection, $query);
            confirm($register_user_query);
            
            $message = "Your Registration has been Submited! Now you can login! ";
            header("Refresh: 0.1; url=index.php");
        }else{
            $message = "You need to fill all fields correctly! ";
        }
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}

?>
    
 
<!-- Page Content -->
<div class="container">
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1>Register</h1>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Enter Desired Username">
                        </div>
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com">
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="Password">
                            <?php echo "<p class='text-danger'>$errPassword</p>";?>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="sr-only">Confirm Password</label>
                            <input type="password" name="confirm_password" id="key" class="form-control" placeholder="Confirm Password">
                            <?php echo "<p class='text-danger'>$errPassword</p>";?>
                        </div>
                
                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

<hr>

<?php include "includes/footer.php";?>
