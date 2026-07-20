<?php

    include_once 'includes/dbconnect.php';
    include_once 'includes/functions.php';

    $providerId = $_POST['provider_id'] ?? $_POST['providerId'] ?? $_GET['provider_id'] ?? $_GET['providerId'] ?? null;
    $providerLogo = $_POST['logo'] ?? $_POST['providerLogo'] ?? $_GET['logo'] ?? $_GET['providerLogo'] ?? null;

    if ($providerId !== null && $providerLogo !== null) {
        $providerId = (int)$providerId;
        $providerLogo = mysqli_real_escape_string($link, $providerLogo);

        $sql = "UPDATE providers SET provider_logo = '$providerLogo' WHERE id = $providerId";
        $result = mysqli_query($link, $sql) or die("MySQL ERROR: " . mysqli_error($link));

        if ($result) {
            echo "Provider logo updated successfully!";
        } else {
            echo "Error updating provider logo!";
        }
    } else {
        echo "Missing parameters!";
    }