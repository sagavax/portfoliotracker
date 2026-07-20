<?php 
       include "../../includes/dbconnect.php";
      include "../../includes/functions.php";
     
      session_start();

?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfoliotracker</title>
    <link rel="stylesheet" href="../../css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/bugs.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">  
    <script type="text/javascript" defer src="../../js/bug.js?<?php echo time(); ?>"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon-32x32.png">

  </head>
  <body>
        <?php include("../../includes/header.php") ?>   
      <div class="main_wrap">
      <div class="tab_menu">
          <?php include("../../includes/menu.php"); ?>
        </div>    
        <div class="main_wrap">
         <div class="content">
               <div class="fab fab-icon-holder" onclick="window.location.href='bugs.php';">
                <i class="fa fa-arrow-left"></i>
              </div>
              <div class="list">
               
                  <?php
                        $bug_id = $_GET['bug_id'];

                         $is_fixed = 0;


                         // Dynamické nastavenie URL pre API podľa prostredia (localhost vs produkcia)      
                        $currAddress = $_SERVER['SERVER_NAME'];
                        if($currAddress == 'localhost') {
                            $api_host = "http://localhost/bugbuster";
                        } else {
                            $api_host = "https://bugbuster.tmisura.sk";
                        }

                        $apiUrl = 'http://localhost/bugbuster/api/api.php?endpoint=bug&bug_id='.$bug_id;
                        
                        
                        // Inicializácia cURL pro požiadavku na API
                        $ch = curl_init();

                            curl_setopt_array($ch, [
                                CURLOPT_URL => $apiUrl,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_TIMEOUT => 10,
                                CURLOPT_HTTPGET => true,
                            ]);

                            $response = curl_exec($ch);
                            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            $curlError = curl_error($ch);

                            $data = null;
                            $errorMessage = null;

                            if ($response === false || $curlError !== '') {
                                $errorMessage = 'Nepodarilo sa spojiť s API.';
                            } elseif ($httpCode !== 200) {
                                $errorMessage = 'API vrátilo HTTP kód: ' . $httpCode;
                            } else {
                                $data = json_decode($response, true);

                                if (json_last_error() !== JSON_ERROR_NONE) {
                                    $errorMessage = 'Odpoveď z API nie je validný JSON.';
                                }
                            }

                        if ($data !== null) {             
                              $row = $data[0];                          
                              $bug_id = $row['bug_id'];
                              $bug_title = $row['bug_title'];
                              $bug_text = $row['bug_text'];
                              $is_fixed = $row['is_fixed'];
                              $added_date = $row['added_date'];
                              $bug_status = $row['bug_status'];
                              $bug_priority = $row['bug_priority'];
                              $added_date = $row['added_date'];

                              echo "<div class='bug'>";
                                    echo "<div class='bug_title'>$bug_title</div>";
                                    echo "<div class='bug_text'>$bug_text</div>";
                                    echo "<div class='bug_footer'>";
                                       echo "<div class='bug_status $bug_status'>$bug_status</div><div class='bug_priority $bug_priority'>$bug_priority</div><div class='bug_added_date'>$added_date</div>";   
                                       if($is_fixed==0){
                                                echo "<button type='button' title='mark the bug as fixed' name='bug_set_fixed' class='button small_button'><i class='fa fa-check'></i></button>";
                                                
                                        } elseif ($is_fixed==1){

                                            echo "<button type='button' title='mark the bug as fixed' class='button small_button' name='reopen_bug'>Reopen</button><div class='bug_fixed'>fixed</div>";
                                        }             

                                          
                                    echo "</div>";
                              echo "</div>"; // bug
                        }      
                  ?>

                    <div class="bug_comments_list">
                              <?php
                              $apiUrl = 'http://localhost/bugbuster/api/api.php?endpoint=bug_comments&bug_id='.$bug_id;
                              $ch = curl_init();
                              curl_setopt_array($ch, [
                                  CURLOPT_URL => $apiUrl,
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_TIMEOUT => 10,
                                  CURLOPT_HTTPGET => true,
                              ]);
                              $response = curl_exec($ch);
                              $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
                              $curlError = curl_error($ch);

                              $commentsData = null;
                              $errorMessage = null;

                              if ($response === false || $curlError !== '') {
                                  $errorMessage = 'Nepodarilo sa spojiť s API.';
                              } elseif ($httpCode !== 200) {
                                  $errorMessage = 'API vrátilo HTTP kód: ' . $httpCode;
                              } else {
                                  $commentsData = json_decode($response, true);

                                  if (json_last_error() !== JSON_ERROR_NONE) {
                                      $errorMessage = 'Odpoveď z API nie je validný JSON.';
                                  }
                              }

                              if ($commentsData !== null) {
                                  foreach ($commentsData as $row_comment) {


                                    $comm_id = $row_comment['comm_id'];
                                    $comm_title = $row_comment['bug_comm_header'];
                                    $comm_text = $row_comment['bug_comment'];
                                    $comm_date = $row_comment['comment_date'];

                                    echo "<div class='bug_comment' data-comment-id='$comm_id'>";
                                        echo "<div class='connector-line'></div>";
                                        echo "<div class='bug_top_banner'></div>";
                                        if($comm_title!=""){
                                            echo "<div class='bug_title'>$comm_title</div>";    
                                        }
                                        echo "<div class='bug_text'>$comm_text</div>";
                                        echo "<div class='bug_comm_action'><input type='hidden' name='comm_id' value=$comm_id>$comm_date<button type='button' name='delete_comment' class='button small_button'><i class='fa fa-times'></i></button></div>";
                                    echo "</div>";
                                 }   
                              } else {
                                  echo "<div class='error_message'>Žiadne komentáre k tomuto bug-u.</div>";
                              }
                              ?>  

                              
                             <div class="bug_comment_new">
                                <h4>Add a comment</h4>
                                <form action="" method="post">
                                  <input type="hidden" name="bug_id" value="<?php echo $bug_id?>">
                                  <input type="text" name="bug_comment_header" autocomplete="off" placeholder="type title here">
                                  <textarea name="bug_comment" placeholder="type comment here..."></textarea>
                                  
                                  <div class="bug_comment_action">
                                    <?php
                                          if($is_fixed==0){
                                              echo "<button name='save_comment' class='button small_button'>save</button>";
                                          } else if ($is_fixed==1){
                                              echo "<button name='save_comment' disabled class='button small_button'>save</button>";
                                          }
                                    ?>  
                                    
                                   </div>
                                </form>   
                        </div><!--bug comment -->
                    </div><!-- bug comment list-->    
                     
                 
              </div><!-- list-->

        </div><!--content-->
      </div><!--main_wrap-->
  </body>
  </html> 
