<?php

   $idea_id = $_POST['idea_id'];
   $idea_title = $_POST['new_title'];

  $data = ['idea_id' => $idea_id, 'idea_title' => $idea_title];

$currServer = $_SERVER['HTTP_HOST'];
$api_host = ($currServer == 'localhost') ? "http://localhost/bugbuster" : "https://bugbuster.tmisura.sk";


$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $api_host."/api/api.php?endpoint=idea&idea_id=".$idea_id."&idea_title=".$idea_title,
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