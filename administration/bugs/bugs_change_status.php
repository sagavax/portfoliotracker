<?php

 include "../../includes/dbconnect.php";
 include "../../includes/functions.php";
     

$bug_id = $_POST['bug_id'];
$bug_status = $_POST['bug_status'];


//$update_status = "UPDATE bugs SET status='$bug_status' WHERE bug_id=$bug_id";
//$result = mysqli_query($link, $update_status) or die(mysqli_error($link));

$data = [
    'bug_id' => $bug_id,
    'bug_status' => $bug_status,
    'bug_application' => 'portfoliotracker'
];


$currServer = $_SERVER['HTTP_HOST'];
$api_host = ($currServer == 'localhost') ? "http://localhost/bugbuster" : "https://bugbuster.tmisura.sk";


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




// Add diary entry

$diary_text="Bug s id $bug_id status sa zmenil na $bug_status";
$create_record="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text', now())";
$result = mysqli_query($link, $create_record) or die("MySQLi ERROR: ".mysqli_error($link));
