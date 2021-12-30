<?php
//db connection included
require '../dbconn.php';

$errors = array();
$success = array();

if (isset ($_POST['btnupdate'])){
  $alt_name = $_POST['alt_name'];
  $affiliate_link = $_POST['affiliate_link'];
  $id = $_POST['edit_id'];
  $status=1;
  $created_on = date("Y-m-d H:i:s", time());
  $sql = 'UPDATE affiliate SET link=:affiliate_link, alt=:alt_name, Is_Active=:status, created_on=:created_on WHERE id=:id';
  $statement = $connection->prepare($sql);
  if ($statement->execute([':affiliate_link' => $affiliate_link, ':alt_name' => $alt_name, ':status' => $status, ':created_on' => $created_on, ':id' => $id])) {
    $success['data'] = "affiliate details updated successfully <a href='manage-affiliate-links.php'>Go Back</a>";
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
        <h1 class="mt-4">Affiliate</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Edit affiliate Page</li>
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
                   
                  $sql = 'SELECT * FROM affiliate WHERE id=:id';
                  $statement = $connection->prepare($sql);
                  $statement->execute([':id' => $id ]);
                  $affiliate = $statement->fetchAll(PDO::FETCH_OBJ);

                  
                  foreach ($affiliate as $aff) {
                    ?>
            
                   <form action="edit-affiliate-links.php" method="post">
                    <input type="hidden" name="edit_id" value="<?= $aff->id; ?>">
                    <div class="form-group">
                        <label class="col-md-6 control-label">Link name</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?= $aff->alt ?>" name="alt_name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-6 control-label">Affiliate Link</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?= $aff->link ?>" name="affiliate_link" required>
                        </div>
                    </div>

           
                    
                    <a href="manage-affiliate-links.php" class="btn btn-danger">Cancel</a>
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

