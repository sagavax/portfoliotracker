<?php

   $bug_id = $_POST['bug_id'];
   $bug_description = $_POST['new_description'];

  $data = ['bug_id' => $bug_id, 'bug_description' => $bug_description];

$currServer = $_SERVER['HTTP_HOST'];
$api_host = ($currServer == 'localhost') ? "http://localhost/bugbuster" : "https://bugbuster.tmisura.sk";


$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $api_host."/api/api.php?endpoint=bug&bug_id=".$bug_id."&bug_description=".$bug_description,
    CURLOPT_RETURNTRANSFER => true,
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
    echo $response;
}