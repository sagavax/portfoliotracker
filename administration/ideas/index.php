<?php
      include "../../includes/dbconnect.php";
      include "../../includes/functions.php";
      session_start();

      $currAddress = $_SERVER['SERVER_NAME'];
      if($currAddress == 'localhost') {
          $api_host = "http://localhost/bugbuster";
      } else {
          $api_host = "https://bugbuster.tmisura.sk";
      }

      $apiUrl = $api_host.'/api/api.php?endpoint=ideas&app_name=portfoliotracker';
    
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
    <title>Portfoliotracker</title>
    <link rel="stylesheet" href="../../css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/ideas.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">  
    <script type="text/javascript" defer src="../../js/ideas.js?<?php echo time(); ?>"></script>
    <script type="text/javascript" defer src="../../js/message.js"></script>
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
              
              <h3>Ideas for the informating system</h3>
              <div class="new_idea">
                <form action="" method="post">
                      <input type="text" name="idea_title" placeholder="idea title here" id="idea_title" autocomplete="off">
                      <textarea name="idea_text" placeholder="Put a your idea(s) here..." id="idea_text"></textarea>
                      <select name="idea_priority">
                        <option value="0">--- choose priority --- </option>
                        <option value = "low">low</option>
                        <option value = "medium">medium</option>
                        <option value = "high">high</option>
                        <option value = "critical">critical</option>
                      </select>

                      <select name="idea_status">
                          <option value="0">--- choose status --- </option>
                          <option value = "new">new</option>
                          <option value = "in progress">in progress</option>
                          <option value = "pending">pending</option>
                          <option value = "applied">applied</option>
                          <option value = "canceled">canceled</option>
                      </select>
                      <div class="new_idea_action">
                        <button type="submit" name="save_idea" class="button small_button">Save</button>
                      </div>
               </form>
              </div><!-- new idea-->
               <div class="ideas_list">

                <?php if ($errorMessage): ?>
                <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
                    <?php elseif ($data): ?>
                    <?php foreach ($data as $idea):
                        $idea_id        = $idea['idea_id'];
                        $idea_title     = $idea['idea_title'];
                        $idea_text      = $idea['idea_text'];
                        $idea_status    = $idea['idea_status'];
                        $idea_priority  = $idea['idea_priority'];
                        $is_applied     = $idea['is_implemented'];
                        $nr_of_comments = $idea['count_comments'];
                    ?>

                    <div class="idea" idea-id="<?= $idea_id ?>">
                        <div class="idea_title"><?= htmlspecialchars($idea_title) ?></div>
                        <div class="idea_text"><?= htmlspecialchars($idea_text) ?></div>
                        <div class="idea_footer">
                            <input type="hidden" name="idea_id" value="<?= $idea_id ?>">
                            <input type="hidden" name="is_applied" value="<?= $is_applied ?>">
                            <div class="nr_of_comments"><?= $nr_of_comments ?> comment(s)</div>
                            <div class="idea_status"><?= htmlspecialchars($idea_status) ?></div>
                            <div class="idea_priority"><?= htmlspecialchars($idea_priority) ?></div>
                            <button type="submit" name="see_idea_details" class="button small_button">
                                <i class="fa fa-eye"></i>
                            </button>
                            <?php if ($is_applied == 0): ?>
                                <button type="submit" name="delete_idea" class="button small_button">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button type="submit" name="to_apply" class="button small_button">
                                    <i class="fa fa-check"></i>
                                </button>
                            <?php else: ?>
                                <div class="span_modpack">applied</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Žiadne nápady.</p>
                <?php endif; ?>

                </div><!-- ideas_list-->             
            </div><!-- list-->
        </div><!--content-->
      </div><!--main_wrap-->
      
      <dialog class="modal_show_status">
        <ul>
          <li>new</li>
          <li>in progress</li>
          <li>pending</li>
          <li>applied</li>
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
      <textarea name="comment_text" placeholder="Add a comment here"></textarea>
      <button type="submit" name="add_comment" class="button small_button">Add</button>
    </dialog>                  


  </body>
  </html> 
