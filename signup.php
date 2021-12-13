<?php 
session_start();
include 'signupController.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>One meta Signup Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-primary">

  <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
          <h1 class="text-center text-white mt-3 mb-4">Create an Account</h1>
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

          <div class="card w-100 trans shadow">
            <form method="POST" id="signup_form" action="signup.php">
             
              <div class="form-row mb-3 mt-3 mr-2 ml-2">
                <div class="col">
                  <label for="full_name">Username</label>
                 <input type="text" class="form-control" name="username" value="<?= $username ?? '' ?>" required="">
                </div>

                <div class="col">
                     <label for="Email">Email Address</label>
                     <input type="text" class="form-control" name="email" id="email" value="<?= $email ?? '' ?>" required>                  
                </div>
              </div>

              <div class="form-row mb-3 mt-3 mr-2 ml-2">
                <div class="col">
                  <label for="full_name">First Name</label>
                 <input type="text" class="form-control" name="firstname" value="<?= $firstname ?? '' ?>" required="">
                </div>

                <div class="col">
                     <label for="Email">Last Name</label>
                     <input type="text" class="form-control" name="lastname" id="lastname" value="<?= $lastname ?? '' ?>" required>                  
                </div>
              </div>

           <div class="form-row mb-3 mt-2 mr-2 ml-2">
                <div class="col">
                  <label for="Password">Password</label>
                  <div class="input-group mb-3" id="show_hide_password">
                    <input type="password" class="form-control" name="password" value="<?= $code ?? '' ?>" required aria-label="password" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <span class="input-group-text" id="basic-addon2"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                    </div>
                </div>
                  
                </div>

                <div class="col">
                   <label for="Password">Confirm Password</label>
                  <div class="input-group mb-3" id="confirm_password">
                    <input type="password" class="form-control"  name="confirmpassword" value="<?= $confirm ?? '' ?>" required aria-label="password" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <span class="input-group-text" id="basic-addon2"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                    </div>
                </div>
                               
                </div>
          </div>

          <div class="form-row mb-3 mt-2 mr-2 ml-2">
                <div class="col">

                  <div class="g-recaptcha w-100" data-sitekey="6LdTmk4cAAAAABdAY63Ks679Amk_TsqvCZkf5N3_"></div>
                </div>
          </div>

            <div class="form-row mb-3 mt-4 mr-2 ml-2">
                <div class="col">

                 <input type="submit" class="btn btn-outline-primary login w-100 mt-2 mb-3" id="signup_button" name="register" value="Sign up">
                </div>
          </div>

            </form>
          </div>

          <div class="card w-100 mt-3 mb-4 trans shadow">
            <p class="text-center mt-2">Already have an account?  <a href="signin.php">Login</a></p>
          </div>
        </div>
    </div>
  </div>


<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
 <script>
      "use strict";
          $(`option[data-code=NG]`).attr('selected','');
          
          $('select[name=country]').change(function(){
              $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
              $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
          });
          $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
          $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));

          $(document).ready(function() {
              $("#show_hide_password a").on('click', function(event) {
                  event.preventDefault();
                  if($('#show_hide_password input').attr("type") == "text"){
                      $('#show_hide_password input').attr('type', 'password');
                      $('#show_hide_password i').addClass( "fa-eye-slash" );
                      $('#show_hide_password i').removeClass( "fa-eye" );
                  }else if($('#show_hide_password input').attr("type") == "password"){
                      $('#show_hide_password input').attr('type', 'text');
                      $('#show_hide_password i').removeClass( "fa-eye-slash" );
                      $('#show_hide_password i').addClass( "fa-eye" );
                  }
              });

              $("#confirm_password a").on('click', function(event) {
                  event.preventDefault();
                  if($('#confirm_password input').attr("type") == "text"){
                      $('#confirm_password input').attr('type', 'password');
                      $('#confirm_password i').addClass( "fa-eye-slash" );
                      $('#confirm_password i').removeClass( "fa-eye" );
                  }else if($('#confirm_password input').attr("type") == "password"){
                      $('#confirm_password input').attr('type', 'text');
                      $('#confirm_password i').removeClass( "fa-eye-slash" );
                      $('#confirm_password i').addClass( "fa-eye" );
                  }
              });
          });
    </script>

</body>
</html>
