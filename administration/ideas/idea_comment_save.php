<?php 

      include "../../includes/dbconnect.php";
      include "../../includes/functions.php";
    

    $api_host = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') ? 'http://localhost/bugbuster' : 'https://bugbuster.sk';


    $idea_id = $_POST['idea_id'];
    $idea_comment = $_POST['comment'];
    $idea_comment_header = $_POST['comment_title'];

    $data = [
        'idea_comment' => $idea_comment,
        'idea_comment_header' => $idea_comment_header,
        'idea_id' => $idea_id
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_host."/api/api.php?endpoint=idea_comments&idea_id=$idea_id",
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
        
        $diary_text="Bol pridany novy kommentar k idei id <b>$idea_id</b>";
        $sql="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text',now())";
        $result = mysqli_query($link, $sql) or die("MySQLi ERROR: ".mysqli_error($link));
        //echo "<script>message('Comment added','success')</script>";
        //header("location:idea.php");
        exit;