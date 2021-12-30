<?php

//db connection
require '../dbconn.php';

//fetch mail
$sql = 'SELECT * FROM tblmail';
$statement = $connection->prepare($sql);
$statement->execute();
$mails = $statement->fetchAll(PDO::FETCH_ASSOC);
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
        <h1 class="mt-4">Received Mails</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">View Mails Page</li>
        </ol>
     
        <div class="col-md-12">
            <div class="demo-box m-t-20">
                <div class="m-b-30">
                    <a href="single.php">
                    <button id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="fa fa-plus" ></i></button>
                    </a>
                </div>
                
                <br>
                <br>

            <div class="table-responsive">
                <table class="table m-0 table-hover table-condensed table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                     <?php foreach($mails as $mail): ?>
            
            					<tr>
            						<td><?php echo $mail['id']; ?></td>
                        <td><?php echo $mail['name']; ?></td>
                        <td><?php echo $mail['email']; ?></td>
            			<td><?php echo $mail['message']; ?></td>
                        <td><?php echo $mail['PostingDate']; ?></td>
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
    
