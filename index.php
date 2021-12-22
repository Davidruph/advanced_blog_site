
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
    $email = $_SESSION['email'];  
    $profile_image = $_SESSION['profile_image'];  
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

$sql = 'SELECT * FROM affiliate WHERE Is_Active=1';
$statement = $connection->prepare($sql);
$statement->execute();
$affiliate = $statement->fetchAll(PDO::FETCH_ASSOC);

$sql = 'SELECT * FROM announcement WHERE Is_Active=1';
$statement = $connection->prepare($sql);
$statement->execute();
$announcement = $statement->fetchAll(PDO::FETCH_ASSOC);

$sql = 'SELECT * FROM article WHERE Is_Active=1 ORDER BY id DESC LIMIT 7';
$statement = $connection->prepare($sql);
$statement->execute();
$article = $statement->fetchAll(PDO::FETCH_ASSOC);

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
            <a href="<?php echo $aff["link"]; ?>" target = "_blank" class="btn btn-dark btn-sm text-white mr-2 mt-2 mb-2" alt="<?php echo $aff["alt"]; ?>"><?php echo $aff["link"]; ?></a>
        <?php endforeach; ?>
    </div>
</div>

<section class="w-100 shadow-sm bg-dark">
  

<div class="container-fluid">

    <nav class="navbar navbar-expand-xl navbar-light text-white bg-transparent" data-aos="fade-left" data-aos-duration="500">
    <a class="navbar-brand" href="index.php">
        <img src="img/Logo.png" alt="" class="img-responsive" height="100" width="auto">
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
                    echo '<img src="https://s3.eu-central-1.amazonaws.com/bootstrapbaymisc/blog/24_days_bootstrap/fox.jpg" width="40" height="40" class="rounded-circle ml-2">';
                  }
                ?>
                
              </a>
              <div class="dropdown-menu bg-dark text-white" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item text-white bg-transparent" href="user/index.php">Dashboard</a>
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
                        <button class="btn btn-lg btn-dark" type="submit" name="submit"><i class="fa fa-search"></i></button>
                    </div>
                    <!--end of col-->
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="mt-5 mb-5">
        <h5 class="text-center">OUR NEWS ARTICLE</h5>
    </div>
    <div class="row justify-content-center">

        <?php foreach($article as $art): //php fetch blog post from database?>
        <div class="col-lg-3" id="blogs">
            <div class="card-deck h-100">
              <div class="card row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                 <div class="embed-responsive embed-responsive-16by9">
                     <?php
                        $media = $art['image'];
                        $video_format = array(".avi", ".giv", ".mp4", ".mov", ".AVI", ".GIV", ".MP4", ".MOV");
                        if(in_array($media, $video_format)) {
                            ?>
                            <video class="card-img-top embed-responsive-item" autoplay controls> <source src='admin/article_images/<?php echo $art['image']; ?>' type='video/mp4'> </video>"
                            <?php
                       }else {
                           ?>
                            <img class="card-img-top embed-responsive-item" src="admin/article_images/<?php echo $art['image']; ?>" alt="Card image cap">

                            <?php
                       }

                     ?>
                     
                 </div>
                <div class="card-body text-justify">
                  <h5 class="card-title article"><b><?php echo $art['title']; ?></h5></b>
                  <p class="card-text"><small><b>Posted by <?php echo $art['author']; ?>, 

                    <?php 

                    //date_default_timezone_set('Africa/Lagos');
                     $time_posted = $art['created_on'];
                    $time = date($time_posted); //now
                    $timeago = timeago($time);
                    echo $timeago; 
                    ?>
                    </b></small>

                    <div class="star-rating">
                        <span class="fa divya fa-star" data-rating="1" style="font-size:13px;"></span>
                        <span class="fa fa-star" data-rating="2" style="font-size:13px;"></span>
                        <span class="fa fa-star" data-rating="3" style="font-size:13px;"></span>
                        <span class="fa fa-star" data-rating="4" style="font-size:13px;"></span>
                        <span class="fa fa-star-half" data-rating="5" style="font-size:13px;"></span>
                        <span class="fa" style="font-size:13px;">4.9</span>
                        <input type="hidden" name="whatever3" class="rating-value" value="1">
				    </div>
                  
                   <form action="article-details.php"  method="post">
                    <input type="hidden" name="edit_id" value="<?php echo $art["id"]; ?>">
                      <button type="hidden" name="btn_edit" class="btn btn-sm stretched-link"></button>
                </form>
                </div>
              </div>
          </div>
        </div>
        <?php endforeach; ?> 

        <div class="col-lg-3">
            <div class="card shadow h-auto">
                <div class="card-body">
                    <p class="card-title">Ad will be placed here</p>
                </div>
                
            </div>
        </div>           
    </div>

    <a href="#" class="btn btn-outline-dark btn-sm mt-5 d-block text-center">Show More</a>
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
</body>
</html>