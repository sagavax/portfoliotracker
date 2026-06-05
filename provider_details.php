<?php
    include_once 'includes/dbconnect.php';
    include_once 'includes/functions.php';


    $providerId = $_GET['providerId'] ?? null;


    $getProvideDatails = "SELECT * FROM providers WHERE id = $providerId";
    $result = mysqli_query($link, $getProvideDatails) or die(mysqli_error($link));
    
    echo "<div class='provider'>";  
        while($row = mysqli_fetch_assoc($result)) {
            $providerName = $row['provider_name'];
            $providerDescription = $row['provider_description'];
            $providerLogo = $row['provider_logo'];
            $providerUrl = $row['provider_url'];

            echo "<div class='provider_name_wrapper'><h2>$providerName</h2><div class='provider_logo'><img src='$providerLogo' alt='$providerName logo'></div></div>";
            echo "<div class='provider_description' contenteditable='true'>$providerDescription</div>";
            echo "<div class='provider_url'><a href='$providerUrl' target='_blank'>Visit Website</a> <button type='button' class='button small_button' name='edit_provider_url' title='edit provider url'>Edit</button></div>";
        }

    echo "</div>";        