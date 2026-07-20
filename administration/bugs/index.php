<?php 
     
      include "../../includes/dbconnect.php";
      include "../../includes/functions.php";

      session_start();


      // Dynamické nastavenie URL pre API podľa prostredia (localhost vs produkcia)      
      $currAddress = $_SERVER['SERVER_NAME'];
      if($currAddress == 'localhost') {
          $api_host = "http://localhost/bugbuster";
      } else {
          $api_host = "https://bugbuster.tmisura.sk";
      }

      $apiUrl = $api_host.'/api/api.php?endpoint=bugs&app_name=portfoliotracker';
    
    
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


?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfoliotracker - Bug Buster</title>
    <link rel="stylesheet" href="../../css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/bugs.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">  
    <script type="text/javascript" defer src="../../js/bugs.js?<?php echo time(); ?>"></script>
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
              <div class="list">              
                <div class="new_bug">
                    <form action="" method="post">
                        <input type="text" name="bug_title" placeholder="bug title here" id="bug_title" autocomplete="off">
                        <textarea name="bug_text" placeholder="Put a bug / error text here" id="markdown-input"></textarea>
                        <select name="bug_priority">
                            <option value="0">--- choose priority --- </option>
                            <option value = "low">low</option>
                            <option value = "medium">medium</option>
                            <option value = "high">high</option>
                            <option value = "critical">critical</option>
                        </select>

                        <select name="bug_status">
                            <option value="0">--- choose status --- </option>
                            <option value = "new">new</option>
                            <option value = "in progress">in progress</option>
                            <option value = "pending">pending</option>
                        </select>

                        <div class="new_bug_action">
                            <button type="submit" name="save_bug" class="button small_button">Save</button>
                        </div>
                </form>
                </div><!-- new bug-->
                
              <div class="bug_list">
               <?php if ($errorMessage): ?>
                      <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
                  <?php elseif ($data): ?>
                      <?php foreach ($data as $bug):
                          $bug_id         = $bug['bug_id'];
                          $bug_title      = $bug['bug_title'];
                          $bug_text       = $bug['bug_text'];
                          $bug_status     = $bug['bug_status'];
                          $bug_priority   = $bug['bug_priority'];
                          $is_fixed       = $bug['is_fixed'];
                          $nr_of_comments = $bug['count_comments'];
                          
                          $fixed_label = $is_fixed == 1 ? "<span class='fixed_label'>fixed</span>" : "";
                          
                          if ($is_fixed == 0) {
                              $action_buttons = "
                                  <button type='submit' name='delete_bug' class='button small_button'><i class='fa fa-times'></i></button>
                                  <button type='submit' name='mark_fixed' class='button small_button'><i class='fa fa-check'></i></button>
                              ";
                          } else {
                              $action_buttons = "<div class='span_modpack'>fixed</div>";
                          }
                    
                       $add_comment = "<button type='button' title='add comment' name='add_comment' class='button small_button'><i class='fa fa-comment'></i></button>";
                          $fixed_label = $is_fixed ? "<div class='span_fixed'>fixed</div>" : "";  
                      $action_buttons = $is_fixed ? 
                        "<button type='button' name='see_bug_details' title='bug details' class='button small_button'><i class='fa fa-eye'></i></button>" : // Pridanie komentára aj pre fixed stav
                        "<button type='button' name='see_bug_details' title='bug details' class='button small_button'><i class='fa fa-eye'></i></button>
                        <button type='button' name='move_to_ideas' title='move to ideas' class='button small_button'><i class='fas fa-chevron-right'></i></button>
                        <button type='button' name='mark_fixed' title='mark as fixed' class='button small_button'><i class='fa fa-check'></i></button>
                        <button type='button' name='bug_remove' title='remove bug' class='button small_button'><i class='fa fa-times'></i></button>
                        {$add_comment}"; // Pridanie komentára aj pre nefixed stav  
                       ?>

                      <div class="bug" bug-id="<?= $bug_id ?>">
                          <div class="bug_title"><?= htmlspecialchars($bug_title) ?> <?= $fixed_label ?></div>
                          <div class="bug_text"><?= htmlspecialchars($bug_text) ?></div>
                          <div class="bug_footer">
                              <div class="bug_status <?= $bug_status ?>"><?= htmlspecialchars($bug_status) ?></div>
                              <div class="bug_priority <?= $bug_priority ?>"><?= htmlspecialchars($bug_priority) ?></div>
                              <div class="nr_of_comments"><?= $nr_of_comments ?> comments</div>
                              <div class="bug_action">
                                  <?= $action_buttons ?>
                              </div>
                          </div>
                      </div>

                      <?php endforeach; ?>
                  <?php else: ?>
                      <p>Žiadne bugy.</p>
                  <?php endif; ?>
              </div><!-- bug list-->                
            </div><!-- list-->

        </div><!--content-->
      </div><!--main_wrap-->
      
     <dialog class="modal_show_status">
        <ul>
          <li>new</li>
          <li>in progress</li>
          <li>pending</li>
          <li>fixed</li>
          <li>reopened</li>
        </ul>
    </dialog>

    <dialog class="modal_show_priority">
      <ul>
        <li>low</li>
        <li>medium</li>
        <li>high</li>
        <li>critical</li>
      </ul> 
    </dialog>

    <dialog class="modal_add_comment">
      <div class="inner_wrap"> 
        <textarea name="comment_text" placeholder="Add a comment here"></textarea>
        <div class="modal_add_comment_action">
            <button type="submit" name="create_comment" class="button small_button">Add</button>
        </div>
       </div>
    </dialog>                  
    
              

  </body>
  </html> 
