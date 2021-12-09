
<?php
    require 'functions/dbconn.php';

    //All header tag to be included
    include('include/header.php');
?>

<?php
    //sidebar tag to be included
    include('include/sidebar.php');
?>
        
<main>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    <div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <a href="manage-authors.php" style="text-decoration: none;">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">registered users</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800 text-dark">
                <?php
                   $count=$connection->prepare("SELECT * FROM users WHERE `role` = 'user'");
                        $count->execute();
                        $users=$count->rowCount();
                        echo $users; 
                ?>

              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-green-300 text-dark"></i>
            </div>
          </div>
        </div>
        </a>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <a href="manage-recipe.php" style="text-decoration: none;">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">No. of subscribers</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800 text-dark">
                <?php
                  
                     $count=$connection->prepare("SELECT * FROM suscribers");
                        $count->execute();
                        $suscribers=$count->rowCount();
                        echo $suscribers; 
                ?>

              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-utensils fa-2x text-green-300 text-dark"></i>
            </div>
          </div>
        </div>
        </a>
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
    
