<?php

    include('../includes/dbconnect.php');
    include('../includes/functions.php');

    //if is ticker set
    if (isset($_GET['ticker'])) {
    $search = mysqli_real_escape_string($link, $_GET['ticker']);
    $get_ticker = "SELECT ticker, company_name FROM tickers WHERE ticker LIKE '%$search%' ORDER BY ticker ASC LIMIT 100";
    } elseif (isset($_GET['letter'])) {
        $letter = mysqli_real_escape_string($link, $_GET['letter']);
        $get_ticker = "SELECT ticker, company_name FROM tickers WHERE ticker LIKE '$letter%' ORDER BY ticker ASC LIMIT 100";
    } else {
        $get_ticker = "SELECT ticker, company_name FROM tickers ORDER BY ticker ASC LIMIT 50";
    }

    $result = mysqli_query($link, $get_ticker) or die("MySQL ERROR: " . mysqli_error($link));
    
    while ($row = mysqli_fetch_array($result)) {
        //output json
        $ticker = $row['ticker'];
        $company_name = $row['company_name'];
        echo '<button name="symbol" class="secondary" name="ticker" data-ticker="'.$ticker.'">'.$ticker.' - '.$company_name.'</button>';
    }