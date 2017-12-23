<?php  
include "../s3/init.php";
include "db.php"; 
include "header.php";
include "../admin/includes/functions.php";
include "../admin/modals/admin_profile_modal.php";
include "../admin/modals/admin_password.php";



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
    $user_image = $row['user_image'];
  }
  $user_image_1 = $user_image;
}

  if(isset($_POST['update_sub_user'])){
    $file = $_FILES['image'];
  
    $name = $file['name'];
    if($name !== ''){
      $file = $_FILES['image'];
  
      $name = $file['name'];
      $tmp_name = $file['tmp_name'];
      
      $ext = explode('.', $name);
      $ext = strtolower(end($ext));
      
      $key = md5(uniqid());
      
      $temp_file_name = "{$key}.{$ext}";
      
      $temp_file_path = "cms/{$temp_file_name}";
      
      move_uploaded_file($tmp_name, $temp_file_path);
      
      try {
        // Upload data.
        $result = $s3->putObject(array(
           'Bucket'=> $config['s3']['bucket'],
            'Key'   => "cms/{$name}",
            'Body'  => fopen($temp_file_path, 'rb'),
            'ACL'   => 'public-read'
       ));
      
        // Print the URL to the object.
      } catch (S3Exception $e) {
          echo $e->getMessage() . "\n";
      }
      unlink($temp_file_path);
      $user_image = $_FILES['image']['name'];
      $user_image_tmp = $_FILES['image']['tmp_name'];
    }else{
      $user_image = $user_image_1;
    }
 
    $username = $_POST['username'];
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    if(!empty($username) && !empty($user_email) && !empty($user_password)){
        $query = "SELECT randSalt FROM users";
        $select_randsalt_query = mysqli_query($connection, $query);
        confirm($select_randsalt_query);
        
        $row = mysqli_fetch_array($select_randsalt_query);
        $salt = $row['randSalt'];
        $hashed_password = crypt($user_password, $salt);
        
        $query = "UPDATE users SET username = '{$username}', user_firstname = '{$user_firstname}', user_lastname = '{$user_lastname}', ";
        $query .= "user_email = '{$user_email}', user_password = '{$hashed_password}', user_image = '{$user_image}' "; 
        $query .= "WHERE user_id = {$user_id} ";
        
        $update_sub_user = mysqli_query($connection, $query);
        
        confirm($update_sub_user);
        
        $_SESSION['username'] = $username;
        $return = 1;
    }else{
        $return = 2;
    }
  }

?>
<!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" >
        
        <div class="container" style="margin-bottom: -3px; margin-top: -10px;">
            
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../index.php" style="margin-top: 12px;" >CMS</a>
            </div>
            <?php
                if($_SESSION['username'] != null){
            ?>
            <ul class="nav navbar-right top-nav" style="margin-top:5px;">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo "<img width='36' style='margin-right: 5px;' src='https://s3-ap-southeast-1.amazonaws.com/nicoedeimages/cms/{$user_image}' alt='image'>"; ?><?php echo $_SESSION['username']; ?> </b> <b class="caret" style="color:#999;"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <?php if($_SESSION['user_role'] == 'Admin'){?>
                                <a href="../admin/profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
                            <?php }else{ ?>
                                <a href="subscriber_profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
                            <?php } ?>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <?php } ?>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="margin-top: 12px;">
                <ul class="nav navbar-nav">
                  <?php  
                    $query = "SELECT * FROM categories";
                    $select_all_categories_query = mysqli_query($connection, $query);
                    
                    while($row = mysqli_fetch_assoc($select_all_categories_query)){
                      $cat_id = $row['cat_id'];
                      $cat_title = $row['cat_title'];
                      echo "<li><a href='../category.php?category=$cat_id'>{$cat_title}</a></li>";
                    }
                  ?>
                    
                    <?php
                        if($_SESSION['user_role'] == 'Admin'){
                    ?>
                        <li>
                            <a href="admin">Admin</a>
                        </li>
                    <?php } ?>
                    <?php if(!isset($_SESSION['username'])){ ?>
                        <li>
                            <a href="registration.php">Registration</a>
                        </li>
                    <?php } ?>

                    
                </ul>
                
            </div>
            
            <!-- /.navbar-collapse -->
        </div>
 
        <!-- /.container -->
    </nav>
    
    <!-- Page Content -->
    <div class="container">
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-3">
                <?php echo "<img class='img-responsive' src='https://s3-ap-southeast-1.amazonaws.com/nicoedeimages/cms/{$user_image}' alt='image' width='40%' style='float: right; margin-top:70px;'>" ?>
            </div>
            <div class="col-xs-6">
                <div class="form-wrap">
                <h1><center>Update Profile</center></h1>
                    <form action="" method="post" id="login-form" enctype="multipart/form-data">
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
                            <input type="password" name="user_password" id="key" class="form-control">
                            <?php echo "<p class='text-danger'>Please retype your password!</p>";?>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input value="<?php echo $user_firstname; ?>" type="text" name="user_firstname" id="f_name" class="form-control" placeholder="First Name">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input value="<?php echo $user_lastname; ?>" type="text" name="user_lastname" id="l_name" class="form-control" placeholder="Last Name">
                        </div>
                        <div class="form-group">
                            <label for="image">Profile Picture</label>
                            <input type="file" name="image"/>
                        </div>
                        <div class="form-group">
                            <?php echo "<p class='text-danger'>$errForgot</p>";?>
                        </div>
                        <input type="submit" name="update_sub_user" id="btn-login" class="btn btn-custom btn-lg btn-block updateSubUser" value="Update">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>
<hr>
<?php include "footer.php";?>

<script>
    var check = <?php echo $return; ?>;
    
    if(check == 1){
      $('#adminProfile').modal('show');
    }
    
    if(check == 2){
      $('#adminPass').modal('show');
    }
</script>
