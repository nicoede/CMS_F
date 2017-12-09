<?php
  if(isset($_GET['u_id'])){
    $the_user_id = $_GET['u_id'];
    
    $query = "SELECT * FROM users WHERE user_id = $the_user_id ";
    $select_user_by_id = mysqli_query($connection, $query);
    
    while($row = mysqli_fetch_assoc($select_user_by_id)){
      $user_id = $row['user_id'];
      $username = $row['username'];
      $user_firstname = $row['user_firstname'];
      $user_lastname = $row['user_lastname'];
      $user_email = $row['user_email'];
      $user_password = $row['user_password'];
      $user_role = $row['user_role'];
      $user_image = $row['user_image'];
      $user_date = $row['user_date'];
    }
  }

  if(isset($_POST['update_user'])){
    $username = $_POST['username'];
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $user_role = $_POST['user_role'];
    $user_image = $_FILES['user_image']['name'];
    $user_image_tmp = $_FILES['user_image']['tmp_name'];
    
    move_uploaded_file($user_image_temp, "../images/$user_image" );
    
    $query = "SELECT randSalt FROM users";
    $select_randsalt_query = mysqli_query($connection, $query);
    confirm($select_randsalt_query);
    
    $row = mysqli_fetch_array($select_randsalt_query);
    $salt = $row['randSalt'];
    $hashed_password = crypt($user_password, $salt);
    
    $query = "UPDATE users SET username = '{$username}', user_firstname = '{$user_firstname}', user_lastname = '{$user_lastname}', ";
    $query .= "user_email = '{$user_email}', user_password = '{$hashed_password}', user_role = '{$user_role}' "; 
    $query .= "WHERE user_id = {$the_user_id} ";
    
    $update_user = mysqli_query($connection, $query);
    
    if(!confirm($update_user)){
      $message = "User Updated!";
      echo "<script type='text/javascript'>alert('$message');</script>";
    }
    
    header("Refresh: 0.5; url=users.php");
  }
?>

<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="username">Username</label>
    <input value="<?php echo $username?>" type="text" class="form-control" name="username"/>
  </div>

  <div class="form-group">
    <select class="form-control" name="user_role" id="">
      <option value='Subscriber'>Subscriber</option>
      <option value='Admin'>Admin</option>
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
    <input class="btn btn-primary" type="submit" name="update_user" value="Update User"/>
  </div>
</form>