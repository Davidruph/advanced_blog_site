
<?php
    session_start();

    if(!isset($_SESSION['email'])) {
        //header("Location: signin.php");
    }
    else{
     
      //session variables for default login
    $id = $_SESSION['user'];
    $user = $_SESSION['username'];
    $lastname = $_SESSION['lastname'];
    $firstname = $_SESSION['firstname'];
    $fullname = $firstname." ".$lastname;
    $email = $_SESSION['email'];  
    $profile_image = $_SESSION['profile_image'];  
    }
    

    require 'dbconn.php';
    $errorss = array();
    $successs = array();

    //if suscribe button is clicked
    if (isset($_POST['subscribe'])) {
        $name = $_POST['name'];
        $subscriber_email = $_POST['subscriber_email'];
        $website = $_POST['website'];
        $postingdate = date("Y-m-d H:i:s", time());
        if ($name === "" || $subscriber_email === "") {
            $errorss['pass'] = "the name and email field are both required";
        }
    
        $query = mysqli_query($conn, "SELECT email FROM subscribers WHERE email='$subscriber_email'");
            if(mysqli_num_rows($query) > 0){
               $errorss['pass'] = "Hi, you've already subscribed";
            }else{
              $sql = 'INSERT INTO subscribers(name, email, website, PostingDate) VALUES(:name, :email, :website, :postingdate)';
              $statement = $connection->prepare($sql);
    
              if ($statement->execute([':name' => $name, ':email' => $subscriber_email, ':website' => $website, ':postingdate' => $postingdate])) {
                $successs['data'] = 'Subscribed successfully';
              }else{
                $errorss['data'] = 'Ooops, an error occured';
              }
            }
        
    }

$sql = 'SELECT * FROM affiliate WHERE Is_Active=1';
$statement = $connection->prepare($sql);
$statement->execute();
$affiliate = $statement->fetchAll(PDO::FETCH_ASSOC);

$sql = 'SELECT * FROM announcement WHERE Is_Active=1';
$statement = $connection->prepare($sql);
$statement->execute();
$announcement = $statement->fetchAll(PDO::FETCH_ASSOC);


function timeago($time, $tense='ago'){
    static $periods = array('year', 'month', 'day', 'hour', 'minute', 'second');

    if (!(strtotime($time)>0)) {
        //return trigger_error("wrong time format: '$time'", E_USER_ERROR);
    }

    $now = new DateTime('now');
    $time = new DateTime($time);

    $diff = $now->diff($time)->format('%y %m %d %h %i %s');
    $diff = explode(' ', $diff);
    $diff = array_combine($periods, $diff);
    $diff = array_filter($diff);

    $period = key($diff);
    $value = current($diff);
    if (!$value) {
        $period = '';
        $tense = '';
        $value = 'just now';
    }else{
        if ($period == 'day' && $value >= 7) {
            $period = 'week';
            $value = floor($value/7);
        }if ($value > 1) {
            $period .='s';
        }
    }

    return "$value $period $tense";
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>One meta home Page</title>
  
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  
</head>
<body>
<div class="container-fluid bg-warning">
    <div class="row justify-content-center">
        <?php foreach($affiliate as $aff): ?>
            <a href="<?php echo $aff["link"]; ?>" target = "_blank" class="btn btn-dark btn-sm text-white mr-2 mt-2 mb-2" alt="<?php echo $aff["alt"]; ?>"><?php echo $aff["alt"]; ?></a>
        <?php endforeach; ?>
    </div>
</div>

<section class="w-100 shadow-sm bg-dark">
  

<div class="container-fluid">

    <nav class="navbar navbar-expand-xl navbar-light text-white bg-transparent" data-aos="fade-left" data-aos-duration="500">
    <a class="navbar-brand" href="index.php">
    <img src="img/Logo.png" alt="logo" class="img-responsive" height="100" width="auto">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">

        <ul class="navbar-nav ml-auto">
            <li class="nav-item line">
                <a class="nav-link mr-1 text-white font-weight-bold active" href="index.php">Home</a>
            </li>
            <li class="nav-item line">
                <a class="nav-link mr-1 text-white font-weight-bold active" href="#">Trends</a>
            </li>
            <li class="nav-item line">
                <a class="nav-link mr-1 text-white font-weight-bold" href="#">Articles & Updates</a>
            </li>

            <li class="nav-item line">
                <a class="nav-link mr-1 text-white font-weight-bold" href="#">Top Ranks</a>
            </li>
            <li class="nav-item line">
                <a class="nav-link mr-1 text-white font-weight-bold" href="#">Reviews & Ratings</a>
            </li>
            
            <li class="nav-item line">
                <a class="nav-link mr-1 text-white font-weight-bold" href="#">Genre</a>
            </li>

            <li class="nav-item line">
                <a class="nav-link mb-2 text-white font-weight-bold" href="#">Reports</a>
            </li>

            <li class="nav-item line">
                <a class="nav-link mb-2 text-white font-weight-bold" href="#">Commentaries</a>
            </li>

            <?php
                if(!isset($_SESSION['email'])) {
                    ?>
                         <li class="nav-item line">
                            <a class="nav-link mb-2 text-white font-weight-bold" href="signin.php">Signin</a>
                        </li>

                        <li class="nav-item line">
                            <a class="nav-link mb-2 text-white font-weight-bold" href="signup.php">Sign Up</a>
                        </li>
                    <?php
                }
            ?>

        </ul>

        <?php
            if(isset($_SESSION['email'])) {
              ?>
               <ul class="navbar-nav ml-auto">
              <li class="nav-item dropdown">

              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <label for="" class="text-white">Hi,
                  <?php
                      echo "$user";
                                        
                  ?>
                
                </label>
                <?php
                  if(isset($_SESSION['email'])) {
                    echo '<img src="img/avatar.jpg" width="40" height="40" class="rounded-circle ml-2">';
                  }
                ?>
                
              </a>
              <div class="dropdown-menu bg-dark text-white" aria-labelledby="navbarDropdownMenuLink">
              <?php
                    if ($_SESSION['role'] === "admin") {
                        ?>
                            <a class="dropdown-item text-white bg-transparent" href="admin/index.php">My Account</a>
                        <?php
                    }else{
                        ?>
                        <a class="dropdown-item text-white bg-transparent" href="user/index.php">My Account</a>
                        <?php
                    }
                  ?>
                <a class="dropdown-item text-white bg-transparent" href="logout.php">Log Out</a>
              </div>
            </li>   
          </ul>
              <?php
            }
            
          ?>
    </div>
    </nav>

</div>
</section>

<div class="container-fluid bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="ticker w-100">
                <marquee class="news-content mt-3 mb-3">
                    <?php foreach($announcement as $ann): ?>
                        <p><?php echo $ann["announcement"]; ?></p>
                    <?php endforeach; ?>
                </marquee>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-7 mt-4 mb-4">
            <form class="card card-md shadow" action="search.php" method="post">
                <div class="card-body row no-gutters align-items-center">
                    <div class="col">
                        <input class="form-control form-control-lg form-control-borderless" name="searchcriteria" type="search" placeholder="Search Articles">
                    </div>
                    <!--end of col-->
                    <div class="col-auto ml-3">
                        <button class="btn btn-lg btn-dark" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                    <!--end of col-->
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="mt-5 mb-5">
    <ol class="breadcrumb mb-4 d-block" style="margin-top: 80px;">
            <li class="breadcrumb-item active">Article Search Results</li>

        </ol>
    </div>
    <div class="row justify-content-center">
    <?php

//if search button is clicked anywhere, select db for a match else echo error
if(isset($_POST['submit'])) {
$searchcriteria = $_POST['searchcriteria'];

$sql = 'SELECT * FROM article WHERE title LIKE :searchcriteria OR author LIKE :searchcriteria OR description LIKE :searchcriteria OR category LIKE :searchcriteria';
$statement = $connection->prepare($sql);
$statement->execute(array(':searchcriteria' => '%'.$searchcriteria.'%'));
$article = $statement->fetchAll(PDO::FETCH_OBJ);



if(sizeof($article) == 0){ 
  echo '<div class="container">
  <div class="alert alert-danger text-center">
          <li>Oooops...  No Record Found.</li>
  </div>
</div>
   ';
   
  }else{
  
    foreach ($article as $art) {
      // if a match is found, foreach of the records print them out with the below template
      $media = $art->image;
$temp = array();
$media=trim($media, '/,');
$temp   = explode(',', $media);
$temp   = array_filter($temp);
$images = array();
foreach($temp as $image){
$images[]="article_images/".trim( str_replace(array('[',']') ,"" ,$image ) );
}
 ?>

<div class="col-lg-3" id="blogs">
    <div class="card-deck h-100">
        <div class="card row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                        <img class="d-block w-100" src="<?php echo $images[0];?>" alt="Card image cap" style=" height: 30vh;">
                        </div>
                        <?php
                            if (sizeof($images) > 1) {
                                ?>
                                    <div class="carousel-item">
                                    <img class="d-block w-100" src="<?php echo $images[1];?>" alt="Card image cap" style=" height: 30vh;">
                                    </div>
                                <?php
                            }
                        ?>

                        <?php
                            if (sizeof($images) > 2) {
                                ?>
                                    <div class="carousel-item">
                                    <img class="d-block w-100" src="<?php echo $images[2];?>" alt="Card image cap" style=" height: 30vh;">
                                    </div>
                                <?php
                            }
                        ?>
                        
                        
                    </div>
                    </div>
        <div class="card-body text-justify">
            <h5 class="card-title article"><b><?php echo $art->title ?></h5></b>
            <p class="card-text"><small><b><?php echo $art->catchy_phrase ?>
            <p class="card-text"><small><b>Posted by <?php echo $art->author ?>, 

            <?php 

            //date_default_timezone_set('Africa/Lagos');
                $time_posted = $art->created_on;
            $time = date($time_posted); //now
            $timeago = timeago($time);
            echo $timeago; 
            ?>
            </b></small>
            
            <form action="article-details.php"  method="post">
            <input type="hidden" name="edit_id" value="<?php echo $art->id ?>">
                <button type="submit" name="btn_edit" class="btn btn-link btn-sm stretched-link"></button>
        </form>
        </div>
        </div>
    </div>
</div>
 <?php
}

}
}
?>
        
</div>

</div>

<div class="container-fluid mt-5 mb-3">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow h-auto">
                <div class="card-body text-center">
                    <p class="card-title">Ad will be placed here</p>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="footer bg-dark">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-4 mt-5">
                <a class="navbar-brand" href="index.php">
                <img src="img/Logo.png" alt="logo" class="img-responsive" height="100" width="auto">
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
                <h3 class="text-white mb-5 text-justify">Subscribe</h3>
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

                <form action="search.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" name="name" placeholder = "Your name" required value="<?= $fullname ?? '' ?>">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control form-control-sm" name="subscriber_email" placeholder = "Your email" required value="<?= $email ?? '' ?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" name="website" placeholder = "Your website (Optional)">
                </div>
                <input type="submit" name="subscribe" class="btn btn-primary btn-sm mb-2" value = "subscribe">
                    
                </form>

                <p class="text-justify text-white mt-2 mb-2">Get latest updates and offers.</p>
                <hr class="bg-white">
                <div class="row justify-content-center mt-4 mb-4 text-white">
                    <a href="" title="link to shop" class="btn btn-default border mr-2 icon"><i class="fas fa-store-alt"></i></a>
                    <a href="" class="btn btn-default border mr-2 icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="" class="btn btn-default border mr-2 icon"><i class="fab fa-twitter"></i></a>
                    <a href="" class="btn btn-default border mr-2 icon"><i class="fab fa-tiktok"></i></a>
                    <a href="" class="btn btn-default border mr-2 icon"><i class="fab fa-instagram"></i></a>
                    <a href="" class="btn btn-default border icon"><i class="fab fa-youtube"></i></a>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid bg-dark">
    <div class="row justify-content-center">
        <?php foreach($affiliate as $aff): ?>
            <a href="<?php echo $aff["link"]; ?>" target = "_blank" class="btn bg-dark btn-sm text-white mr-2 mt-2 mb-2" alt="<?php echo $aff["alt"]; ?>"><?php echo $aff["alt"]; ?></a>
        <?php endforeach; ?>
    </div>
</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    const searchButton = document.getElementById('search-button');
const searchInput = document.getElementById('search-input');
searchButton.addEventListener('click', () => {
  const inputValue = searchInput.value;
  alert(inputValue);
});
</script>


<script>
   
   (function() {
    const idleDurationSecs = 1800;
    const redirectUrl = 'logout.php';
    let idleTimeout;

    const resetIdleTimeout = function() {
        if(idleTimeout) clearTimeout(idleTimeout);
        idleTimeout = setTimeout(() => location.href = redirectUrl, idleDurationSecs * 1000);
    };
	
	// Key events for reset time
    resetIdleTimeout();
    window.onmousemove = resetIdleTimeout;
    window.onkeypress = resetIdleTimeout;
    window.click = resetIdleTimeout;
    window.onclick = resetIdleTimeout;
    window.touchstart = resetIdleTimeout;
    window.onfocus = resetIdleTimeout;
    window.onchange = resetIdleTimeout;
    window.onmouseover = resetIdleTimeout;
    window.onmouseout = resetIdleTimeout;
    window.onmousemove = resetIdleTimeout;
    window.onmousedown = resetIdleTimeout;
    window.onmouseup = resetIdleTimeout;
    window.onkeypress = resetIdleTimeout;
    window.onkeydown = resetIdleTimeout;
    window.onkeyup = resetIdleTimeout;
    window.onsubmit = resetIdleTimeout;
    window.onreset = resetIdleTimeout;
    window.onselect = resetIdleTimeout;
    window.onscroll = resetIdleTimeout;

})();
</script>
</body>
</html>