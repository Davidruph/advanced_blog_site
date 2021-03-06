
<?php

//db connection
require '../dbconn.php';
$errors = array();
$success = array();
//fetch affiliate
$sql = 'SELECT * FROM affiliate WHERE Is_Active=1';
$statement = $connection->prepare($sql);
$statement->execute();
$affiliate = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
//delete affiliate

if (isset($_POST['delete_btn'])) {
  $id = $_POST['delete_id'];
  $sql = 'UPDATE affiliate SET Is_Active = 0 WHERE id=:id';
  $statement = $connection->prepare($sql);
  if ($statement->execute([':id' => $id])) {
   
    $success['confirm'] = "One record has been deleted!";
    header("location: manage-affiliate-links.php");
   
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
        <h1 class="mt-4">Manage Affiliate</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Manage Affiliate Page</li>
        </ol>
     
        <div class="col-md-12">
            <div class="demo-box m-t-20">
                <div class="m-b-30">
                    <a href="add-affiliate-links.php">
                    <button id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="fa fa-plus" ></i></button>
                    </a>
                </div>
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
                <br>
                <br>

            <div class="table-responsive">
                <table class="table m-0 table-colored-bordered table-bordered-primary" id="dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Links</th>
                            <th>Name of website</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                     <?php $counter = 0; foreach($affiliate as $aff): ?>
            
					<tr>
                    <td><?php echo ++$counter; ?></td>
            <td><?php echo $aff['link']; ?></td>
            <td><?php echo $aff['alt']; ?></td>
						<td style="display: inline-flex;">
              <form action="edit-affiliate-links.php" method="post">
                <input type="hidden" name="edit_id" value="<?php echo $aff["id"]; ?>">
                  <button type="submit" name="btn_edit" class="btn" style="color: blue;"><i class="far fa-edit"></i></button>
              </form>
              
     

          <form action="manage-affiliate-links.php" method="post">
            <input type="hidden" name="delete_id" value="<?php echo $aff["id"]; ?>">
              <button type="submit" name="delete_btn" class="btn" onclick="return confirm('Are you sure you want to delete this record?');" style="margin-left: 10px;color: red;"><i class="fas fa-trash-alt"></i></button>
          </form>
              
            </td>
					</tr>
					
          <?php endforeach; ?>
                        
                    </tbody>
                                                  
                </table>
                </div>
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
    
