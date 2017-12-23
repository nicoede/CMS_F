<?php  
include "includes/db.php"; 
include "includes/header.php"; 
include "includes/navigation.php"; 
include "admin/modals/error.php";
include "admin/modals/register_user_modal.php";
include "admin/modals/user_exists.php";

function eita(){
global $connection;
if(isset($_POST['submit'])){
    $username = $_POST['username'];
    if(!empty($username)){
        $user_query = "SELECT username FROM users WHERE username = '{$username}'";
        $user_query_result = mysqli_query($connection, $user_query);
        $count = mysqli_num_rows($user_query_result);
    }
    if($count == 0){
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
                $user_image = 'profile.png';
                
                $query = "INSERT INTO users (username, user_email, user_password, user_role, user_image, user_date)";
                $query .= "VALUES('{$username}', '{$email}', '{$password}', 'Subscriber', '{$user_image}', now() )";
                $register_user_query = mysqli_query($connection, $query);
                confirm($register_user_query);
                return $message = 1;
            }else{
               return $message = 2;
            }
        }
    }else{
       return $message = 3;
    }
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
                        <?php $return = eita(); ?>
                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block registerUser" value="Register">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

<hr>

<?php include "includes/footer.php";?>

<script>
    var check = <?php echo $return; ?>;
    
    if(check == 1){
      
        
          $('#register_user_modal_id').modal('show');
        
      
    }
    
    if(check == 2){
      
        
          $('#error').modal('show');
        
      
    }
    
    if(check == 3){
      
        
          $('#user_exists').modal('show');
        
      
    }
</script>