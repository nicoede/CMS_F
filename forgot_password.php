<?php  
include "includes/db.php"; 
include "includes/header.php"; 
include "includes/navigation.php"; 

include "forgot-password-recovery-mail.php";

if(isset($_POST["forgot-password"])){
	$condition = "";
	if(!empty($_POST["username"])) 
		$condition = " username = '" . $_POST["username"] . "'";
	if(!empty($_POST["user_email"])) {
		if(!empty($condition)) {
			$condition = " and ";
		}
		$condition = " user_email = '" . $_POST["user_email"] . "'";
	}
	
	if(!empty($condition)) {
		$condition = " WHERE " . $condition;
	}
	
	$sql = "SELECT * FROM users " . $condition;
	$result = mysqli_query($connection,$sql);
	$user = mysqli_fetch_array($result);
	if(!empty($user)) {
		sendmail($user['username'], $user['user_email']);
	} else {
		$error_message = 'No User Found';
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
                <h1>Forgot your Password?</h1>
                <h3>Which of the following do you remember?</h3>
                    <form role="form" action="" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                            <label for="username" class="sr-only">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" style="margin-bottom:13px;">
                            <b>Or</b>
                        </div>
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="user_email" id="user_email" class="form-control" placeholder="somebody@example.com">
                        </div>            
                        <input type="submit" name="forgot-password" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Submit">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

<hr>

<?php include "includes/footer.php";?>
