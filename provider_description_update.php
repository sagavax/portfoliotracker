<?php

     include_once 'includes/dbconnect.php';
    include_once 'includes/functions.php';


    $providerId = $_POST['providerId'];
    $description = $_POST['description'];

    $updateProvider = "UPDATE providers SET provider_description = '$description' WHERE id = $providerId";
    $result = mysqli_query($link, $updateProvider) or die("MySQL ERROR: " . mysqli_error($link));