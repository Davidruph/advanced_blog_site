
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
            $sql = 'INSERT INTO subscribers(name, email, website, PostingDate) VALUES(:name, :email, :website, :postingdate)';
            $statement = $connection->prepare($sql);
  
            if ($statement->execute([':name' => $name, ':email' => $subscriber_email, ':website' => $website, ':postingdate' => $postingdate])) {
              $successs['data'] = 'Subscribed successfully';
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
  <link rel="stylesheet" type="text/css" href="ratings.css">
  <script type="text/javascript">
function addBookmark(url, title){
if (!url) {url = window.location}
	if (!title) {title = document.title}
	var browser=navigator.userAgent.toLowerCase();
	if (window.sidebar) { // Mozilla, Firefox, Netscape
		window.sidebar.addPanel(title, url,"");
	} else if( window.external) { // IE or chrome
		if (browser.indexOf('chrome')==-1){ // ie
			window.external.AddFavorite( url, title);
		} else { // chrome
			alert('Please Press CTRL+D (or Command+D for macs) to bookmark this page');
		}
	}
	else if(window.opera && window.print) { // Opera - automatically adds to sidebar if rel=sidebar in the tag
		return true;
	}
	else if (browser.indexOf('konqueror')!=-1) { // Konqueror
		alert('Please press CTRL+B to bookmark this page.');
	}
	else if (browser.indexOf('webkit')!=-1){ // safari
		alert('Please press CTRL+B (or Command+D for macs) to bookmark this page.');
	} else {
		alert('Your browser cannot add bookmarks using this link. Please add this link manually.')
	}
}
</script>
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
    <div class="col-md-8">

<?php
    if(isset($_POST['btn_edit'])) {
      $id = $_POST['edit_id'];
       
      $sql = 'SELECT * FROM article WHERE id=:id';
      $statement = $connection->prepare($sql);
      $statement->execute([':id' => $id ]);
      $article = $statement->fetchAll(PDO::FETCH_OBJ);
     
      
      foreach ($article as $art) {
        ?>

           <div class="card mb-4">

                <div class="card-body">
                  <h2 class="card-title"><b><?php echo $art->title;?></b></h2>
                  <p><b>Category : </b><?php echo htmlentities($art->category);?><br>
                  <small><p><b><a href="#"><?php echo date("F j, Y", strtotime($art->created_on)); ?></a>. Posted by <?php echo htmlentities($art->author);?></b> &nbsp; <a href="#">Comments (<?php $article_post_id = $art->id;$count=$connection->prepare("SELECT post_id FROM tblcomments WHERE post_id = $article_post_id");$count->execute();$comments=$count->rowCount();echo $comments; ?>)</a> &nbsp; <a href="#">Notify Me</a></p></small>
                 

                  <fieldset class="border p-2">
                    <legend  class="w-auto"></legend>

                    <div class="row ml-5">
                        <?php
                            $article_id = $art->id;
                            $article_title = $art->title;
                            $site_url = "http://localhost/fiverr/oneupmeta/share.php?article_id=$article_id";
                        ?>
                    <a href="https://www.facebook.com/sharer.php?u=<?=$site_url?>" target="_blank" title="share to facebook" class="btn btn-primary mr-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/share?url=<?=$site_url?>&amp;text=Simple%20Share%20Buttons&amp;hashtags=simplesharebuttons" target="_blank" title="share to twitter" class="btn btn-info mr-2"><i class="fab fa-twitter"></i></a>

                   <input type="hidden" value="<?=$site_url?>" id="myInput">
                    <button title="copy link" class="btn btn-warning mr-2" title="Copy to clipboard" onclick="myFunction()"><i class="fa fa-link" ></i></button>

                    <a href="mailto:?Subject=<?=$site_url?>&amp;Body=I%20saw%20this%20and%20thought%20of%20you!%20 <?=$site_url?>" target="_blank" title="share to email" class="btn btn-dark mr-2"><i class="fa fa-envelope"></i></a>

                    <a href="https://telegram.me/share/url?url=<?=$site_url?>" target="_blank" title="share to telegram" class="btn btn-info mr-2"><i class="fab fa-telegram"></i></a>

                    <a href="#"  onclick="addBookmark('<?=$site_url?>', '<?=$article_title?>');" title="add to bookmarks" class="btn btn-default icon mr-2"><i class="fa fa-bookmark"></i></a>
                    </div>
                    </fieldset>
                    
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
                 <div class="card-footer justify-content-between">
                  <a href="index.php">Back to Homepage</a>
                  
                  <div class="star-rating float-right">
                  <p><small>rate this article below:</small></p>
                  <fieldset class="rating">
                        <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
                        <input type="radio" id="star4half" name="rating" value="4 and a half" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
                        <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                        <input type="radio" id="star3half" name="rating" value="3 and a half" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
                        <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
                        <input type="radio" id="star2half" name="rating" value="2 and a half" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
                        <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="star1half" name="rating" value="1 and a half" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
                        <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
                        <input type="radio" id="starhalf" name="rating" value="half" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
                    </fieldset>
				    </div>
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
                                    <th><small>Time</small></th>
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
                                <p class="card-text">
                                <?php 

                                    //date_default_timezone_set('Africa/Lagos');
                                    $time_posted = $comment['PostingDate'];
                                    $time = date($time_posted); //now
                                    $timeago = timeago($time);
                                    echo $timeago; 
                                    ?>

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

                    
                    <button onclick="myyFunction()" id="comment_button" class="btn btn-outline-primary">Start Discussion</button>
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
                                <textarea name="comment" id="comment" class="form-control" placeholder="Your comment" required></textarea>
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

                            //var_dump($rel->id);
                            ?>  
                            
            <div class="justify-content-center">
            <div class="card mb-2 text-center" >
                 <div class="embed-responsive embed-responsive-4by3">
                     <?php
                        $media = $rel->image;
                        $video_format = array(".avi", ".giv", ".mp4", ".mov", ".AVI", ".GIV", ".MP4", ".MOV");
                        if(in_array($media, $video_format)) {
                            ?>
                            <video class="card-img-top embed-responsive-item" autoplay controls> <source src='article_images/<?php echo $rel->image; ?>' type='video/mp4'> </video>"
                            <?php
                       }else {
                           ?>
                            <img class="card-img-top embed-responsive-item" src="article_images/<?php echo $rel->image; ?>" alt="Card image cap">

                            <?php
                       }

                     ?>
                     
                 </div>
                <div class="card-body text-left">
                  <h5 class="card-title article"><b><?php echo $rel->title; ?></h5></b>
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
                <a class="navbar-brand text-center" href="index.php">
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

                <form action="article-details.php" method="post">
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
    function myyFunction() {
    //  document.getElementById("name").focus();
     document.getElementById("comment").focus();
}
</script>

<script>
   $(document).click(function(){
        if(typeof timeOutObj != "undefined") {
            clearTimeout(timeOutObj);
        }

        timeOutObj = setTimeout(function(){ 
            localStorage.clear();
            window.location = "/logout.php";
        }, 1800000);   //will expire after thirty minutes

   });
</script>
<script>
function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  //copyText.select();
  //copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
//   alert("Copied the text: " + copyText.value);
  alert("Copied successfully!");
}
</script>

</body>
</html>