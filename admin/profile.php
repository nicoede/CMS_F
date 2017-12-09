<?php 
include "includes/admin_header.php"; 

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
    $user_role = $row['user_role'];
    $user_image = $row['user_image'];
  }
}

if(isset($_POST['update_profile'])){
    $username = $_POST['username'];
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $user_role = $_POST['user_role'];
    
    $query = "SELECT randSalt FROM users";
    $select_randsalt_query = mysqli_query($connection, $query);
    confirm($select_randsalt_query);
    
    $row = mysqli_fetch_array($select_randsalt_query);
    $salt = $row['randSalt'];
    $hashed_password = crypt($user_password, $salt);
    
    $query = "UPDATE users SET username = '{$username}', user_firstname = '{$user_firstname}', user_lastname = '{$user_lastname}', ";
    $query .= "user_email = '{$user_email}', user_password = '{$hashed_password}', user_role = '{$user_role}' "; 
    $query .= "WHERE user_id = {$user_id} ";
    
    $update_profile = mysqli_query($connection, $query);
    
    confirm($update_profile);
    $_SESSION['username'] = $username;
    header("Location: profile.php");
  }
?>


<div id="wrapper" style="margin-top:-20px;">
    
    <!-- Navigation -->
    <?php include "includes/admin_navigation.php"; ?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                       Your Profile
                    </h1>
                    
                    <form action="" method="post" enctype="multipart/form-data">
                      
                      <div class="form-group">
                        <label for="username">Username</label>
                        <input value="<?php echo $username?>" type="text" class="form-control" name="username"/>
                      </div>
                    
                      <div class="form-group">
                        <select class="form-control" name="user_role" id="">
                          <option value="Subscriber"><?php echo $user_role; ?></option>
                          <?php
                            if($user_role == 'Admin'){
                              echo "<option value='Subscriber'>Subscriber</option>";
                            }else{
                              echo "<option value='Admin'>Admin</option>";
                            }
                          ?>
                        </select>
                      </div>
                      
                      <div class="form-group">
                        <label for="user_firstname">First Name:</label>
                        <input value="<?php echo $user_firstname?>" type="text" class="form-control" name="user_firstname"/>
                      </div>
                      
                      <div class="form-group">
                        <label for="user_lastname">Last Name:</label>
                        <input value="<?php echo $user_lastname?>" type="text" class="form-control" name="user_lastname"/>
                      </div>
                      
                      <div class="form-group">
                        <label for="user_email">Email:</label>
                        <input value="<?php echo $user_email?>" type="email" class="form-control" name="user_email"/>
                      </div>
                      
                      <div class="form-group">
                        <label for="user_password">Password:</label>
                        <input type="password" class="form-control" name="user_password"/>
                      </div>
                      
                      <div class="form-group">
                        <img width='100' src="../images/<?php echo $user_image; ?>" alt="">
                      </div>
                      
                      <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="update_profile" value="Update Profile"/>
                      </div>
                      
                    </form>
                   
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

<?php include "includes/admin_footer.php"; ?>