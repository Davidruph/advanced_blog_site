<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

//db connection included
require '../dbconn.php';

$errors = array();
$success = array();

//if submit button is clicked and inputs are not empty
if (isset ($_POST['submit'])){

  $user_name = $_POST['username'];
  $lastname = $_POST['lastname'];
  $firstname = $_POST['firstname'];
  $email = $_POST['email'];
  $code = $_POST['password'];
  $id = $_SESSION['user'];
  $captcha = $_POST['g-recaptcha-response'];
  $link="http://localhost/fiverr/oneupmeta/signin.php";
  $message='
    <div>
    <b><p>Hi, '.$firstname.' '.$lastname.'</p></b>
    <p>You updated your profile information on oneupmeta website.</p>
    <p>If you did not initiate this, kindly sign in and  change your password through your dashboard.</p>
    <p><a href="'.$link.'">Go to website</a></p>
    </div>
    ';

  //list of reserved usernames
  $reserved = array("1upmeta", "oneupmeta", "admin", "root");


    if($user_name === "" || $lastname === "" || $firstname === "" || $email === "" || $code === "") {
         $errors['fields'] = "All fields are Required";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $errors['email'] = "Email is invalid";
    }
    elseif(in_array($user_name, $reserved)) {
         $errors['user_name'] = "Sorry you cannot use this username";
    }
    elseif(strlen($user_name) < 8) {
        $errors['user_name'] = "Username cannot be lower than 8 characters";
    }
    elseif(strlen($user_name) > 15) {
        $errors['user_name'] = "Username cannot exceed 15 characters";
    }
    elseif(!preg_match('/^[a-zA-Z0-9_]+$/',$user_name)) {
      $errors['user_name'] = " Username can only contain alphanumeric characters and underscores";
    }

    else{
      if($captcha === "") {
                $errors['captcha'] = "Captcha is required";
            }
            elseif(!empty($captcha)){
             $secret_key = '6LdTmk4cAAAAAJn9MtDme2NEAFBSGUJAjR3zuBK-';
              $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
              $response_data = json_decode($response);
              if(!$response_data->success){
                  $errors['captcha'] = 'Captcha verification failed';
              }

            $query = mysqli_query($conn, "SELECT username FROM users WHERE username='$user_name' AND id != $id");
            if(mysqli_num_rows($query) > 0){
               $errors['pass'] = "Pls this username has been used";
            }else{

                $qry = mysqli_query($conn, "SELECT email FROM users WHERE email='$email' AND id != $id");
                if(mysqli_num_rows($qry) > 0){
                   $errors['pass'] = "Pls this email has been used";
                }else{


                    $qry = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
                        if(mysqli_num_rows($qry) > 0){
                        $row = mysqli_fetch_array($qry);
                        //$id = $row['id'];
                        $user = $row['username'];
                        $last = $row['lastname'];
                        $first = $row['firstname'];
                        $pwd = $row['password'];
                        $user_email = $row['email'];
                        $code = $_POST['password'];
                        
                        //verify password
                        if(password_verify($code, $pwd)){
                            if ($user != $user_name || $last != $lastname || $first != $firstname || $user_email != $email) {

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
                                        $mail->setFrom('oneupmeta.ray@gmail.com', 'Profile Update'); // Gmail address which you used as SMTP server
                                        //$mail->debug = 2;
                                        $mail->isHTML(true);
                                        $mail->Subject = 'Message Received From (Oneupmeta)';
                                        $mail->Body = "$message";
                                        $mail->AltBody = '';

                                        if ($mail->Send()){
                                          $sql = 'UPDATE users SET username=:user_name, lastname=:lastname, firstname=:firstname, email=:email WHERE id=:id';
                                            $statement = $connection->prepare($sql);
                                            if ($statement->execute([':user_name' => $user_name, ':lastname' => $lastname, ':firstname' => $firstname, ':email' => $email, ':id' => $id])) {
                                                 
                                                $success['data'] = "your details has been updated successfully";
                                            }else{
                                              $errors['data'] = 'Ooops, an error occured';
                                            }
                                        }else{
                                           $errors['mail'] = 'Error occured';
                                        }
                                      
                                    } catch (Exception $e) {
                                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                    }

                              
                            }else{
                              $sql = 'UPDATE users SET username=:user_name, lastname=:lastname, firstname=:firstname, email=:email WHERE id=:id';
                              $statement = $connection->prepare($sql);
                              if ($statement->execute([':user_name' => $user_name, ':lastname' => $lastname, ':firstname' => $firstname, ':email' => $email, ':id' => $id])) {
                                   $success['data'] = "your details has been updated successfully";

                              }else{
                                $errors['data'] = 'Ooops, an error occured';
                              }
                            }

                          
                        }else {
                            $errors['username'] = "Incorrect Password";
                        }

                   }else {
                        $errors['username'] = "User not found";
        
                    }
                }

    }

  }
 }
}



?>