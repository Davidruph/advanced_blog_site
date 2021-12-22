<?php

require 'dbconn.php';
$errors = array();

//if default login button is clicked
if(isset($_POST['submit'])){
     $user_name = $_POST['username'];
     $password = $_POST['password'];
     $captcha = $_POST['g-recaptcha-response'];
     
     $user_name = trim($user_name);
     $password = trim($password);
   
    if($user_name === "") {
        $errors['username'] = "username is required";
    }
    elseif($password === "") {
        $errors['password'] = "Password is required";
    }
    
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
  
        $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$user_name' OR email='$user_name'");
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_array($query);
            $id = $row['id'];
            $user_name = $row['username'];
            $lastname = $row['lastname'];
            $firstname = $row['firstname'];
            $pwd = $row['password'];
            $email = $row['email'];
            $role = $row['role'];
            $profile_image = $row['profile_image'];
            $code = $_POST['password'];
            
            if(password_verify($password, $pwd)){
               if($query && $role === "admin"){
                        //declare session

                         $_SESSION['user'] = $id;
                        $_SESSION['username'] = $user_name;
                        $_SESSION['lastname'] = $lastname;
                        $_SESSION['firstname'] = $firstname;
                        $_SESSION['email'] = $email;
                        $_SESSION['role'] = $role;
                        $_SESSION['profile_image'] = $profile_image;
                        header('location:admin/index.php');
                        exit();
                    }
                    if($query && $role === "user"){
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
                                header('location:user/index.php');
                            }
                        }

                    }
                
            }else {
                    //$errors['username'] = "Incorrect Password";

                    //if password failed count
                      $id = $row['id'];
                      $login_attempt = 1;

                      $fail_query = mysqli_query($conn, "SELECT * FROM failed_attempts WHERE user_id='$id'");
                        if(mysqli_num_rows($fail_query) > 0){

                            $attempt = mysqli_fetch_array($fail_query);
                            $last_attempt = $attempt['login_attempt'];
                            $new_attempt = $last_attempt + 1;

                            if ($last_attempt > 5) {
                                $errors['password'] = "You've made more than 5 unsuccessful attempt to login to this account, if you've forgotten your password, kindly change password via this &nbsp;<a href='forgot_password.php'>link</a>";
                            }else{
                                $update_query = mysqli_query($conn, "UPDATE failed_attempts SET login_attempt = '$new_attempt' WHERE user_id = '$id'");
                                if($update_query){
                                    $errors['username'] = "Incorrect Password";

                                }
                            }

                        }else{
                            //update failed_attempts details
                            $log_query = mysqli_query($conn, "INSERT INTO failed_attempts (user_id, login_attempt) VALUES('$id','$login_attempt')");
                            if($log_query){
                                $errors['username'] = "Incorrect Password";
                            }
                        }

            } 
        }else {
                    $errors['username'] = "User not found";

            }

    }

    

}
?>