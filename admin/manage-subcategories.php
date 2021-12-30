
<?php

//db connection
require '../dbconn.php';
$errors = array();
$success = array();
//fetch category
// $sql = 'SELECT * FROM subcategory WHERE Is_Active=1';
// $statement = $connection->prepare($sql);
// $statement->execute();
// $subcategory = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
//delete category

if (isset($_POST['delete_btn'])) {
  $id = $_POST['delete_id'];
  $sql = 'UPDATE subcategory SET Is_Active = 0 WHERE id=:id';
  $statement = $connection->prepare($sql);
  if ($statement->execute([':id' => $id])) {
   
    $success['confirm'] = "One record has been deleted!";
    header("Location: manage-subcategories.php");
   
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
        <h1 class="mt-4">Manage Subcategory</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Manage Subategory Page</li>
        </ol>
     
        <div class="col-md-12">
            <div class="demo-box m-t-20">
                <div class="m-b-30">
                    <a href="add-subcategory.php">
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
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    <?php
                    $query=mysqli_query($conn,"SELECT category.CategoryName as catname,subcategory.subcategory as subcatname,subcategory.id as subcatid from subcategory join category on subcategory.category_id=category.id where subcategory.Is_Active=1");

                    $rowcount=mysqli_num_rows($query);
                    if($rowcount==0)
                    {
                    ?>
                    <tr>

                    <td colspan="7" align="center"><h3 style="color:red">No record found</h3></td>
                    <tr>
                    <?php 
                    } else {

                    while($row=mysqli_fetch_array($query))
                    {
                    ?>
                
					<tr>
						<td><?php echo $row['subcatid']; ?></td>
                        <td><?php echo $row['catname']; ?></td>
                        <td><?php echo $row['subcatname']; ?></td>
						<td style="display: inline-flex;">
              <form action="edit-subcategory.php" method="post">
                <input type="hidden" name="edit_id" value="<?php echo $row["subcatid"]; ?>">
                  <button type="submit" name="btn_edit" class="btn" style="color: blue;"><i class="far fa-edit"></i></button>
              </form>
              
     

          <form action="manage-subcategories.php" method="post">
            <input type="hidden" name="delete_id" value="<?php echo $row["subcatid"]; ?>">
              <button type="submit" name="delete_btn" class="btn" onclick="return confirm('Are you sure you want to delete this record?');" style="margin-left: 10px;color: red;"><i class="fas fa-trash-alt"></i></button>
          </form>
              
            </td>
					</tr>
                    <?php
					}
                }
                 ?>
                        
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
    
