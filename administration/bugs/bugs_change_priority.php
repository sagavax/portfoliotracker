<?php

 include "../../includes/dbconnect.php";
 include "../../includes/functions.php";
     

$bug_priority=$_POST['bug_priority'];
$bug_id = $_POST['bug_id'];

/* $update_prioty = "UPDATE bugs SET priority='$bug_priority' WHERE bug_id=$bug_id";
$result = mysqli_query($link, $update_prioty); */

$data = [
    'bug_id' => $bug_id,
    'bug_priority' => $bug_priority,
    'bug_application' => 'portfoliotracker'
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $api_host."/api/api.php?endpoint=bugs",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Accept: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($data),
]);

$response = curl_exec($curl);
$errno = curl_errno($curl);
$error = curl_error($curl);

if ($errno) {
    http_response_code(500);
    die(json_encode(['error' => "cURL Error: $error"]));
} else {
    echo $response; // Vráť odpoveď z API
}

//add to audit log
$diary_text="Pre bug s id $bug_id bol zmenena prioritita na $bug_priority";
$create_record="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text', now())";
$result = mysqli_query($link, $create_record) or die("MySQLi ERROR: ".mysqli_error($link));