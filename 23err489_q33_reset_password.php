<?php
include "includes/db.php"; 
include "includes/header.php"; 
include "includes/navigation.php";
include "admin/modals/password_change.php";
include "admin/modals/not_match.php";

function verify(){
	if(isset($_POST["reset-password"])) {
		$password = $_POST['password'];
		$confirm_password = $_POST['confirm_password'];
        if(strcmp($password, $confirm_password) !== 0 || empty($password)){
            return 2;
        }else{
    		$name1 = $_GET['name'];
    		$query = "SELECT randSalt FROM users";
    	    $select_randsalt_query = mysqli_query($connection, $query);
    	    
    	    $row = mysqli_fetch_array($select_randsalt_query);
    	    $salt = $row['randSalt'];
    	    $hashed_password = crypt($password, $salt);
    	    
    	    $eita = "UPDATE users SET user_password = '{$hashed_password}' "; 
    	    $eita .= "WHERE username = '{$name1}' ";
    	    
    	    $update_user = mysqli_query($connection, $eita);
    	    
    	    return 1;
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
                <h1 style="margin-top: 100px; margin-bottom: 50px;">Your New Password:</h1>
                    <form role="form" action="" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" style="margin-bottom:13px;">
                        </div>
                         <div class="form-group">
                            <label for="confirm_password" class="sr-only">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                        </div>            
                        <input type="submit" name="reset-password" id="btn-login" class="btn btn-custom btn-lg btn-block passChange" value="Submit">
                    </form>
                     <?php $return = verify(); ?>
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

<hr>

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
    var check = <?php echo $return; ?>;
    
    if(check == 1){
      $('#pass_change_modal_id').modal('show');
    }
    
    if(check == 2){
      $('#notmatch_id').modal('show');
    }
    
</script>
