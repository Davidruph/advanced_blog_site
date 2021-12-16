
<?php
    session_start();

    if(!isset($_SESSION['email'])) {
        header("Location: signin.php");
    }
    else{
     
      //session variables for default login
    $id = $_SESSION['user'];
    $user = $_SESSION['username'];
    $lastname = $_SESSION['lastname'];
    $firstname = $_SESSION['firstname'];
    $email = $_SESSION['email'];    
    }
    

    require 'dbconn.php';
    $errorss = array();
    $successs = array();

    //if suscribe button is clicked
if (isset($_POST['suscribe'])) {
    $suscriber_email = $_POST['suscriber_email'];
    $postingdate = date("Y-m-d H:i:s", time());

    $query = mysqli_query($conn, "SELECT email FROM suscribers WHERE email='$suscriber_email'");
        if(mysqli_num_rows($query) > 0){
           $errorss['pass'] = "Hi, you've already suscribed";
        }else{
          $sql = 'INSERT INTO suscribers(email, PostingDate) VALUES(:email, :postingdate)';
          $statement = $connection->prepare($sql);

          if ($statement->execute([':email' => $suscriber_email, ':postingdate' => $postingdate])) {
            $successs['data'] = 'Suscribed successfully';
          }else{
            $errorss['data'] = 'Ooops, an error occured';
          }
        }
    
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>One meta home Page</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  
  
</head>
<body>

<section class="w-100 bg-dark mb-5">
  

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light text-white bg-dark sticky-top">
    <a class="navbar-brand" href="index.php">
    <i class="fas fa-infinity fa-spin text-white" style="font-size:60px"></i>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">

        <ul class="navbar-nav ml-auto">
            
            <li class="nav-item">
                <a class="nav-link mr-1 text-white font-weight-bold" href="">Announcements</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mr-1 text-white font-weight-bold" href="">Events</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link mr-1 text-white font-weight-bold" href="">Blog</a>
            </li>

            <li class="nav-item active">
                <a class="nav-link mb-2 text-white font-weight-bold" href="">Contact</a>
            </li>
           

        </ul>
        <ul class="navbar-nav ml-auto">
              <li class="nav-item dropdown">

              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <label for="" class="text-white">Hi,
                  <?php
                    if(isset($_SESSION['email'])) {
                      echo "$user";
                    }elseif(isset($_SESSION['google_email'])) {
                      echo "$google_name";
                    }
                    
                  ?>
                
                </label>
                <?php
                  if(isset($_SESSION['email'])) {
                    echo '<img src="https://s3.eu-central-1.amazonaws.com/bootstrapbaymisc/blog/24_days_bootstrap/fox.jpg" width="40" height="40" class="rounded-circle ml-2">';
                  }
                ?>
                
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="logout.php">Log Out</a>
              </div>
            </li>   
          </ul>
    </div>
    </nav>

</div>
</section>

<br>
<br><br><br><br><br><br><br><br><br><br><br><br>
<div class="footer bg-dark">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-4 mt-5">
                <a class="navbar-brand" href="index.php">
                    <img src="img/meta.png" alt="logo" class="img-fluid header_pic">
                </a>
                <p class="text-white footer-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum delenit augue duis dolore te feugait. <a class="text-decoration-none" href="aboutus.php">Read More &nbsp;<i class="fas fa-long-arrow-alt-right"></i></a> </p>
            </div>

            <div class="col-lg-2 mt-5">
                <h3 class="text-white mb-5 text-justify">Explore</h3>
                <li class="mb-2 text-white text-justify ml-2"><a href="#" class="text-white">About Us</a></li>
                <li class="mb-2 text-white text-justify ml-2"><a href="#" class="text-white">Events</a></li>
                <li class="mb-2 text-white text-justify ml-2"><a href="#" class="text-white">Publications</a></li>
                <li class="mb-2 text-white text-justify ml-2"><a href="#" class="text-white">Contact</a></li>
            </div>

            <div class="col-lg-2 mt-5">
                <h3 class="text-white mb-5 text-justify">Activities</h3>
                <li class="mb-2 text-white text-justify"><a href="" class="text-white">Press Releases</a></li>
                <li class="mb-2 text-white text-justify"><a href="" class="text-white">Multimedia</a></li>
                <li class="mb-2 text-white text-justify"><a href="" class="text-white">Blog</a></li>
                <li class="mb-2 text-white text-justify"><a href="" class="text-white">LSA in the Media</a></li>
            </div>

            <div class="col-lg-4 mt-5">
                <h3 class="text-white mb-5 text-justify">Suscribe</h3>
                         <?php if (count($errorss) > 0): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php foreach($errorss as $error): ?> 
                        <li class="text-danger"><?php echo $error; ?></li>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                          
                        <?php endforeach; ?>
                      </div>
                      <?php endif; ?>

                      <?php if (count($successs) > 0): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php foreach($successs as $succes): ?> 
                        <li class="text-success"><?php echo $succes; ?></li>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                          
                        <?php endforeach; ?>
                      </div>
                      <?php endif; ?>

                <form action="index.php" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="suscriber_email" value="<?= $email ?? '' ?>" required placeholder="Your email" aria-label="blogpient's email" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                        <input type="submit" name="suscribe" class="btn btn-danger" value="suscribe">
                        </div>
                    </div>
                </form>

                <p class="text-justify text-white mt-2 mb-2">Get latest updates and offers.</p>
                <hr class="bg-white">
                <div class="row justify-content-center mt-4 mb-4 text-white">
                    <a href="" class="btn btn-default border mr-2 icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="" class="btn btn-default border mr-2 icon"><i class="fab fa-twitter"></i></a>
                    <a href="" class="btn btn-default border mr-2 icon"><i class="fab fa-dribbble"></i></a>
                    <a href="" class="btn btn-default border icon"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>