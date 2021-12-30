<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

    //All header tag to be included
    include('include/header.php');
?>

<?php
require '../dbconn.php';

$errors = array();
$success = array();

if (isset($_POST['submit'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $id = $_SESSION['user'];

        if($old_password === "" || $new_password === "" || $confirm_password === "") {
            $errors['fields'] = "All fields are Required";
        }
        elseif($new_password != $confirm_password) {
            $errors['passwordf'] = "Your two passwords don't match";
        }
        elseif(strlen($new_password) < 8) {
        $errors['password'] = "Password cannot be lower than 8 characters";
        }
        elseif(strlen($new_password) > 127) {
            $errors['password'] = "Password maximum length is 127 characters";
        }
        elseif(!preg_match('/^(?=.*\d)(?=.*[a-zA-Z])(?=.*[!#\$%&\?]).{8,}$/',$new_password)) {
          $errors['password'] = "Password must contain a letter, a number and a symbol";
        }else{
           $query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
            if(mysqli_num_rows($query) > 0){
                $row = mysqli_fetch_array($query);
                $id = $row['id'];
                $old_pwd = $row['password'];
                $lastname = $row['lastname'];
                $firstname = $row['firstname'];
                $message='
                <div>
                <b><p>Hello, '.$firstname.' '.$lastname.'</p></b>
                <p>Your password was changed successfully on oneupmeta website.</p>
                </div>
                ';

                 if(password_verify($old_password, $old_pwd)){
                   $new_password = password_hash($new_password, PASSWORD_DEFAULT);
                      $sql = mysqli_query($conn, "UPDATE users SET password = '$new_password' WHERE id = '$id'");
                      if ($sql) {
                              $success['data'] = "your password was changed successfully";
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
                              $mail->setFrom('oneupmeta.ray@gmail.com', 'Password Change'); // Gmail address which you used as SMTP server
                              $mail->debug = 2;
                              $mail->isHTML(true);
                              $mail->Subject = 'Message Received From (Oneupmeta)';
                              $mail->Body = "$message";
                              $mail->AltBody = '';
                              $mail->Send();
                            
                          } catch (Exception $e) {
                              echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                          }
                          session_destroy();
                          //header('location: ../signin.php');
                      }
                                        
                 }else{
                    $errors['password'] = "Incorrect password or user does not exist";
                 }
             }
        }
    }
?>

<?php
    //sidebar tag to be included
    include('include/sidebar.php');
?>


<main>
    <div class="container-fluid">
        <h1 class="mt-4">Password</h1>
        <ol class="breadcrumb mb-5">
            <li class="breadcrumb-item active">Change password Page</li>
        </ol>
          <!-- if there is an error, echo all of them -->
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

        <!-- if there is success, echo all of them -->
       <?php if (count($success) > 0): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php foreach($success as $succes): ?> 
                <li class="text-green"><?php echo $succes; ?></li>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  
                <?php endforeach; ?>
              </div>
              <?php endif; ?>

     <div class="card-box">
        <div class="col-md-9">

        <form class="form-horizontal mb-4" method="post" autocomplete="off" action="change_password.php">
          
          <div class="row mb-3">
            <div class="col">
              <label for="full_name">Old Password</label>
              <input type="password" class="form-control" name="old_password" required>
            </div>
          </div>

           <div class="row mb-3">
            <div class="col">
              <label for="full_name">New Password</label>
              <input type="password" class="form-control" name="new_password" required>
            </div>
          </div>     

          <div class="row mb-3">
            <div class="col">
              <label for="full_name">Confirm New Password</label>
              <input type="password" class="form-control" name="confirm_password" required>
            </div>
          </div>       
          
           <div class="row mt-3">
                <div class="col">
                    <button type="submit" class="btn btn-primary btn-block" name="submit">
                        Change
                    </button>
                </div>
            </div>              

        </form>
</div>
</div>

        
    </div>
</main>
            
<?php
    //footer tag to be included
    include('include/footer.php');
?>

<?php
    //javascripts files to be included
    include('include/scripts.php');
?>
