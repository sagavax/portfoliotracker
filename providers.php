<?php
    include_once 'includes/dbconnect.php';
    include_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portforlio Tracker - Providers</title>
        <link rel="stylesheet" href="css/style.css?<?php echo time() ?>" />
        <link rel="stylesheet" href="css/providers.css?<?php echo time() ?>" />
        <!-- <link rel="stylesheet" href="css/message.css?<?php echo time() ?>" /> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link rel="icon" type="image/png" sizes="32x32" href="investment.png">
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
        <script src="js/clock.js?<?php echo time() ?>" defer></script>
        <script src="js/providers.js?<?php echo time() ?>" defer></script>
</head>
<body>
     <header>
          <a href="."><img src="portfolio-ticker-logo.svg" alt="Portfolio Ticker" /></a>
          <div class="clockWrapper"><button type ="button" class="secondary" name="worldclock"  id="worldclock">World Clock</button><div id="clock">--:--:--</div></div>
      </header>
        <div class="container">
            <div class="sidebar">
                <nav>
                    <ul>
                        <?php
                            $base = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') ? 'http://localhost/portfoliotracker/' : 'https://portfoliotracker.tmisura.sk/';
                        ?>

                        <li><a href="<?= $base ?>index.php"><i class="fas fa-home"></i> Domov</a></li>
                        <li><a href="<?= $base ?>/portfolio/index.php"><i class="fas fa-chart-line"></i> Portfólio</a></li>
                        <li><a href="<?= $base ?>providers.php"><i class="fas fa-building"></i> Poskytovatelia</a></li>
                       <!--  <li><a href="<?= $base ?>influencers.php"><i class="fas fa-users"></i> Influencers</a></li>
                        <li><a href="<?= $base ?>news.php"><i class="fas fa-newspaper"></i> Novinky</a></li>
                        <li><a href="<?= $base ?>settings.php"><i class="fas fa-cogs"></i> Nastavenia</a></li>
                        <li><a href="<?= $base ?>notes.php"><i class="fas fa-sticky-note"></i> Poznámky</a></li> -->
                        <li><a href="<?= $base ?>logout.php"><i class="fas fa-sign-out-alt"></i> Odhlásiť sa</a></li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                <h1>Poskytovatelia</h1>
                <p>Tu môžete spravovat poskytovateľov finančných služieb, ktoré používate pre svoje investície.</p> 

                <button type="button" id="btnAddNewProvider" class="button small_button" name="new_provider" title="add new provider"><i class="fa fa-plus"></i></button>
                

                <div class="providers">
                    <?php
                        // Example: Fetch providers from database and display them
                        
                        $get_providers = "SELECT * FROM providers ORDER BY provider_name ASC";
                        $result = mysqli_query($link, $get_providers) or die("MySQL ERROR: " . mysqli_error($link));
                        while ($row = mysqli_fetch_array($result)) {
                        
                        $provider_id = $row['id'];
                        $provider_name = $row['provider_name'];
                        $provider_logo = $row['provider_logo'];
                        $provider_description = $row['provider_description'];


                        echo '<div class="provider_card" data-id="'.$provider_id.'" data-name="'.$provider_name.'">'.$provider_name.'</div>';
                        }
                
                        ?>

                </div><!--providers-->
            <div class="provider_details">
            </div><!--provider_details-->    

            </div><!--content-->  
        </div><!--container-->      
    
</body>
</html>