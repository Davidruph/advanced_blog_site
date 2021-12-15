<?php
	//start sesssion
	session_start();

	//db connection
	require 'dbconn.php';

	//get user id from logged in session
	$id = $_SESSION['user'];
	$log_date_time = date("Y-m-d H:i:s", time());
	$status = "Out";

	//update log details
    $query = mysqli_query($conn, "UPDATE log SET user_id = '$id', log_date_time = '$log_date_time', status = '$status' WHERE user_id = '$id'");
    if ($query) {
    	session_destroy();
		header('location: signin.php');
    }
	
?>