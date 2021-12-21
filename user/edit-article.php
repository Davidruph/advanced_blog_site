<?php
//db connection included
require 'functions/dbconn.php';

$errors = array();
$success = array();

if (isset ($_POST['btnupdate'])){
   $article_title = $_POST['article_title'];
  $author = $_POST['author'];
  $article_description = $_POST['description'];
  $category = $_POST['category'];
  $created_on = date("Y-m-d H:i:s", time());
  $status=1;
  $id = $_POST['edit_id'];

  if($article_title === "" || $author === "" || $article_description === "" || $category === "") {
        $errors['description'] = "All fields are required";
  }else{

  $sql = 'UPDATE article SET title=:title, author=:author, description=:description, category=:category, created_on=:created_on WHERE id=:id';
  $statement = $connection->prepare($sql);
  if ($statement->execute([':title' => $article_title, ':author' => $author, ':description' => $article_description, ':category' => $category, ':created_on' => $created_on, ':id' => $id])) {
    $success['data'] = "Article has been updated successfully <a href='manage-article.php'>Go Back</a>";
  }else{
    $errors['data'] = 'Ooops, an error occured';
  }
}
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
        <h1 class="mt-4">Article</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Edit article Page</li>
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

      <div class="col-md-12">
           <div class="card-body">
                <?php
                if(isset($_POST['btn_edit'])) {
                  $id = $_POST['edit_id'];
                   
                  $sql = 'SELECT * FROM article WHERE id=:id';
                  $statement = $connection->prepare($sql);
                  $statement->execute([':id' => $id ]);
                  $article = $statement->fetchAll(PDO::FETCH_OBJ);

                  
                  foreach ($article as $art) {
                    ?>
            
                   <form action="edit-article.php" method="post">
                    <input type="hidden" name="edit_id" value="<?= $art->id; ?>">

                  <div class="form-group">
                      <label class="col-md-2 control-label">Article title</label>
                      <div class="col-md-10">
                          <input type="text" class="form-control" value="<?= $art->title; ?>" name="article_title" required>
                      </div>
                  </div>

                   <div class="form-group">
                      <label class="col-md-2 control-label">Category</label>
                      <div class="col-md-10">
                          <input type="text" class="form-control" value="<?= $art->category; ?>" name="category" required>
                      </div>
                  </div>

                   <div class="form-group">
                      <label class="col-md-2 control-label">Author Name</label>
                      <div class="col-md-10">
                          <input type="text" class="form-control" value="<?= $art->author; ?>" name="author" required>
                      </div>
                  </div>
                   
                <div class="form-group">
                    <label class="col-md-4 control-label">Description</label>
                    <div class="col-md-10">
                        <textarea class="form-control" rows="5" name="description" id="description" required><?= $art->description; ?></textarea>
                    </div>
                </div>
                   
                    
                    <a href="manage-article.php" class="btn btn-danger">Cancel</a>
                    <button type="submit" name="btnupdate" class="btn btn-primary">Update</button>
                    
                   </form>
                   <?php
			    }
				}
				?>

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
  placeholder: 'Enter blog Descriptions here...',
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
form_data.append('file', file);
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