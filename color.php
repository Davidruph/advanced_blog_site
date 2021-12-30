<?php
                        $media = $art['image'];
                        $video_format = array(".avi", ".giv", ".mp4", ".mov", ".AVI", ".GIV", ".MP4", ".MOV");
                        if(in_array($media, $video_format)) {
                            ?>
                            <video class="card-img-top embed-responsive-item" autoplay controls> <source src='article_images/<?php echo $art['image']; ?>' type='video/mp4'> </video>"
                            <?php
                       }else {
                           ?>
                            <img class="card-img-top embed-responsive-item" src="article_images/<?php echo $art['image']; ?>" alt="Card image cap">

                            <?php
                       }

                     ?>
					 
					 <div class="table-responsive">
                          <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <!-- <th><small>#</small></th> -->
                                    <th><small>Time</small></th>
                                    <th><small>Name</small></th>
                                    <th><small>Comments</small></th>
                                    <!-- <th><small>action</small></th> -->
                                </tr>
                            </thead>
                              <tbody>
                                <?php 
                                $counter = 0;
                                foreach($comments as $comment): ?>
                                  <tr>
                                   
                                    <td><small>
                                <p class="card-text">
                                <?php 

                                    //date_default_timezone_set('Africa/Lagos');
                                    $time_posted = $comment['PostingDate'];
                                    $time = date($time_posted); //now
                                    $timeago = timeago($time);
                                    echo $timeago; 
                                    ?>

                                </p></small></td>
                                   
                                    <td>
                                         <small>
                                <p class="card-text"><?php echo htmlentities($comment['name']);?></p>
                            </small>
                                    </td>

                                    <td>
                                       <small>
                                <p class="card-text "><?php echo htmlentities($comment['comments']);?></p>
                            </small>
                                    </td>
                                    <td>

                                    <p>
                                    <a class="btn btn-link" id="comment_btn_view" data-toggle="collapse" href="#collapseExample1" role="button" aria-expanded="false" aria-controls="collapseExample">
                                       reply
                                    </a>
                                    </p>

                                    </td>
                                  </tr>

                                  <?php endforeach; ?>
                              </tbody>
                          </table>

                          <!-- reply div -->
                            <div class="collapse mb-5" id="collapseExample1">
                                <div class="card card-body">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" value="">
                                </div>
                                <button id="comment_button" class="btn btn-sm btn-dark">add reply</button>
                                </div>
                            </div>
                            <!-- reply div end -->

                      </div>
                      <button onclick="myyFunction()" id="comment_button" class="btn btn-outline-primary">Start Discussion</button>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		.color1{
			background-color: #0F70BF;
			height: 50px;
		}
		.color2{
			background-color: #494949;
			height: 50px;
		}
		.color3{
			background-color: #FFFFFF;
			height: 50px;
		}
		.color4{
			background-color: #000000;
			height: 50px;
		}
	</style>
</head>
<body>
	<div class="color1"></div>
	<div class="color2"></div>
	<div class="color3"></div>
	<div class="color4"></div>
</body>
</html>