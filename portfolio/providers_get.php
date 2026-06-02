<?php

    include('../includes/dbconnect.php');
    include('../includes/functions.php');


    $get_providers = "SELECT id, provider_name FROM providers ORDER BY provider_name ASC LIMIT 100";
    $result = mysqli_query($link, $get_providers) or die("MySQL ERROR: " . mysqli_error($link));
    
    while ($row = mysqli_fetch_array($result)) {
        //output json
        $provider_id = $row['id'];
        $provider_name = $row['provider_name'];
        echo '<div class="provider_card" data-id="'.$provider_id.'" data-name="'.$provider_name.'">'.$provider_name.'</div>';
    }

     echo '<div class="provider_card" data-name="new_provider"><i class="fa fa-plus"></i> New provider</div>';