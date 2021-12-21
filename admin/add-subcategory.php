<?php
//db connection included
require 'functions/dbconn.php';

$errors = array();
$success = array();

//if submit button is clicked and inputs are not empty
if (isset ($_POST['submit']) && (isset ($_POST['subcategory']))){
  $subcategory = $_POST['subcategory'];
  $category_id = $_POST['category_id'];
  $status=1;
  $created_on = date("Y-m-d H:i:s", time());
  if($category_id === "" || $subcategory === "") {
         $errors['fields'] = "Pls leave no empty fields";
  }else{
        $sql = 'INSERT INTO subcategory(category_id, subcategory, Is_Active, created_on) VALUES(:category_id, :subcategory, :status, :created_on)';
      $statement = $connection->prepare($sql);

      if ($statement->execute([':category_id' => $category_id, ':subcategory' => $subcategory, ':status' => $status, ':created_on' => $created_on])) {
        $success['data'] = 'Subcategory created successfully';
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
        <h1 class="mt-4">Subcategory</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Add Subcategory Page</li>
        </ol>
         <!-- if there is an error, echo all of them -->
         <?php if (count($errors) > 0): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $error): ?> 
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- if there is success, echo all of them -->
        <?php if (count($success) > 0): ?>
            <div class="alert alert-success">
              <?php foreach($success as $succes): ?> 
                <li><?php echo $succes; ?></li>
              <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php
            $sql = 'SELECT * FROM category WHERE Is_Active=1';
            $statement = $connection->prepare($sql);
            $statement->execute();
            $category = $statement->fetchAll(PDO::FETCH_ASSOC);
        ?>

     <div class="card-box">
        <div class="col-md-6">
        <form class="form-horizontal" name="category" method="post">
             <div class="form-group">
                <label class="col-md-2 control-label">Category</label>
                <div class="col-md-10">
                   <select class="form-control" name="category_id" required>
                        <option value="">--Select category--</option>
                        <?php foreach($category as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['CategoryName']; ?></option>
                        <?php endforeach; ?>
                   </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Add Subcategory</label>
                <div class="col-md-10">
                   <input type="text" class="form-control" name="subcategory" required>
                </div>
            </div>
        
                <div class="form-group">
                <div class="col-md-10">
              
            <button type="submit" class="btn btn-primary btn-md" name="submit">
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
    
