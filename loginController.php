<?php
require 'dbconn.php';
$errors = array();

//code start for login with google

//Include Configuration File
include('config.php');

$login_with_google_button = '';

if(isset($_GET["code"]))
{
 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
 if(!isset($token['error']))
 {
  $google_client->setAccessToken($token['access_token']);
  $_SESSION['access_token'] = $token['access_token'];
  $google_service = new Google_Service_Oauth2($google_client);

  $data = $objOAuthService->userinfo->get();

  if(!empty($data['given_name']))
  {
   $_SESSION['user_first_name'] = $data['given_name'];
  }

  if(!empty($data['family_name']))
  {
   $_SESSION['user_last_name'] = $data['family_name'];
  }

  if(!empty($data['email']))
  {
   $_SESSION['user_email_address'] = $data['email'];
  }

  if(!empty($data['gender']))
  {
   $_SESSION['user_gender'] = $data['gender'];
  }

  if(!empty($data['picture']))
  {
   $_SESSION['user_image'] = $data['picture'];
  }
 }
}

if(!isset($_SESSION['access_token']))
{

 $login_with_google_button = '<a class="btn btn-outline-danger w-100" href="'.$google_client->createAuthUrl().'"><i class="fa fa-google mr-3"></i>Login With Google</a>';
}
//end code for login with google

//if default login button is clicked
if(isset($_POST['submit'])){
     $username = $_POST['username'];
     $password = $_POST['password'];
     $captcha = $_POST['g-recaptcha-response'];
     
     $username = trim($username);
     $password = trim($password);
   
    if($username === "") {
        $errors['username'] = "username is required";
    }
    if($password === "") {
        $errors['password'] = "Password is required";
    }
    elseif(strlen($password) < 6) {
        $errors['password'] = "password too short";
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
  
        $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_array($query);
            $id = $row['id'];
            $username = $row['username'];
            $lastname = $row['lastname'];
            $firstname = $row['firstname'];
            $pwd = $row['password'];
            $email = $row['email'];
            $role = $row['role'];
            $code = $_POST['password'];
            
            if(password_verify($password, $pwd)){
               if($query && $role === "admin"){
                        //declare session

                         $_SESSION['user'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['lastname'] = $lastname;
                        $_SESSION['firstname'] = $firstname;
                        $_SESSION['email'] = $email;
                        $_SESSION['role'] = $role;
                        header('location:admin/index.php');
                        exit();
                    }
                    if($query && $role === "user"){
                        //declare session

                         $_SESSION['user'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['lastname'] = $lastname;
                        $_SESSION['firstname'] = $firstname;
                        $_SESSION['email'] = $email;
                        $_SESSION['role'] = $role;
                        header('location:index.php');
                    }
                
            }else {
                    $errors['username'] = "Incorrect Password";

            } 
        }else {
                    $errors['username'] = "User not found";

            }

    }

    

}
?>