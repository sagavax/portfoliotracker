<?php 
      include "../../includes/dbconnect.php";
      include "../../includes/functions.php";
    
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfoliotracker - Idea</title>
    <link rel="stylesheet" href="../../css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/ideas.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">  
    <script type="text/javascript" src="../../js/idea.js?<?php echo time(); ?>" defer></script>  <!-- this file contains functions for like, comment, apply -->
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
               <div class="fab fab-icon-holder" onclick="window.location.href='ideas.php';">
                <i class="fa fa-arrow-left"></i>
              </div>
              <div class="list">
               
                  <?php
                        $idea_id = $_GET['idea_id'];
                         $is_implemented = 0;


                         // Dynamické nastavenie URL pre API podľa prostredia (localhost vs produkcia)      
                        $currAddress = $_SERVER['SERVER_NAME'];
                        if($currAddress == 'localhost') {
                            $api_host = "http://localhost/bugbuster";
                        } else {
                            $api_host = "https://bugbuster.tmisura.sk";
                        }

                        $apiUrl = $api_host.'/api/api.php?endpoint=idea&idea_id='.$idea_id;
                        
                        
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

                          echo "<div class='idea' idea-id='$idea_id'>";

                            if ($errorMessage !== null) {
                                echo "<div class='error_message'>$errorMessage</div>";
                            } else {
                                
                                $idea_title = $data['idea_title'] ?? null;
                                $idea_text = $data['idea_text'] ?? null;
                                $idea_status = $data['idea_status'] ?? null;
                                $idea_priority = $data['idea_priority'] ?? null;
                                $is_implemented = $data['is_implemented'] ?? null;
                                $nr_of_comments = $data['nr_of_comments'] ?? null;
                                $idea_date = $data['added_date'] ?? null;
                                $idea_application = $data['idea_application'] ?? null;
                                $idea_author = $data['idea_author'] ?? null;

                                    if(isset($idea_title)){
                                        echo "<div class='idea_title'>$idea_title</div>";    
                                    }
                                    
                                    echo "<div class='idea_text'>$idea_text</div>";
                                    echo "<div class='idea_footer'>";
                                        $nr_of_comments = GetCountIdeaComments($idea_id);
                                        echo "<div class='nr_of_comments'>$nr_of_comments comment(s)</div>";
                                        echo "<div class='idea_status'>$idea_status</div><div class='idea_priority $idea_priority'>$idea_priority</div>";
                                        //echo "<button type='button' name='see_idea_details' class='button small_button'><i class='fa fa-eye'></i></button>";

                                        if ($is_implemented == 0) {
                                            echo "<form action='' method='post'>";
                                            echo "<input type='hidden' name='idea_id' value='$idea_id'>";
                                            echo "<button type='submit' name='to_apply' class='button small_button'><i class='fa fa-check'></i></button>";
                                            echo "</form>";
                                        } elseif ($is_implemented == 1) {
                                            echo "<div class='idea_implemented'>Implemented</div>";
                                        }
                                    echo "</div>"; // idea_footer
                                    echo "</div>"; // idea
                            }   
                                
                        

                    
                    //komenbte
                     $apiUrlComments = $api_host.'/api/api.php?endpoint=idea_comments&idea_id='.$idea_id;

                    $ch = curl_init();

                            curl_setopt_array($ch, [
                                CURLOPT_URL => $apiUrlComments,
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
                        ?>        

                    <div class="idea_comments_list">
                              <?php

                                if ($errorMessage !== null) {
                                    echo "<div class='error_message'>$errorMessage</div>";
                                }     
                                else{
                                    
                                }
                                foreach ($data as $comment) {
                                    $comm_id = $comment['comm_id'];
                                    $comm_title = $comment['idea_comm_header'];
                                    $comm_text = $comment['idea_comment'];
                                    $comm_date = $comment['comment_date'];

                                    echo "<div class='idea_comment' data-comment-id=$comm_id>";
                                        echo "<div class='connector-line'></div>";
                                        echo "<div class='idea_top_banner'></div>";
                                        
                                        if($comm_title!=""){
                                            echo "<div class='idea_comm_title'>$comm_title</div>";    
                                        }
                                        echo "<div class='idea_comm_text'>$comm_text</div>";
                                        echo "<div class='idea_comm_action'>";
                                            echo "<div class='idea_comm_date'>$comm_date</div>";
                                            echo "<button type='button' name='delete_comment' class='button small_button'><i class='fa fa-times'></i></button>";
                                        echo "</div>"; // idea_comm_action
                                    echo "</div>"; // idea_comment
                                }    
                              ?>  

                              
                             <div class="idea_comment_new">
                                <h4>Add a comment</h4>
                                
                                <input type="text" name="idea_comment_header" autocomplete="off" placeholder="type title here">
                                <textarea name="idea_comment" placeholder="type comment here..."></textarea>
                                
                                <div class="idea_comment_action">
                                  <?php
                                        if($is_implemented ==0){
                                            echo "<button name='save_idea_comment' class='button small_button'>save</button>";
                                        } else if ($is_implemented==1){
                                            echo "<button name='save_idea_comment' disabled class='button small_button'>save</button>";
                                        }
                                  ?>  
                                  
                                </div>
                            
                        </div><!--idea comment -->
                    </div><!-- idea comment list-->    
                </div><!-- list-->

        </div><!--content-->
      </div><!--main_wrap-->
    </body>
  </html> 
