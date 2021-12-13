<?php

require 'dbconn.php';
$errors = array();

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