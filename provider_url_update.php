<?php

    include_once 'includes/dbconnect.php';
    include_once 'includes/functions.php';


    $provider_id = $POST['provider_id'] ?? null;
    $provider_url = $_POST['url'] ?? null;


    $update_provider_url = "UPDATE providers SET provider_url = '$provider_url' WHERE id = $provider_id";
    $result = mysqli_query($link, $update_provider_url) or die(mysqli_error($link));
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update provider URL.']);
    }