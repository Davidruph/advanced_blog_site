<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

//database connection
require 'dbconn.php';

    //error array for outputing errors
    $errors = array();

    //success array for outputing success messages
    $success = array();

    //if sign up button is clicked
if(isset($_POST['register'])){
    
    //variables
    $user_name = $_POST['username'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $code = $_POST['password'];
    $confirm = $_POST['confirmpassword'];
    
    //trim input tags
    $user_name = trim($user_name);
    $lastname = trim($lastname);
    $firstname = trim($firstname);
    $email = trim($email);
    $password = trim($code);
    $confirm = trim($confirm);
    $role = "user";
    $reg_date = date("Y-m-d H:i:s", time());
    $captcha = $_POST['g-recaptcha-response'];


    //list of reserved usernames
    $reserved = array("1upmeta", "oneupmeta", "admin", "root");

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


    //validate if any empty field
    if( $user_name === "" || $lastname === "" || $firstname === "" || $email === "" || $password === "" || $confirm === "") {
         $errors['fields'] = "All fields are Required";
    }
    //if the username user entered falls in list of reserved words, show error
    elseif(in_array($user_name, $reserved)) {
         $errors['user_name'] = "Sorry you cannot use this username";
    }
    //if username lesser than 8 characters
    elseif(strlen($user_name) < 8) {
        $errors['user_name'] = "Username cannot be lower than 8 characters";
    }
    //if username greater than 15 characters
    elseif(strlen($user_name) > 15) {
        $errors['user_name'] = "Username cannot exceed 15 characters";
    }
    //if username has non alphanumeric and symbols
    elseif(!preg_match('/^[a-zA-Z0-9_]+$/',$user_name)) {
      $errors['user_name'] = " Username can only contain alphanumeric characters and underscores";
    }
    //check for valid email
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $errors['email'] = "Email is invalid";
    }
    //check password if lessser than 8 characters
    elseif(strlen($password) < 8) {
        $errors['password'] = "Password cannot be lower than 8 characters";
    }
    //check password if greater than 127 characters
    elseif(strlen($password) > 127) {
        $errors['password'] = "Password maximum length is 127 characters";
    }
    //if password does not contain at least one digit
    elseif (!preg_match("/\d/", $password)) {
        $errors['password'] = "Password should contain at least one digit";
    }
    //if password does not contain at least one letter
    elseif (!preg_match("/[a-zA-Z]/", $password)) {
        $errors['password'] = "Password should contain at least one Letter";
    }
    //if password does not contain at least one special character
    elseif (!preg_match("/\W/", $password)) {
        $errors['password'] = "Password should contain at least one special character";
    }
    //if password has any white space
    elseif (preg_match("/\s/", $password)) {
        $errors['password'] = "Password should not contain any white space";
    }
    //if password not equal to confirm password
    elseif($password != $confirm) {
        $errors['passwordf'] = "Your passwords don't match";
    }
    else {
        //check if captcha was clicked and verified
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
        $sql = mysqli_query($conn, "SELECT username FROM users WHERE username='$user_name'");
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
             $query = mysqli_query($conn, "INSERT INTO users (username, lastname, firstname, email, password, registered_on, role) VALUES('$user_name','$lastname','$firstname','$email','$password','$reg_date','$role')");
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
                    $mail->setFrom('noreply@1upmeta.com', 'Email verification'); // Gmail address which you used as SMTP server
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
                        $user_name = $row['username'];
                        $lastname = $row['lastname'];
                        $firstname = $row['firstname'];
                        $pwd = $row['password'];
                        $email = $row['email'];
                        $role = $row['role'];
                        $profile_image = $row['profile_image'];
                        $code = $_POST['password'];
                        
                        //verify password
                        if(password_verify($code, $pwd)){
                            if($qry && $role === "admin"){
                                //declare session

                                 $_SESSION['user'] = $id;
                                $_SESSION['username'] = $user_name;
                                $_SESSION['lastname'] = $lastname;
                                $_SESSION['firstname'] = $firstname;
                                $_SESSION['email'] = $email;
                                $_SESSION['role'] = $role;
                                $_SESSION['profile_image'] = $profile_image;
                                header('location:admin/index.php');
                            }
                            if($qry && $role === "user"){
                                //declare session

                                 $_SESSION['user'] = $id;
                                $_SESSION['username'] = $user_name;
                                $_SESSION['lastname'] = $lastname;
                                $_SESSION['firstname'] = $firstname;
                                $_SESSION['email'] = $email;
                                $_SESSION['role'] = $role;
                                $_SESSION['profile_image'] = $profile_image;

                                //get user id from logged in session
                                $id = $_SESSION['user'];
                                $log_date_time = date("Y-m-d H:i:s", time());
                                $status = "In";

                                $log_query = mysqli_query($conn, "INSERT INTO log (user_id, log_date_time, status) VALUES('$id','$log_date_time','$status')");
                                    if($log_query){
                                        header('location:index.php');
                                    }
                                

                            }
                            
                                                   
                        }else {
                            $errors['username'] = "Incorrect Password";
            
                        } 
                    }else {
                            $errors['username'] = "The username and password you have entered doesn't match";
            
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