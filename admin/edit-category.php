<?php
//db connection included
require 'functions/dbconn.php';

$errors = array();
$success = array();

if (isset ($_POST['btnupdate'])){
  $categ = $_POST['category'];
  $id = $_POST['edit_id'];
  $status=1;
  $created_on = date("Y-m-d H:i:s", time());
  $sql = 'UPDATE category SET CategoryName=:category, Is_Active=:status, created_on=:created_on WHERE id=:id';
  $statement = $connection->prepare($sql);
  if ($statement->execute([':category' => $categ, ':status' => $status, ':created_on' => $created_on, ':id' => $id])) {
    $success['data'] = "category updated successfully <a href='manage-categories.php'>Go Back</a>";
  }else{
    $errors['data'] = 'Ooops, an error occured';
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
        <h1 class="mt-4">Category</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Edit Category Page</li>
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
                   
                  $sql = 'SELECT * FROM category WHERE id=:id';
                  $statement = $connection->prepare($sql);
                  $statement->execute([':id' => $id ]);
                  $category = $statement->fetchAll(PDO::FETCH_OBJ);

                  
                  foreach ($category as $cat) {
                    ?>
            
                   <form action="edit-category.php" method="post">
                    <input type="hidden" name="edit_id" value="<?= $cat->id; ?>">
                   <div class="form-group">
                      <label class="col-md-2 control-label">Category</label>
                      <div class="col-md-10">
                          <input type="text" class="form-control" value="<?= $cat->CategoryName; ?>" name="category" required>
                      </div>
                  </div>
                    
                    <a href="manage-categories.php" class="btn btn-danger">Cancel</a>
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

