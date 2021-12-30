<?php
error_reporting(0);
    //All header tag to be included
    include('include/header.php');
    
//db connection included
require '../dbconn.php';
if(isset($_SESSION['email'])) {
    $id = $_SESSION['user'];
}

$errors = array();
$success = array();

//if submit button is clicked and inputs are not empty
if (isset ($_POST['submit'])){
    
  $article_title = $_POST['article_title'];
  $author = $_POST['author'];
  $article_description = $_POST['description'];
  $category = $_POST['category'];
  $catchy_phrase = $_POST['catchy_phrase'];
  $created_on = date("Y-m-d H:i:s", time());
  $status=1;
  $id = $_SESSION['user'];


  if($article_title === "" || $author === "" || $article_description === "" || $category === "" || $_FILES['postimage']['tmp_name'] === "") {
        $errors['description'] = "All fields are required";
  }else{

  $targetDir = "../article_images/"; 
  $allowTypes = array('jpg','png','jpeg','gif','JPG', 'JPEG', 'GIF', 'PNG', 'webp'); 
  $image = $_FILES['postimage']['name'];

//   $filename = implode(",",$image);
  if(!empty($image)){ 
    foreach($_FILES["postimage"]['name'] as $key=>$tmp_name){
        $file_name=$_FILES["postimage"]["name"][$key];
        $file_tmp=$_FILES["postimage"]["tmp_name"][$key];
        $ext=pathinfo($file_name,PATHINFO_EXTENSION);
        $filename=basename($file_name,$ext);
        $newFileName=(string)$filename.time().".".$ext;
        if(in_array($ext, $allowTypes)){ 
              // Upload file to server 
            move_uploaded_file($file_tmp=$_FILES["postimage"]["tmp_name"][$key],"../article_images/".$newFileName);
            $concateFiles .= $comma.$newFileName;
            $comma = ','; 
          }else{ 
            $errors['image'] = "file is not supported";
          } 
    }
    
      $sql = 'INSERT INTO article(user_id, title, author, catchy_phrase, description, image, category, created_on, Is_Active) VALUES(:user_id, :title, :author, :catchy_phrase, :description, :imgnewfile, :category, :created_on, :status)';
      $statement = $connection->prepare($sql);
    
      if ($statement->execute([':user_id' => $id, ':title' => $article_title, ':author' => $author, ':catchy_phrase' => $catchy_phrase, ':description' => $article_description, ':category' => $category, ':imgnewfile' => $concateFiles, ':created_on' => $created_on, ':status' => $status])) {
        $success['data'] = 'Article created successfully';
      }else{
        $errors['data'] = 'Ooops, an error occured';
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
        <h1 class="mt-4">Article</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Add Article Post</li>
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
        <form class="form-horizontal" name="recipe-form" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="col-md-5 control-label">Feature Image</label>
                <div class="col-md-10">
                    <input type="file" class="form-control-file" id="postimage" name="postimage[]" multiple required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-5 control-label">Article title</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" value="" name="article_title" required>
                </div>
            </div>

             <div class="form-group">
                <label class="col-md-6 control-label">Enter Author name</label>
                <div class="col-md-10">
                    <input type="text" name="author" id="author" class="form-control" required="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-6 control-label">Enter Catchy Phrase</label>
                <div class="col-md-10">
                    <input type="text" name="catchy_phrase" id="catchy_phrase" class="form-control" required="">
                </div>
            </div>

              <div class="form-group">
                <label class="col-md-6 control-label">Select or Enter Category name</label>
                <div class="col-md-10">
                    <input list="browser" name="category" id="category" class="form-control" required="">
                </div>
            </div>
              <?php
                  $sql = 'SELECT * FROM category';
                  $statement = $connection->prepare($sql);
                  $statement->execute();
                  $category = $statement->fetchAll(PDO::FETCH_OBJ);
              ?>

              <datalist id="browser">

                <?php foreach($category as $cat): ?>
                 <option value="<?= $cat->CategoryName; ?>"></option>
                 <?php endforeach; ?>
              </datalist>
         
                <div class="form-group">
                    <label class="col-md-5 control-label">Article Details</label>
                    <div class="col-md-10">
                        <textarea class="form-control" rows="5" name="description" id="description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                <label class="col-md-2 control-label">&nbsp;</label>
                <div class="col-md-10">
              
            <button type="submit" class="btn btn-primary btn-block" name="submit">
                Submit
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
$("#description").summernote({
  placeholder: 'Enter Blog details here...',
        height: 100,
         callbacks: {
        onImageUpload : function(files, editor, welEditable) {
 
             for(var i = files.length - 1; i >= 0; i--) {
                     sendFile(files[i], this);
            }
        }
    }
    });

function sendFile(file, el) {
var form_data = new FormData();
form_data.append('postimage', file);
$.ajax({
    data: form_data,
    type: "POST",
    url: 'include/editor-upload.php',
    cache: false,
    contentType: false,
    processData: false,
    success: function(url) {
        $(el).summernote('editor.insertImage', url);
    }
});
}
</script>
    
