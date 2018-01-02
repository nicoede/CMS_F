<?php  
include "includes/db.php"; 
include "includes/header.php"; 
include "includes/navigation.php";
include "admin/modals/mail_modal.php";

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
                <h1 style="margin-top: 100px;">Forgot your Password?</h1>
                <h3 style="margin-bottom: 50px;">Which of the following do you remember?</h3>
                    <form role="form" action="" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" name="username" id="username" class="form-control" style="margin-bottom:13px;">
                            <b>Or</b>
                        </div>
                         <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="user_email" id="user_email" class="form-control">
                        </div>            
                        <input type="submit" name="forgot-password" id="btn-login" class="btn btn-custom btn-lg btn-block emailSend" value="Submit">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>




    <!-- Footer -->
        <div class="row footer" style="margin-top: 30%;" >
            <div class="col-lg-12">
                <hr>
                <p>Copyright &copy; Edenilson J dos Passos 2017</p>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->

</div>
<!-- /.container -->



</body>

</html>


<script>
    $(document).ready(function(){
        $(".checkEmail").on('click', function(){
          $('#check_email_modal_id').modal('show');
        });
    });
</script>
