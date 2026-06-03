<?php

    include('../includes/dbconnect.php');
    include('../includes/functions.php');

    $provider = $_POST['provider'];

    //check if provider already exists
    $check_provider = "SELECT * FROM `providers` WHERE `provider_name`='$provider'";
    $result = mysqli_query($link, $check_provider) or die("MySQLi ERROR: ".mysqli_error($link));
    if(mysqli_num_rows($result) > 0){
        echo json_encode(["status" => "error", "message" => "Provider already exists."]);
        exit();
    } else {
        $create_provider = "INSERT INTO `providers` (`provider_name`) VALUES ('$provider')";
        $result = mysqli_query($link, $create_provider) or die("MySQLi ERROR: ".mysqli_error($link));  
        
       //get the id of the newly created provider
        $provider_id = mysqli_insert_id($link); 
        
        echo json_encode(["status" => "success", "message" => "Provider added successfully.", "provider_id" => $provider_id]);
    }

    