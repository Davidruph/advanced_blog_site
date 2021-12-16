<?php
session_start();
$errors = array();
$success = array();
require 'dbconn.php';

if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
}

?>

<?php
if (isset($_GET['reset_code'])) {
    $code = $_GET['reset_code'];
}

 
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];
        $defas = 0;
        
        if(empty($password)){
            $errors['tests'] = "Password field cannot be empty"; 
    }
    if(empty($confirm)){
        $errors['tests'] = "Re-type password field cannot be empty"; 
    }
    if($password !== $confirm){
        $errors['tests'] = "the two password did not match"; 
    }else{
         $pwd=password_hash($password, PASSWORD_DEFAULT);
           $query = mysqli_query($conn, "UPDATE users SET password = '$pwd' WHERE email = '$email'");
          
           if($query){
                $query_attempt = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
                if(mysqli_num_rows($query_attempt) > 0){
                    $details = mysqli_fetch_array($query_attempt);
                    $user_id = $details['id'];
                    $email = $_SESSION['email'];
                    $qry = mysqli_query($conn, "UPDATE users SET verification_code = $defas WHERE email = '$email'");
                    $sql = mysqli_query($conn, "DELETE FROM failed_attempts WHERE user_id = $user_id");
                    $success['testt'] = "Password has been changed";
                    session_destroy();
                    header('location:signin.php');
                    
                }
               
           }else{
               $errors['tests'] = "an error occured";
           }
    }
    
    
    }   
 ?>

 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-white">

  <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4">
          <h1 class="text-center text-dark mt-5 mb-4">Password Reset</h1>
           <?php if (count($errors) > 0): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php foreach($errors as $error): ?> 
                <li class="text-danger"><?php echo $error; ?></li>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  
                <?php endforeach; ?>
              </div>
              <?php endif; ?>

              <?php if (count($success) > 0): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php foreach($success as $succes): ?> 
                <li class="text-success"><?php echo $succes; ?></li>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
          <div class="w-100 shadow trans card">
           
            <form method="POST" action="reset_password.php">
                 <?php
                 if(isset($_GET['reset_code'])){
                 $code = $_GET['reset_code'];
                    $sql = mysqli_query($conn, "SELECT verification_code FROM users WHERE verification_code = '$code'");
                            if(mysqli_num_rows($sql) > 0){
                                
                                ?>
                                 <div class=" form-group mt-4 mr-4 ml-4">
                                     <label for="Username">Enter New Password</label>
                              <div class="input-group" id="show_password">
                                    <input type="password" class="form-control"  name="password" required aria-label="password" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                      <span class="input-group-text" id="basic-addon2"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                                    </div>
                                </div>
                              </div>
                              
                              <div class=" form-group mt-4 mr-4 ml-4">
                                     <label for="Username">Re-enter Password</label>
                              <div class="input-group" id="show_new_password">
                                    <input type="password" class="form-control" name="confirm_password" required aria-label="password" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                      <span class="input-group-text" id="basic-addon2"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                                    </div>
                                </div>
                              </div>
             

              <div class="form-group mr-4 ml-4">
                <input type="submit" class="btn btn-outline-primary login w-100 mt-2 mb-3" name="submit" value="Change Password">
              </div>

               <?php
                    }else{
                        $errors['pass'] = "Invalid approach, please use the link that was sent to your email";
                    }
                    }else{
                        $errors['pass'] = "Invalid token, please use the token that was sent to your email";
                   }
                ?>
            </form>
          </div>
          <div class="trans card w-100 mt-3 shadow">
            <p class="text-center mt-2"><a href="signin.php">Go to login</a></p>
          </div>
        </div>
    </div>
  </div>





<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
  $(document).ready(function() {
              $("#show_password a").on('click', function(event) {
                  event.preventDefault();
                  if($('#show_password input').attr("type") == "text"){
                      $('#show_password input').attr('type', 'password');
                      $('#show_password i').addClass( "fa-eye-slash" );
                      $('#show_password i').removeClass( "fa-eye" );
                  }else if($('#show_password input').attr("type") == "password"){
                      $('#show_password input').attr('type', 'text');
                      $('#show_password i').removeClass( "fa-eye-slash" );
                      $('#show_password i').addClass( "fa-eye" );
                  }
              });
              
              $("#show_new_password a").on('click', function(event) {
                  event.preventDefault();
                  if($('#show_new_password input').attr("type") == "text"){
                      $('#show_new_password input').attr('type', 'password');
                      $('#show_new_password i').addClass( "fa-eye-slash" );
                      $('#show_new_password i').removeClass( "fa-eye" );
                  }else if($('#show_new_password input').attr("type") == "password"){
                      $('#show_new_password input').attr('type', 'text');
                      $('#show_new_password i').removeClass( "fa-eye-slash" );
                      $('#show_new_password i').addClass( "fa-eye" );
                  }
              });
          });
</script>

</body>
</html>