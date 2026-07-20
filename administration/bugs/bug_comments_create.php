<?php

    include "../../includes/dbconnect.php";   
     
    $api_host = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') ? 'http://localhost/bugbuster' : 'https://bugbuster.sk';


    $bug_id = $_POST['bug_id'];
    $bug_comment = $_POST['comment_text'];
    $bug_comment_header = $_POST['comment_header'];

    $data = [
        'bug_comment' => $bug_comment,
        'bug_comment_header' => $bug_comment_header,
        'bug_id' => $bug_id
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_host."/api/api.php?endpoint=bug_comments&bug_id=$bug_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
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
        
        $diary_text="Bol pridany novy kommentar k bugu id <b>$bug_id</b>";
        $sql="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text',now())";
        $result = mysqli_query($link, $sql) or die("MySQLi ERROR: ".mysqli_error($link));
        //echo "<script>message('Comment added','success')</script>";
        //header("location:idea.php");
        exit;


?>