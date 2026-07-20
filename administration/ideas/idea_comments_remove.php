<?php


include "../../includes/dbconnect.php";
include "../../includes/functions.php";

$comment_id = (int)($_POST['comm_id']);

$api_host = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') ? 'http://localhost/bugbuster' : 'https://bugbuster.sk';

$data = [
    'comment_id' => $comment_id
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $api_host."/api/api.php?endpoint=idea_comments",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "DELETE",
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