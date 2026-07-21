<?php
  include('includes/dbconnect.php');
  include('includes/functions.php');

  session_start();

  if(!isset($_SESSION['login'])) {
    header('Location: ../login.php');
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
  <body>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portforlio Tracker</title>
        <link rel="stylesheet" href="css/style.css?<?php echo time() ?>" />
         <link rel="stylesheet" href="css/tickers.css?<?php echo time() ?>" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link rel="icon" type="image/svg+xml" sizes="32x32" href="../investment.png">
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
        <script src="js/clock.js?<?php echo time() ?>" defer></script>
        <script src="js/tickers.js?<?php echo time() ?>" defer></script>
        <script src="js/message.js?<?php echo time() ?>" defer></script>
        <!-- <script src="js/worldclock.js?<?php echo time() ?>"></script> -->
    </head>

        <header>
          <a href="."><img src="portfolio-ticker-logo.svg" alt="Portfolio Ticker"></a><div class="clockWrapper"><button type ="button" class="secondary" name="worldclock"  id="worldclock">World Clock</button><div id="clock">--:--:--</div></div>
        </header>
        <div class="container">
           <!--  <div class="debug" style="display: flex; color:white"><?php echo $_SERVER['HTTP_HOST']; ?></div> -->
            <div class="sidebar">
                <nav>
                    <ul>
                        <?php
                            $base = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') ? 'http://localhost/portfoliotracker/' : 'https://portfoliotracker.tmisura.sk/';

                        ?>

                        <li><a href="<?= $base ?>index.php"><i class="fas fa-home"></i> Domov</a></li>
                        <li><a href="<?= $base ?>/portfolio/index.php"><i class="fas fa-chart-line"></i> Portfólio</a></li>
                        <li><a href="<?= $base ?>providers.php"><i class="fas fa-building"></i> Poskytovatelia</a></li>
                        <li><a href="<?= $base ?>tickers.php"><i class="fas fa-tags"></i> Tickers</a></li>
                       <!--  <li><a href="<?= $base ?>influencers.php"><i class="fas fa-users"></i> Influencers</a></li>
                        <li><a href="<?= $base ?>news.php"><i class="fas fa-newspaper"></i> Novinky</a></li>
                        <li><a href="<?= $base ?>settings.php"><i class="fas fa-cogs"></i> Nastavenia</a></li>
                        <li><a href="<?= $base ?>notes.php"><i class="fas fa-sticky-note"></i> Poznámky</a></li> -->
                        <li><a href="<?= $base ?>logout.php"><i class="fas fa-sign-out-alt"></i> Odhlásiť sa</a></li>

                    </ul>
                </nav>
            </div>
            <div class="content">
                <h1>Vitajte v Portfolio Tracker</h1>
                <p>Zoznam jednotlivych tickersov.</p>
                <div class="search-container">
                    <input type="text" id="search_in_ticker" placeholder="Hľadať ticker...">
                    <button type="button" class="primary" data-ticker="add_ticker">Pridať nový ticker</button>
                </div>
                
                <div class="filter_ticker_alphabet">
                    <?php
                        foreach (range('A', 'Z') as $letter) {
                            echo "<button type='button' class='filter_button' data-letter='$letter'>$letter</button>";
                        }
                    ?>
                </div>

                <div class="tickers_wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Ticker</th>
                                <th>Company Name</th>
                                <th>Kratke meno</th>
                                <th>Priemysel</th>
                                <th>Popopis</th>
                                <th>websttranka</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    $sql = "SELECT * FROM tickers ORDER BY ticker ASC LIMIT 25";
                                    $result = mysqli_query($link, $sql) or die("MySQLi ERROR: ".mysqli_error($link));
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                        echo "<td>".$row['ticker']."</td>";
                                        echo "<td>".$row['company_name']."</td>";
                                        echo "<td>".$row['short_name']."</td>";
                                        echo "<td>".$row['industry']."</td>";
                                        echo "<td class='description' title='".htmlspecialchars($row['description'])."'>".$row['description']."</td>";
                                        echo "<td><a href='".$row['website']."' target='_blank'>".$row['website']."</a></td>";
                                        echo "</tr>";
                                    } 
                            ?>
                        </tbody>
                    </table>
                </div><!-- tickers_wrapper -->
            </div><!-- content -->
        </div><!-- container -->

        <dialog id="modalTicker">
            <div class="modal-container">
                <h3>Pridať nový ticker</h3>
                <div id="addTickerContent">
                    <input type="text" id="new_ticker" placeholder="Zadajte nový ticker...">
                    <input type="text" id="new_short_name" placeholder="Zadajte kratke meno...">
                </div>
                <div class="modal-actions">
                    <button id="cancelTicker" class="secondary">Zrušiť</button>
                    <button id="saveTicker" class="secondary">Uložiť</button>
                </div>
            </div>
        </dialog>
</body>
</html>