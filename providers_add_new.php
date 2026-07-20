<?php
    include_once 'includes/dbconnect.php';
    include_once 'includes/functions.php';



    $provider_name = $_POST['providerName'] ?? null;
    $provider_url = $_POST['providerUrl'] ?? null;
    $provider_logo = $_POST['providerLogo'] ?? null;
    $provider_description = mysqli_real_escape_string($link,$_POST['providerDescription']) ?? null;

    $add_provider = "INSERT INTO providers (provider_name, provider_url, provider_logo, provider_description) VALUES ('$provider_name', '$provider_url', '$provider_logo', '$provider_description')";
    $result = mysqli_query($link, $add_provider) or die("MySQL ERROR: " . mysqli_error($link));

    $get_provider_id = "SELECT id FROM providers WHERE provider_name = '$provider_name'";
    $result = mysqli_query($link, $get_provider_id) or die("MySQL ERROR: " . mysqli_error($link));
    $row = mysqli_fetch_array($result);
    $provider_id = $row['id'];

    echo json_encode(["status" => "success", "message" => "Provider added successfully.", "provider_id" => $provider_id]);