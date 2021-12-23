
<?php
    session_start();

    if(isset($_SESSION['email'])) {
         //session variables for default login
    $id = $_SESSION['user'];
    $user = $_SESSION['username'];
    $lastname = $_SESSION['lastname'];
    $firstname = $_SESSION['firstname'];
    $fullname = $firstname. "  " .$lastname;
    $email = $_SESSION['email'];  
    $role = $_SESSION['role']; 
    $profile_image = $_SESSION['profile_image'];  
    }    

    require 'dbconn.php';
    $errorss = array();
    $successs = array();

    //if suscribe button is clicked
    if (isset($_POST['subscribe'])) {
        $subscriber_email = $_POST['subscriber_email'];
        $postingdate = date("Y-m-d H:i:s", time());
    
        $query = mysqli_query($conn, "SELECT email FROM subscribers WHERE email='$subscriber_email'");
            if(mysqli_num_rows($query) > 0){
               $errorss['pass'] = "Hi, you've already subscribed";
            }else{
              $sql = 'INSERT INTO subscribers(email, PostingDate) VALUES(:email, :postingdate)';
              $statement = $connection->prepare($sql);
    
              if ($statement->execute([':email' => $subscriber_email, ':postingdate' => $postingdate])) {
                $successs['data'] = 'Subscribed successfully';
              }else{
                $errorss['data'] = 'Ooops, an error occured';
              }
            }
        
    }



//code for comments 

if (isset($_POST['leave_comment'])) {
    $post_id = $_POST['post_id'];
    // $name = $_POST['name'];
    // $email = $_POST['email'];
    $comment = $_POST['comment'];
    $postingdate = date("Y-m-d H:i:s", time());

    //check if user is logged in
    if(!isset($_SESSION['email'])) {
        header("Location: login.php");
    }
    else{

    //initialize the session

    $id = $_SESSION['user'];
    $fullname = $_SESSION['fullname'];
    $email = $_SESSION['email'];

    $query = mysqli_query($conn, "SELECT post_id, email FROM tblcomments WHERE email='$email' AND post_id = $post_id");
        if(mysqli_num_rows($query) > 0){
           $errorss['pass'] = "You have already made a comment for this post";
        }else{

            $qry = 'INSERT INTO tblcomments(post_id, name, email, comments, PostingDate) VALUES(:post_id, :name, :email, :comments, :postingdate)';
            $statement = $connection->prepare($qry);
              if ($statement->execute([':post_id' => $post_id, ':name' => $fullname, ':email' => $email, ':comments' => $comment, ':postingdate' => $postingdate])) {
                $successs['data'] = 'Commented successfully';
                header("Location: index.php");

              }else{
                $errorss['data'] = 'Ooops, an error occured';
              }
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
            <a href="<?php echo $aff["link"]; ?>" target = "_blank" class="btn btn-dark btn-sm text-white mr-2 mt-2 mb-2" alt="<?php echo $aff["alt"]; ?>"><?php echo $aff["link"]; ?></a>
        <?php endforeach; ?>
    </div>
</div>

<section class="w-100 shadow-sm bg-dark">
  

<div class="container-fluid">

    <nav class="navbar navbar-expand-xl navbar-light text-white bg-transparent">
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
                <a class="nav-link mr-1 text-white font-weight-bold" href="#">Trends</a>
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
    <div class="mt-5 mb-5">
        <ol class="breadcrumb mb-4 d-block" style="margin-top: 50px;">
            <li class="breadcrumb-item active">Article Details</li>
        </ol>
    </div>
    <div class="row">
    <div class="col-md-8">

<?php
    if (isset($_GET['article_id'])) {
      $article_id = $_GET['article_id'];
       
      $sql = 'SELECT * FROM article WHERE id=:id';
      $statement = $connection->prepare($sql);
      $statement->execute([':id' => $article_id ]);
      $article = $statement->fetchAll(PDO::FETCH_OBJ);

      
      foreach ($article as $art) {
        ?>

           <div class="card mb-4">

                <div class="card-body">
                  <h2 class="card-title"><b><?php echo $art->title;?></b></h2>
                  <p><b>Category : </b><?php echo htmlentities($art->category);?><br>
                  <small><p><b><a href="#"><?php echo date("F j, Y", strtotime($art->created_on)); ?></a>. Posted by <?php echo htmlentities($art->author);?></b> &nbsp; <a href="#">Comments (<?php $article_post_id = $art->id;$count=$connection->prepare("SELECT post_id FROM tblcomments WHERE post_id = $article_post_id");$count->execute();$comments=$count->rowCount();echo $comments; ?>)</a> &nbsp; <a href="#">Notify Me</a></p></small>
                    
                    <hr />
                    <?php
                        $media = $art->image;
                        $video_format = array(".avi", ".giv", ".mp4", ".mov", ".AVI", ".GIV", ".MP4", ".MOV");
                        if(in_array($media, $video_format)) {
                            ?>
                            <video class="card-img-top embed-responsive-item" autoplay controls> <source src='article_images/<?php echo $art->image ?>' type='video/mp4'> </video>"
                            <?php
                       }else {
                           ?>
                           <img class="img-fluid rounded w-100" style="height: 300px;" src="article_images/<?php echo htmlentities($art->image);?>" alt="<?php echo htmlentities($art->title);?>">

                            <?php
                       }

                     ?>
                    

                                <p class="card-text"><?php 
                                        echo $art->description;
                                ?></p>
                 
                </div>
                 <div class="card-footer">
                  <a href="index.php">Back to Homepage</a>
              </div>
              </div>
             

        <?php
}
}
?>
</div>
<div class="col-md-4">

<?php
    if (!empty($article)) {
        foreach ($article as $art) {

             $comment_id = $art->id;
              //var_dump($comment_id);

              $qry = "SELECT * FROM tblcomments WHERE post_id = $comment_id";
                $statement = $connection->prepare($qry);
                $statement->execute();
                $comments = $statement->fetchAll(PDO::FETCH_ASSOC);
                //var_dump($comments);
        }

        ?>
             <p>
                  <a class="btn btn-outline-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    View comments
                  </a>
                </p>
                <div class="collapse mb-5" id="collapseExample">
                  <div class="card card-body">

                    <div class="table-responsive">
                          <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><small>#</small></th>
                                    <th><small>Date</small></th>
                                    <th><small>Name</small></th>
                                    <th><small>Comments</small></th>
                                </tr>
                            </thead>
                              <tbody>
                                <?php 
                                $counter = 0;
                                foreach($comments as $comment): ?>
                                  <tr>
                                    <td><small>
                                <p class="card-text"><?php echo ++$counter; ?></p></p>
                            </small></td>
                                   

                                    <td><small>
                                <p class="card-text"><?php echo htmlentities($comment['PostingDate']);?></p></p>
                            </small></td>
                                   
                                    <td>
                                         <small>
                                <p class="card-text"><?php echo htmlentities($comment['name']);?></p></p>
                            </small>
                                    </td>

                                    <td>
                                       <small>
                                <p class="card-text "><?php echo htmlentities($comment['comments']);?></p></p>
                            </small>
                                    </td>
                                  </tr>

                                  <?php endforeach; ?>
                              </tbody>
                          </table>
                      </div>

                    
                    <button onclick="myFunction()" id="comment_button" class="btn btn-outline-primary">add comment</button>
                  </div>
                </div>

        <?php
    }
?>

<?php
    if(isset($_SESSION['email']) && !empty($article)) {
?>
<div class="card mb-4">
<h5 class="card-header">Leave a comment <?= $fullname ?? '' ?></h5>
<div class="card-body">
       <form name="search" action="article-details.php" method="post">
        <input type="hidden" name="post_id" value="<?= $id ?? '' ?>">

        
    <div class="form-group">
        <input type="hidden" name="name" class="form-control" value="<?= $fullname ?? '' ?>">
    </div>

    <div class="form-group">
        <input type="hidden" name="email" class="form-control" value="<?= $email ?? '' ?>">
    </div>

    <div class="form-group">
        <textarea name="comment" id="comment" class="form-control" placeholder="Your comment"></textarea>
    </div>
    <button class="btn btn-secondary" name="leave_comment" type="submit">Go!</button>

    </form>
</div>

</div>
                <?php
            }else{
                ?>
                <?php

                    if (!empty($article)) {
                        ?>

                            <div class="card mb-4">
                            <h5 class="card-header">Leave a comment</h5>
                            <div class="card-body">
                            <form name="search" action="article-details.php" method="post">
                                    <input type="hidden" name="post_id" value="<?= $id ?? '' ?>">

                            <div class="form-group">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Your name" required>
                            </div>

                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Your email" required>
                            </div>

                            <div class="form-group" id="comment">
                                <textarea name="comment" class="form-control" placeholder="Your comment" required></textarea>
                            </div>
                            <button class="btn btn-secondary" name="leave_comment" type="submit">Go!</button>

                            </form>
                            </div>

                          </div>

                        <?php
                    }
                ?>
                    

                <?php
            }

        ?>
      
          
       
  
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
                <li class="mb-2 text-white text-justify"><a href="" class="text-white">art</a></li>
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

                <form action="share.php" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="subscriber_email" value="<?= $email ?? '' ?>" required placeholder="Your email" aria-label="recipient's email" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                        <input type="submit" name="subscribe" class="btn btn-danger" value="subscribe">
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

<script>
    function myFunction() {
     document.getElementById("name").focus();
}
</script>
</body>
</html>