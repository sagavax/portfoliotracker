<?php
     include("includes/dbconnect.php");
     include("includes/functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="css/influencers.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" sizes="32x32" href="investment.png">
    <!-- <script src="js/clock.js?<?php echo time() ?>" defer></script> -->
    <script src="js/influencers.js?<?php echo time() ?>" defer></script>
    <title>Portfolio Ticker - Influencers</title>
</head>
<body>
    <header>
      <a href="."><img src="portfolio-ticker-logo.svg" alt="Portfolio Ticker"></a><input type="text" name="search_inflences"  id="search_influencers" autocomplete="off" placeholder="search influencers here"><button type="button" name="clear_search" title="clear search" class="button small_button clear_button tooltip>"><i class="fa fa-times"></i></button>
            <button type="button" id="btnAddNewInfluencer" class="button small_button" name="new_influencer" title="add new influencer"><i class="fa fa-plus"></i></button><div class="clockWrapper"><button type ="button" class="secondary" name="worldclock"  id="worldclock">World Clock</button><div id="clock">--:--:--</div></div>
    </header>
    
    <div class="container">
         <div class="sidebar">
                <nav>
                    <ul>
                        <?php
                            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                            $base = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/portfoliotracker/';
                        ?>

                        <li><a href="<?= $base ?>index.php"><i class="fas fa-home"></i> Domov</a></li>
                        <li><a href="<?= $base ?>/portfolio/index.php"><i class="fas fa-chart-line"></i> Portfólio</a></li>
                        <li><a href="<?= $base ?>providers.php"><i class="fas fa-building"></i> Poskytovatelia</a></li>
                        <li><a href="<?= $base ?>influencers.php"><i class="fas fa-users"></i> Influencers</a></li>
                        <li><a href="<?= $base ?>news.php"><i class="fas fa-newspaper"></i> Novinky</a></li>
                        <li><a href="<?= $base ?>settings.php"><i class="fas fa-cogs"></i> Nastavenia</a></li>
                        <li><a href="<?= $base ?>notes.php"><i class="fas fa-sticky-note"></i> Poznámky</a></li>
                        <li><a href="<?= $base ?>logout.php"><i class="fas fa-sign-out-alt"></i> Odhlásiť sa</a></li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                <div class="influencers">
                    <h3>Influencers:</h3>
                    <?php echo GetAllInfluencers(); ?>
                </div>
                <div class="list_of_videos">
                    <div class="loading" style="display: none;">Loading...</div>
                    <div class="video_list"></div>
                </div>        
            </div><!--content-->    
    </div><!--container-->


    <div id="modalAddNewIflencer" class="modal-overlay">
       <div class="modal-container">
            <h3>Add new influencer</h3>
            <div id="influencersContent" class="modal-content">
                <input type="text" autocomplete="off" name="influencer_name" id="influencer_name" placeholder="Influencer name">
                <input type="text" autocomplete="off" name="influencer_url" id="influencer_url" placeholder="Influencer url">
                <input type="text" autocomplete="off" name="influencer_image" id="influencer_image" placeholder="Influencer image">
                <textarea name="influencer_description" id="influencer_description" placeholder="Influencer description"></textarea>
                <div class="modal-actions">
                    <button id="modalAddNewIflencerSave" type="button" ckass="secondary">Uložiť</button>
                    <button id="modalAddNewIflencerClose" type="button" ckass="secondary">Zatvoriť</button>
                </div>    
            </div>
       </div> 
    </div>    
</body>
</html>