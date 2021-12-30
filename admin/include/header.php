<?php
session_start();
//$conn = mysqli_connect("localhost", "root", "", "oneupmeta");

    if(!isset($_SESSION['email'])) {
        header("Location: ../signin.php");
    }
    else{

    $id = $_SESSION['user'];
    $user = $_SESSION['username'];
    $lastname = $_SESSION['lastname'];
    $firstname = $_SESSION['firstname'];
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];

        if ($role === "user") {
            header("Location: ../user/index.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Admin Dashboard</title>
       
         <link href="assets/css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="assets/js/all.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body class="sb-nav-fixed">