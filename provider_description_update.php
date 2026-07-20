<?php

    include_once 'includes/dbconnect.php';
    include_once 'includes/functions.php';

    $providerId = $_POST['provider_id'] ?? $_POST['providerId'] ?? null;
    $description = $_POST['description'] ?? null;

    if ($providerId !== null && $description !== null) {
        $providerId = (int)$providerId;
        $description = mysqli_real_escape_string($link, $description);

        $updateProvider = "UPDATE providers SET provider_description = '$description' WHERE id = $providerId";
        $result = mysqli_query($link, $updateProvider) or die("MySQL ERROR: " . mysqli_error($link));

        if ($result) {
            echo "Provider description updated successfully!";
        } else {
            echo "Error updating provider description!";
        }
    } else {
        echo "Missing parameters!";
    }