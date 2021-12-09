<?php
    //ob_start();
    //session_start();
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
                //$success['confirm'] = "You are now registered <a href='signin.php'>Login Here</a>";
                //if user registers, save data in session
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
                        header('location:index.php');
                    }
                    
                                           
                }else {
                        $errors['username'] = "Incorrect Password";
    
                } 
            }else {
                        $errors['username'] = "User not found";
    
                }
            }else {
              $errors['pass'] = "Error Registering";
            }
        }
     }
    }

}
?>