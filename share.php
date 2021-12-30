
<?php
    session_start();

    if(isset($_SESSION['email'])) {
         //session variables for default login
    $id = $_SESSION['user'];
    $user = $_SESSION['username'];
    $lastname = $_SESSION['lastname'];
    $firstname = $_SESSION['firstname'];
    $fullname = $firstname." ".$lastname;
    $email = $_SESSION['email'];  
    $role = $_SESSION['role']; 
    $profile_image = $_SESSION['profile_image'];  
    }    

    require 'dbconn.php';
    $errorss = array();
    $successs = array();

    $errors = array();
    $success = array();

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



//code for comments 

if (isset($_POST['leave_comment'])) {
    $post_id = $_POST['post_id'];
    // $name = $_POST['name'];
    // $email = $_POST['email'];
    $comments = $_POST['comment'];
    $postingdate = date("Y-m-d H:i:s", time());

    //check if user is logged in
    if(!isset($_SESSION['email'])) {
        header("Location: signup.php");
    }
    else{

    //initialize the session

    $id = $_SESSION['user'];
    $name = $fullname;
    $email = $_SESSION['email'];

    $query = mysqli_query($conn, "SELECT post_id, email FROM tblcomments WHERE email='$email' AND post_id = $post_id");
        if(mysqli_num_rows($query) > 0){
           $errorss['pass'] = "You have already made a comment for this post";
        }else{
            $sql = 'INSERT INTO tblcomments(post_id, name, email, comments, PostingDate) VALUES(:post_id, :name, :email, :comments, :postingdate)';
            $statement = $connection->prepare($sql);
  
            if ($statement->execute([':post_id' => $post_id, ':name' => $name, ':email' => $email, ':comments' => $comments, ':postingdate' => $postingdate])) {
              $successs['data'] = 'commented successfully';
              header("Location: index.php");
            }else{
              $errorss['data'] = 'Ooops, an error occured';
            }
          }

    }
}

if (isset($_POST['reply_comment'])) {
    $comment_id = $_POST['comment_id'];
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $commentbox = $_POST['commentbox'];
    $replied_on = date("Y-m-d H:i:s", time());

    //check if user is logged in
    if(!isset($_SESSION['email'])) {
        header("Location: signup.php");
    }
    else{

    $query = mysqli_query($conn, "SELECT post_id, email FROM replies WHERE email='$email' AND post_id = $post_id AND comment_id = $comment_id");
        if(mysqli_num_rows($query) > 0){
           $errors['pass'] = "You have already made a reply for this comment <a class='ml-3' href='index.php'>view post</a>";
        }else{
            $reply = 'INSERT INTO replies(comment_id, post_id, user_id, name, email, reply_comment, replied_on) VALUES(:comment_id, :post_id, :user_id, :name, :email, :commentbox, :replied_on)';
            $statement = $connection->prepare($reply);
  
            if ($statement->execute([':comment_id' => $comment_id, ':post_id' => $post_id, ':user_id' => $user_id, ':name' => $name, ':email' => $email, ':commentbox' => $commentbox, ':replied_on' => $replied_on])) {
              $success['data'] = "you've replied to the comment successfully <a class='ml-3' href='index.php'>view other post</a>";
              //header("Location: article-details.php");
            }else{
              $errors['data'] = 'Ooops, an error occured';
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
    <div class="mt-5 mb-5">
        <ol class="breadcrumb mb-4 d-block" style="margin-top: 50px;">
            <li class="breadcrumb-item active">Article Details</li>
        </ol>
    </div>
    <div class="row">
    <div class="container w-75">
        <?php if (count($errors) > 0): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php foreach($errors as $erro): ?> 
        <li class="text-danger"><?php echo $erro; ?></li>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
            
        <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (count($success) > 0): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php foreach($success as $succe): ?> 
        <li class="text-success"><?php echo $succe; ?></li>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
            
        <?php endforeach; ?>
        </div>
        <?php endif; ?>
        </div>
    <div class="col-md-7">

<?php
    if (isset($_GET['article_id'])) {
      $article_id = $_GET['article_id'];
       
      $sql = 'SELECT * FROM article WHERE id=:id';
      $statement = $connection->prepare($sql);
      $statement->execute([':id' => $article_id ]);
      $article = $statement->fetchAll(PDO::FETCH_OBJ);

      
      foreach ($article as $art) {
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

           <div class="card mb-4">

                <div class="card-body">
                  <h2 class="card-title"><b><?php echo $art->title;?></b></h2>
                  <p><b>Category : </b><?php echo htmlentities($art->category);?><br>
                  <small><p><b><a href="#"><?php echo date("F j, Y", strtotime($art->created_on)); ?></a>. Posted by <?php echo htmlentities($art->author);?></b> &nbsp; <button class="btn btn-sm btn-link" onclick="comment_view()">Comments (<?php $article_post_id = $art->id;$count=$connection->prepare("SELECT post_id FROM tblcomments WHERE post_id = $article_post_id");$count->execute();$comments=$count->rowCount();echo $comments; ?>)</button> &nbsp; <a href="#">Notify Me</a></p></small>
                    
                    <hr />
                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                        <img class="d-block w-100" src="<?php echo $images[0];?>" alt="Card image cap" style=" height: 50vh;">
                        </div>
                        <?php
                            if (sizeof($images) > 1) {
                                ?>
                                    <div class="carousel-item">
                                    <img class="d-block w-100" src="<?php echo $images[1];?>" alt="Card image cap" style=" height: 50vh;">
                                    </div>
                                <?php
                            }
                        ?>

                        <?php
                            if (sizeof($images) > 2) {
                                ?>
                                    <div class="carousel-item">
                                    <img class="d-block w-100" src="<?php echo $images[2];?>" alt="Card image cap" style=" height: 50vh;">
                                    </div>
                                <?php
                            }
                        ?>
                        
                        
                    </div>
                    </div>
                    

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
<div class="col-md-5">

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
                  <a class="btn btn-outline-primary" id="comment_btn_view" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    View comments
                  </a>
                </p>
                <div class="collapse mb-5" id="collapseExample">
                  <div class="card card-body">
                    
                    <?php foreach($comments as $comment): ?>
                        <div class="comment mb-2">
                        <div class="user"><i class="fa fa-user mr-3"></i><small class="font-weight-bold text-dark"><?php echo htmlentities($comment['name']);?></small> <span class="time ml-3 text-gray"><small><?php 

                            //date_default_timezone_set('Africa/Lagos');
                            $time_posted = $comment['PostingDate'];
                            $time = date($time_posted); //now
                            $timeago = timeago($time);
                            echo $timeago; ?></small></span></div>
                        <div class="userComment ml-5 mt-2"><small class="text-dark"><?php echo htmlentities($comment['comments']);?></small></div>
                        <?php
                            $id_comment = $comment['id'];
                            // $query=mysqli_query($conn,"SELECT tblcomments.post_id as postid,replies.post_id as repliesPostId,replies.comment_id as rcomment_id from replies join tblcomments on replies.comment_id=tblcomments.id where replies.post_id=$id");

                             $qry = "SELECT tblcomments.post_id as postid,replies.name as rname,replies.replied_on as rdate,replies.reply_comment as rcomment,replies.post_id as repliesPostId,replies.comment_id as rcomment_id from replies join tblcomments on replies.comment_id=tblcomments.id where replies.post_id=$id AND tblcomments.id=$id_comment";
                             $statement = $connection->prepare($qry);
                             $statement->execute();
                             $replies = $statement->fetchAll(PDO::FETCH_ASSOC);
                             //var_dump($replies);
                        ?>
                        <?php foreach($replies as $reply): ?>
                            <div class="reply mb-2 ml-5 mt-3">
                            <div class="user"><i class="fa fa-user mr-3"></i><small class="font-weight-bold text-dark"><?php echo htmlentities($reply['rname']);?></small><span class="ml-2"><small>replied</small></span> <span class="time ml-3 text-gray"><small><?php 

                                //date_default_timezone_set('Africa/Lagos');
                                $time_posted = $reply['rdate'];
                                $time = date($time_posted); //now
                                $timeago = timeago($time);
                                echo $timeago; ?></small></span></div>
                                <div class="userComment ml-5 mt-2"><small class="text-dark"><?php echo htmlentities($reply['rcomment']);?></small></div>
                            </div>

                        <?php endforeach; ?>

                        <!-- reply comment begin -->
                            <a class="btn btn-outline-primary btn-sm ml-5 mt-2 mb-2" data-toggle="collapse" href="#collapse<?php echo htmlentities($comment['id']);?>" role="button" aria-expanded="false" aria-controls="collapseExampl">
                            reply
                            </a>

                            <div class="collapse mb-5" id="collapse<?php echo htmlentities($comment['id']);?>">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="article-details.php" method="post">
                                            <input type="hidden" name="comment_id" class="form-control form-control-sm" value="<?php echo htmlentities($comment['id']);?>">
                                            <input type="hidden" name="post_id" class="form-control form-control-sm" value="<?php echo htmlentities($id);?>">
                                            <input type="hidden" name="user_id" class="form-control form-control-sm" value="<?php echo htmlentities($_SESSION['user'] ?? ''); ?>">
                                        
                                        <div class="form-group">
                                            <input type="hidden" name="name" class="form-control" value="<?= $fullname ?? '' ?>">
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" name="email" class="form-control" value="<?= $email ?? '' ?>">
                                        </div>

                                        <div class="form-group">
                                            <textarea name="commentbox" class="form-control" placeholder="Your comment"></textarea>
                                        </div>
                                        <button class="btn btn-secondary" name="reply_comment" type="submit">Go!</button>

                                        </form>
                                        
                                    </div>
                                </div>
                            </div>

                            <!-- reply comment begin -->
                        
                        </div>
                    <?php endforeach; ?>
                    
                    <button onclick="commentFunction()" id="comment_button" class="btn btn-outline-primary btn-sm mt-5">Start Discussion</button>
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

<?php
             if (!empty($article)) {
                foreach ($article as $art) {
                    $id = $art->id;
                    $title = $art->title;
                    $category = $art->category;
                    $author = $art->author;
                    $description = $art->description;

                   

                    $sql_related = 'SELECT * FROM article WHERE title LIKE :title OR author LIKE :author OR description LIKE :description OR category LIKE :category LIMIT 3';
                    $statement = $connection->prepare($sql_related);
                    $statement->execute(array(':title' => '%'.$title.'%', ':author' => '%'.$author.'%', ':description' => '%'.$description.'%', ':category' => '%'.$category.'%'));
                    $related_article = $statement->fetchAll(PDO::FETCH_OBJ);
                    ?>
                    <div class="mt-5 mb-4 justify-content-center">
                        
                        <p class="text-center">Related Articles</p>
                    <?php
                    
                    foreach ($related_article as $rel){
                        if ($rel->id != $id) {
                            $media = $rel->image;
                            $temp = array();
                            $media=trim($media, '/,');
                            $temp   = explode(',', $media);
                            $temp   = array_filter($temp);
                            $images = array();
                            foreach($temp as $image){
                            $images[]="article_images/".trim( str_replace(array('[',']') ,"" ,$image ) );
                            }

                            //var_dump($rel->id);
                            ?>  
                            
            <div class="justify-content-center">
            <div class="card mb-2 text-center" >
                 <!-- <div class="embed-responsive embed-responsive-4by3"> -->
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
                     
                 <!-- </div> -->
                <div class="card-body text-left">
                  <h5 class="card-title article"><b><?php echo $rel->title; ?></h5></b>
                  <p class="card-text"><small><b><?php echo $rel->catchy_phrase; ?>
                  <p class="card-text"><small><b>Posted by <?php echo $rel->author; ?>, 

                    <?php 

                    //date_default_timezone_set('Africa/Lagos');
                     $time_posted = $rel->created_on;
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
                    <input type="hidden" name="edit_id" value="<?php echo $rel->id; ?>">
                      <button type="hidden" name="btn_edit" class="btn btn-sm stretched-link"></button>
                </form>
                </div>
              </div>
          
            </div>
              
                                
                            <?php
                        }
                    }

                   
                }
                
               ?>  
               <?php
            }
           
        ?>
       
  
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
            <a href="<?php echo $aff["link"]; ?>" target = "_blank" class="btn bg-dark btn-sm text-white mr-2 mt-2 mb-2" alt="<?php echo $aff["alt"]; ?>"><?php echo $aff["link"]; ?></a>
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
    function commentFunction() {
    //  document.getElementById("name").focus();
     document.getElementById("comment").focus();
}
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

<script>
function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
//   alert("Copied the text: " + copyText.value);
  alert("Copied successfully!");
}
</script>
<script>
     function comment_view() {
    //  document.getElementById("name").focus();
     document.getElementById("comment_btn_view").click();
}
</script>

</script>
</body>
</html>