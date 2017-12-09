<?php  
include "includes/db.php"; 
include "includes/header.php"; 
include "includes/navigation.php"; 
include "admin/includes/functions.php";

if(isset($_SESSION['username'])){
  $username = $_SESSION['username'];
  
  $query = "SELECT * FROM users WHERE username = '{$username}' ";
  $select_user_profile_query = mysqli_query($connection, $query);
  confirm($select_user_profile_query);
  
  while($row = mysqli_fetch_assoc($select_user_profile_query)){
    $user_id = $row['user_id'];
    $username = $row['username'];
    $user_password = $row['user_password'];
    $user_firstname = $row['user_firstname'];
    $user_lastname = $row['user_lastname'];
    $user_email = $row['user_email'];
  }
}

  if(isset($_POST['update_sub_user'])){
    $username = $_POST['username'];
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    
    $query = "SELECT randSalt FROM users";
    $select_randsalt_query = mysqli_query($connection, $query);
    confirm($select_randsalt_query);
    
    $row = mysqli_fetch_array($select_randsalt_query);
    $salt = $row['randSalt'];
    $hashed_password = crypt($user_password, $salt);
    
    $query = "UPDATE users SET username = '{$username}', user_firstname = '{$user_firstname}', user_lastname = '{$user_lastname}', ";
    $query .= "user_email = '{$user_email}', user_password = '{$hashed_password}' "; 
    $query .= "WHERE user_id = {$user_id} ";
    
    $update_sub_user = mysqli_query($connection, $query);
    
    if(!confirm($update_sub_user)){
      $message = "User Updated!";
      echo "<script type='text/javascript'>alert('$message');</script>";
    }
    $_SESSION['username'] = $username;
  }

?>
    
 
    <!-- Page Content -->
    <div class="container">
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1><center>Update Profile</center></h1>
                    <form role="form" action="subscriber_profile.php" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input value="<?php echo $username; ?>" type="text" name="username" id="username" class="form-control">
                        </div>
                         <div class="form-group">
                            <label for="email">Email:</label>
                            <input value="<?php echo $user_email; ?>" type="email" name="user_email" id="email" class="form-control">
                        </div>
                         <div class="form-group">
                            <label for="password">Password:</label>
                            <input value="<?php echo $user_password; ?>" type="password" name="user_password" id="key" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input value="<?php echo $user_firstname; ?>" type="text" name="user_firstname" id="f_name" class="form-control" placeholder="First Name">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input value="<?php echo $user_lastname; ?>" type="text" name="user_lastname" id="l_name" class="form-control" placeholder="Last Name">
                        </div>
                
                        <input type="submit" name="update_sub_user" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Update">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>


        <hr>



<?php include "includes/footer.php";?>
