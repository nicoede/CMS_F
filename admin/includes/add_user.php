<?php
  add_user_admin();
?>

<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" name="username"/>
  </div>

  <div class="form-group">
    <select class="form-control" name="user_role" id="">
      <?php
        echo "<option value='Subscriber'>Subscriber</option>";
        echo "<option value='Admin'>Admin</option>";
      ?>
    </select>
  </div>
    
  <div class="form-group">
    <label for="user_firstname">First Name:</label>
    <input type="text" class="form-control" name="user_firstname"/>
  </div>
  
  <div class="form-group">
    <label for="user_lastname">Last Name:</label>
    <input type="text" class="form-control" name="user_lastname"/>
  </div>
  
  <div class="form-group">
    <label for="user_email">Email:</label>
    <input type="email" class="form-control" name="user_email"/>
  </div>
  
  <div class="form-group">
    <label for="user_password">Password:</label>
    <input type="password" class="form-control" name="user_password"/>
  </div>
  
  <div class="form-group">
    <label for="user_image">User Image:</label>
    <input type="file" name="user_image"/>
  </div>
  
  
  <div class="form-group">
    <input class="btn btn-primary" type="submit" name="create_user" value="Create User"/>
  </div>
</form>