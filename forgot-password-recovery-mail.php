<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'vendor/autoload.php';
function sendmail($username1, $user_email1){
	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
	
	$emailBody = "<div>" . $username1 . ",<br><br><p>Click this link to recover your password<br><a href='" . "https://cmsss-nicoede.c9users.io" . "/reset_password.php?name=" . $username1 . "'>" . "</a><br><br></p>Regards,<br> Admin.</div>";
	$emailBody = "<div>" . $username1 . ",<br><br><p>Click this link to recover your password https://cmsss-nicoede.c9users.io/reset_password.php?name=" . $username1 . "<br><br></p>Regards,<br> Admin.</div>";
	
	//Server settings
	$mail->SMTPDebug = false;                                 // Enable verbose debug output
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'nicoedeweb@gmail.com';                 // SMTP username
	$mail->Password = 'edenilson';                           // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to
	
	//Recipients
	$mail->setFrom('no-reply@howcode.org', 'Mailer');
	$mail->addAddress($user_email1, 'Joe User');     // Add a recipient
	
	//Content
	                                 // Set email format to HTML
	$mail->Subject = "Forgot Password Recovery";
	
	$mail->MsgHTML($emailBody);
	$mail->isHTML(true); 
	
	if(!$mail->Send()) {
		$error_message = 'Problem in Sending Password Recovery Email';
	} else {
		$success_message = 'Please check your email to reset password!';
	}
}

?>
