<?php
    
    $currServer = $_SERVER['HTTP_HOST'];
$api_host = ($currServer == 'localhost') ? "http://localhost/bugbuster" : "https://bugbuster.tmisura.sk";

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $api_host."/api/api.php?endpoint=ideas&action=latest",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Accept: application/json"
    ],
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