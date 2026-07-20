<?php 
      session_start();
     
      include "../includes/dbconnect.php";
      include "../includes/functions.php";

  ?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfoliotracker</title>
    <!--<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,300italic,700,700italic,400italic' rel='stylesheet' type='text/css'>-->
    <link rel="stylesheet" href="../css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">  
    <script src="../js/admin_dashboard.js" defer></script>
    <script src="../js/timer.js" defer></script>
    <script defer src="../js/app_event_tracker.js?<?php echo time() ?>"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
  </head>
  
  <body>
  <?php
    
      include("../includes/header.php") ?>
          <div class="main_wrap">
            <div class="content">            
              <div class="dashboard_wrap">   
                  <div class="dashboard">  
                     <div class="tile_list">
                      <div class="tile" tile-id="ideas">
                        <div class="tile_title">Ideas</div>
                        <div class="tile_info"><span><?php echo GetCountIdeas() ?> ideas</span></div>
                      </div>
                      <div class="tile" tile-id="bugs">
                        <div class="tile_title">Bugs</div>
                        <div class="tile_info"><span><?php echo GetCountBugs()?> bugs</span></div>
                      </div>
                      <div class="tile" tile-id="app_log">
                        <div class="tile_title">Log</div>
                        <div class="tile_info"><span><?php echo GetCountLogRecords();?> record(s)</span></div>
                      </div>
                      <div class="tile" tile-id="maintenance">
                        <div class="tile_title">Maintenance</div>
                      </div>
                      <div class="tile" tile-id="settings">
                        <div class="tile_title">Settings</div>
                      </div>
                      <div class="tile" tile-id="dashboard_back">
                        <div class="tile_title">Back</div>
                      </div>
                    </div>
                </div><!--wrap-->    
                
                
              </div>
            </div>
     
  </body> 