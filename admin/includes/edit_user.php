<?php
include "modals/edit_user_modal.php";

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
    $user_role = $_POST['user_role'];
    
    $query = "UPDATE users SET user_role = '{$user_role}' "; 
    $query .= "WHERE user_id = {$the_user_id} ";
    
    $update_user = mysqli_query($connection, $query);
    
    confirm($update_user);
    
    header("Refresh: 0.5; url=users.php");
  }
?>

<form action="" method="post" enctype="multipart/form-data">
  <div class="well">Username: <?php echo $username?>
  <br>
  First Name: <?php echo $user_firstname?>
  <br>
  Last Name: <?php echo $user_lastname?>
  <br>
  Email: <?php echo $user_email?>
  <br><br>
  <?php echo "<img width='64' style='margin-right: 5px;' src='https://s3-ap-southeast-1.amazonaws.com/nicoedeimages/cms/{$user_image}' alt='image'>"; ?></div>
  <br><br>
  <div class="form-group">
    <select class="form-control" name="user_role" id="">
      <option value='Subscriber'>Subscriber</option>
      <option value='Admin'>Admin</option>
    </select>
  </div>
  
  <div class="form-group">
    <input class="btn btn-primary updateUser" type="submit" name="update_user" value="Update User"/>
  </div>
</form>

<script>
  $(document).ready(function(){
    $(".updateUser").on('click', function(){
      $('#user_updated_modal_id').modal('show');
    });
  });
</script>