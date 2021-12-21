<?php
    //All header tag to be included
    include('include/header.php');
?>

<?php 
  include('settingsController.php');
?>


<?php
    //sidebar tag to be included
    include('include/sidebar.php');
?>


<main>
    <div class="container-fluid">
        <h1 class="mt-4">Settings</h1>
        <ol class="breadcrumb mb-5">
            <li class="breadcrumb-item active">Settings Page</li>
        </ol>
          <!-- if there is an error, echo all of them -->
            <?php if (count($errors) > 0): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php foreach($errors as $error): ?> 
                <li class="text-danger"><?php echo $error; ?></li>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  
                <?php endforeach; ?>
              </div>
              <?php endif; ?>

        <!-- if there is success, echo all of them -->
       <?php if (count($success) > 0): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php foreach($success as $succes): ?> 
                <li class="text-green"><?php echo $succes; ?></li>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  
                <?php endforeach; ?>
              </div>
              <?php endif; ?>

        <?php
              $qry = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
                if(mysqli_num_rows($qry) > 0){
                    $row = mysqli_fetch_array($qry);
                   $user_name = $row['username'];
                   $lastname = $row['lastname'];
                   $firstname = $row['firstname'];
                   $email = $row['email'];
                   
                }
        ?>

     <div class="card-box">
        <div class="col-md-9">

        <form class="form-horizontal mb-4" method="post" autocomplete="off" action="settings.php">
          
          <div class="row mb-3">
            <div class="col">
              <label for="full_name">Last Name</label>
              <input type="text" class="form-control" value="<?= $lastname ?? '' ?>" name="lastname" required>
            </div>

            <div class="col">
              <label for="full_name">First Name</label>
              <input type="text" class="form-control" value="<?= $firstname ?? '' ?>" name="firstname" required>
            </div>
          </div>

           <div class="row mb-3">
            <div class="col">
              <label for="full_name">Email Address</label>
              <input type="email" class="form-control" value="<?= $email ?? '' ?>" name="email" required>
            </div>

            <div class="col">
              <label for="full_name">Username</label>
              <input type="text" class="form-control" value="<?= $user_name ?? '' ?>" name="username" required>
            </div>
          </div>     

          <div class="row mb-3">
            <div class="col">
              <label for="full_name">Enter Password</label>
              <input type="password" class="form-control" name="password" required>
            </div>
          </div>       

          <div class="row mb-3">
            <div class="col">
               <div class="form-group mt-3">
                 <div class="g-recaptcha w-100" data-sitekey="6LdTmk4cAAAAABdAY63Ks679Amk_TsqvCZkf5N3_"></div>
              </div>
            </div>
          </div>  
          
           <div class="row mt-3">
                <div class="col">
                    <button type="submit" class="btn btn-primary btn-block" name="submit">
                        Update
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
