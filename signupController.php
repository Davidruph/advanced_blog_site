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

if(isset($_POST['register'])){
    
    //variables
    $username = $_POST['username'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $code = $_POST['password'];
    $confirm = $_POST['confirmpassword'];
    
    //trim input tags
    $username = trim($username);
    $lastname = trim($lastname);
    $firstname = trim($firstname);
    $email = trim($email);
    $password = trim($code);
    $confirm = trim($confirm);
    $role = "user";
    $reg_date = date("Y-m-d H:i:s", time());
    $captcha = $_POST['g-recaptcha-response'];

    //email verification variables
    $str = '0089773bcghucjdJFGJDNDTEMNVgdhdhjabcdef0987654321';
    $token = str_shuffle($str);
    $link="http://localhost/fiverr/oneupmeta/verify_email.php?v_code=$token";
    $message='
    <div>
    <b><p>Hi, '.$firstname.' '.$lastname.'</p></b>
    <p>You registered on oneupmeta website.</p>
    <p>pls kindly verify your email via link below.</p>
    <p><a href="'.$link.'">Click to verify</a></p>
    </div>
    ';
    
    //validate
    if( $username === "" || $lastname === "" || $firstname === "" || $email === "" || $password === "" || $confirm === "") {
         $errors['fields'] = "All fields are Required";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $errors['email'] = "Email is invalid";
    }
    elseif(strlen($password) < 8) {
        $errors['password'] = "Password too short";
    }
    elseif($password != $confirm) {
        $errors['passwordf'] = "Passwords don't match";
    }


    else {
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

        //check if username exist
        $sql = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
        if(mysqli_num_rows($sql) > 0){
           $errors['pass'] = "Hi, this username has already been taken";
        }else{

        //check if email exist
        $query = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
        if(mysqli_num_rows($query) > 0){
           $errors['pass'] = "Hi, this email already exists";
        }
        else{
            //hash password
            $password = password_hash($code, PASSWORD_DEFAULT);
             $query = mysqli_query($conn, "INSERT INTO users (username, lastname, firstname, email, password, registered_on, role) VALUES('$username','$lastname','$firstname','$email','$password','$reg_date','$role')");
            if($query){
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
                    $mail->setFrom('oneupmeta.ray@gmail.com', 'Email verification'); // Gmail address which you used as SMTP server
                    //$mail->debug = 2;
                    $mail->isHTML(true);
                    $mail->Subject = 'Message Received From (oneupmeta)';
                    $mail->Body = "$message";
                    $mail->AltBody = '';
                    

                    if ($mail->Send()){
                           //if email verification link is sent and user registers, save data in session
                        $v_query = mysqli_query($conn, "UPDATE users SET verification_code = '$token' WHERE email = '$email'");
                        $qry = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
                        if(mysqli_num_rows($qry) > 0){
                        $row = mysqli_fetch_array($qry);
                        $id = $row['id'];
                        $username = $row['username'];
                        $lastname = $row['lastname'];
                        $firstname = $row['firstname'];
                        $pwd = $row['password'];
                        $email = $row['email'];
                        $role = $row['role'];
                        $code = $_POST['password'];
                        
                        //verify password
                        if(password_verify($code, $pwd)){
                            if($qry && $role === "admin"){
                                //declare session

                                 $_SESSION['user'] = $id;
                                $_SESSION['username'] = $username;
                                $_SESSION['lastname'] = $lastname;
                                $_SESSION['firstname'] = $firstname;
                                $_SESSION['email'] = $email;
                                $_SESSION['role'] = $role;
                                header('location:admin/index.php');
                            }
                            if($qry && $role === "user"){
                                //declare session

                                 $_SESSION['user'] = $id;
                                $_SESSION['username'] = $username;
                                $_SESSION['lastname'] = $lastname;
                                $_SESSION['firstname'] = $firstname;
                                $_SESSION['email'] = $email;
                                $_SESSION['role'] = $role;

                                //get user id from logged in session
                                $id = $_SESSION['user'];
                                $log_date_time = date("Y-m-d H:i:s", time());
                                $status = "In";

                                $log_query = mysqli_query($conn, "SELECT * FROM log WHERE user_id='$id'");
                                if(mysqli_num_rows($log_query) > 0){

                                    $update_query = mysqli_query($conn, "UPDATE log SET user_id = '$id', log_date_time = '$log_date_time', status = '$status' WHERE user_id = '$id'");
                                    if($update_query){
                                        header('location:index.php');
                                    }

                                }else{
                                    //update log details
                                    $log_query = mysqli_query($conn, "INSERT INTO log (user_id, log_date_time, status) VALUES('$id','$log_date_time','$status')");
                                    if($log_query){
                                        header('location:index.php');
                                    }
                                }

                            }
                            
                                                   
                        }else {
                            $errors['username'] = "Incorrect Password";
            
                        } 
                    }else {
                            $errors['username'] = "User not found";
            
                        }
                    }
                    else{

                        $errors['mail'] = 'Email Not sent';
                    }
                    

                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                
            }else {
              $errors['pass'] = "Error Registering";
            }
        }
     }
    }
}
}
?>