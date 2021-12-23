<?php
require 'functions/dbconn.php';

$sql = 'SELECT email FROM subscribers';
// $statement = $connection->prepare($sql);
// $statement->execute();
// $emails = $statement->fetchAll();

$errors = array();
$success = array();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

//if submit button is clicked and inputs are not empty
if (isset($_POST['submit'])) {
  $message = $_POST['message'];
 
  $mail = new PHPMailer(true);

    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;            
    $mail->Host = 'ssl://smtp.gmail.com:465';
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Username = 'oneupmeta.ray@gmail.com'; // Gmail address which you want to use as SMTP server
    $mail->Password = 'p@ssword123456'; // Gmail address Password
    $mail->Port = 465; //587
    $mail->SMTPSecure = 'ssl'; //tls
    $mail->setFrom('oneupmeta.ray@gmail.com', 'Updates/Offers'); // Gmail address which you used as SMTP server
    //$mail->debug = 2;
    $mail->isHTML(true);
    $mail->Subject = 'Message Received From (Oneupmeta)';
    $mail->Body = "<p>$message</p>";
    $mail->AltBody = '';
   foreach ($connection->query($sql) as $row) {
    $mail->AddAddress($row['email']);
    }

    if ($mail->Send()) 
        $success['data'] = 'your email has been sent successfully';

    else
         $errors['mail'] = 'Email Not sent';

}


  
  
?>

<?php
    //All header tag to be included
    include('include/header.php');

?>

<?php
    //sidebar tag to be included
    include('include/sidebar.php');
?>


<main>
    <div class="container-fluid">
        <h1 class="mt-4">Bulk  Mail </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Send Bulk Mail</li>
        </ol>
         <?php if (count($errors) > 0): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $error): ?> 
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (count($success) > 0): ?>
            <div class="alert alert-success">
              <?php foreach($success as $succes): ?> 
                <li class=""><?php echo $succes; ?></li>
              <?php endforeach; ?>
            </div>
        <?php endif; ?>

     <div class="card-box">
        <div class="col-md-12">
        <form class="form-horizontal" name="bulk email" method="post" autocomplete="off" action="all.php">

                <div class="form-group">
                    <label class="col-md-4 control-label">Message</label>
                    <div class="col-md-10">
                        <textarea class="form-control" rows="5" name="message" id="message"></textarea>
                    </div>
                </div>

                <div class="form-group">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-10">
              
            <button type="submit" class="btn btn-primary btn-block" name="submit">
                Send Bulk Mail
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

<script>
 
$("#message").summernote({
  placeholder: 'Enter Message here...',
        height: 100,
    });

</script>
 
