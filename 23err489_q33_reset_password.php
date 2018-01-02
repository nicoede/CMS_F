<?php
include "includes/db.php"; 
include "includes/header.php"; 
include "includes/navigation.php"; 
include "admin/includes/functions.php";

	if(isset($_POST["reset-password"])) {
		$password = $_POST['password'];
		
		$name1 = $_GET['name'];
		$query = "SELECT randSalt FROM users";
	    $select_randsalt_query = mysqli_query($connection, $query);
	    confirm($select_randsalt_query);
	    
	    $row = mysqli_fetch_array($select_randsalt_query);
	    $salt = $row['randSalt'];
	    $hashed_password = crypt($password, $salt);
	    
	    $eita = "UPDATE users SET user_password = '{$hashed_password}' "; 
	    $eita .= "WHERE username = '{$name1}' ";
	    
	    $update_user = mysqli_query($connection, $eita);
	}
?>

<!-- Page Content -->
<div class="container">
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1>Your New Password:</h1>
                    <form role="form" action="" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" style="margin-bottom:13px;">
                        </div>
                         <div class="form-group">
                            <label for="confirm_password" class="sr-only">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                        </div>            
                        <input type="submit" name="reset-password" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Submit">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

<hr>

<?php include "includes/footer.php";?>
