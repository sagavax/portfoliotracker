<?php

 include "../../includes/dbconnect.php";
 include "../../includes/functions.php";
     

$idea_priority=$_POST['idea_priority'];
$idea_id = $_POST['idea_id'];

/* $update_prioty = "UPDATE ideas SET priority='$idea_priority' WHERE idea_id=$idea_id";
$result = mysqli_query($link, $update_prioty); */

$data = [
    'idea_id' => $idea_id,
    'idea_priority' => $idea_priority,
    'idea_application' => 'portfoliotracker'
];

$api_host = ($_SERVER['HTTP_HOST'] == 'localhost') ? "http://localhost/bugbuster" : "https://bugbuster.tmisura.sk";

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $api_host."/api/api.php?endpoint=ideas",
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
$diary_text="Pre idea s id $idea_id bol zmenena prioritita na $idea_priority";
$create_record="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text', now())";
$result = mysqli_query($link, $create_record) or die("MySQLi ERROR: ".mysqli_error($link));




/* update_prioty = "UPDATE ideas SET priority='$idea_priority' WHERE idea_id=$idea_id";
$result = mysqli_query($link, $update_prioty); */

//add to audit log
$diary_text="Portfoliotracker: Idea s id $idea_id bola priorita zmenena na $idea_priority";
$create_record="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text', now())";
$result = mysqli_query($link, $create_record) or die("MySQLi ERROR: ".mysqli_error($link));