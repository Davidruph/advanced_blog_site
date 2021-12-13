<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	// Load Composer's autoloader
	require 'vendor/autoload.php';
	require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
	require 'vendor/phpmailer/phpmailer/src/SMTP.php';
	require 'vendor/phpmailer/phpmailer/src/Exception.php';

	require 'dbconn.php';
	$errors = array();
    $success = array();

    if(isset($_GET['v_code'])){
    $code = $_GET['v_code'];
    
    $query = mysqli_query($conn, "SELECT email, firstname, lastname, verification_code FROM users WHERE verification_code = '$code'");
    if(mysqli_num_rows($query) > 0){
        $user_details = mysqli_fetch_array($query);
        $firstname = $user_details['firstname'];
        $lastname = $user_details['lastname'];
        $email = $user_details['email'];
        $message='
	    <div>
	    <b><p>Hi, '.$firstname.' '.$lastname.'</p></b>
	    <p>Welcome to oneupmeta website.</p>
	    <p>We hope to make your stay with us a great one.</p>
	    </div>
	    ';

        $names = $firstname .' '. $lastname;
        
        $qry = mysqli_query($conn, "UPDATE users SET verified_email = '1' WHERE verification_code = '".$code."'");

        if ($qry) {
        	$mail = new PHPMailer(true);
                try {
                        //Server settings
                        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;            
                    $mail->Host = 'ssl://smtp.gmail.com:465';
                    $mail->isSMTP();
                    $mail->SMTPAuth = true;
                    $mail->Username = 'oneupmeta.ray@gmail.com'; // Gmail address which you want to use as SMTP server
                    $mail->Password = 'p@ssword123456'; // Gmail address Password
                    $mail->Port = 465; //587
                    $mail->SMTPSecure = 'ssl'; //tls
                    $mail->addAddress($email); // Email address where you want to receive emails (you can use any of your gmail address including the gmail address which you used as SMTP server)
                    $mail->setFrom('oneupmeta.ray@gmail.com', 'Welcome Email'); // Gmail address which you used as SMTP server
                    //$mail->debug = 2;
                    $mail->isHTML(true);
                    $mail->Subject = 'Message Received From (oneupmeta)';
                    $mail->Body = "$message";
                    $mail->AltBody = '';
                    

                    if ($mail->Send()){
                     //do nothing
                    }else{

                        $errors['mail'] = 'Email Not sent';
                    }
                    

                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
        }
        
    }else{
        $errors['pass'] = "Invalid approach, please use the link that was sent to your email";
    }
	}else{
	    $errors['pass'] = "Invalid token, please use the token that was sent to your email";
	}
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <title>Email Verification Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	
<div class="container">
	<div class="row justify-content-center">
		<div class="col-lg-6 mt-5">
			<?php if (count($errors) > 0): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php foreach($errors as $error): ?> 
                <li style="color: red"><?php echo $error; ?></li>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  
                <?php endforeach; ?>
              </div>
              <?php endif; ?>

              <?php if (count($success) > 0): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php foreach($success as $succes): ?> 
                <li style="color: green"><?php echo $succes; ?></li>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  
                <?php endforeach; ?>
              </div>
              <?php endif; ?>


              <?php
              if(isset($_GET['v_code'])){
                $code = $_GET['v_code'];
                $sql = mysqli_query($conn, "SELECT verification_code FROM users WHERE verification_code = '$code'");
                if(mysqli_num_rows($sql) > 0){
                    
                ?>
                <div class="card shadow">
				<div class="card-header text-center">Thank you!</div>
				<div class="card-body">
					<p class="card-text">Hi, <?= $names ?></p>
					<p class="card-text">Your email has been verified successfully.</p>
					<p class="card-text">Kindly close the window.</p>
				</div>
				</div>         

               <?php
                    }else{
                        $errors['pass'] = "Invalid approach, please use the link that was sent to your email";
                    }
                   }else{
                   		$errors['pass'] = "Invalid token, please use the token that was sent to your email";
                   }
                ?>
		</div>
	</div>
</div>
<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>