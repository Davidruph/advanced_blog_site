<?php
//db connection included
require 'functions/dbconn.php';

$errors = array();
$success = array();

if (isset ($_POST['btnupdate'])){
  $username = $_POST['username'];
  $lastname = $_POST['lastname'];
  $firstname = $_POST['firstname'];
  $email = $_POST['email'];
  $role = $_POST['role'];
  $id = $_POST['edit_id'];

  $sql = 'UPDATE users SET username=:username, lastname=:lastname, firstname=:firstname, email=:email, role=:role WHERE id=:id';
  $statement = $connection->prepare($sql);
  if ($statement->execute([':username' => $username, ':lastname' => $lastname, ':firstname' => $firstname, ':email' => $email, ':role' => $role, ':id' => $id])) {
    $success['data'] = "user details updated successfully <a href='manage-users.php'>Go Back</a>";
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
        <h1 class="mt-4">Users</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Edit user Page</li>
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
                   
                  $sql = 'SELECT * FROM users WHERE id=:id';
                  $statement = $connection->prepare($sql);
                  $statement->execute([':id' => $id ]);
                  $users = $statement->fetchAll(PDO::FETCH_OBJ);

                  
                  foreach ($users as $user) {
                    ?>
            
                   <form action="edit-users.php" method="post">
                    <input type="hidden" name="edit_id" value="<?= $user->id; ?>">
                    <div class="form-group">
                        <label class="col-md-6 control-label">Username</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?= $user->username ?>" name="username" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-6 control-label">Last name</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?= $user->lastname ?>" name="lastname" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label">First name</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?= $user->firstname ?>" name="firstname" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label">Email</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?= $user->email ?>" name="email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-6 control-label">Role</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?= $user->role ?>" name="role" required>
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

