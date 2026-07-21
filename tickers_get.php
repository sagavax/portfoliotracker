<?php

    include('includes/dbconnect.php');
    include('includes/functions.php');

    //combine search text and first-letter filter, either or both may be present
    $conditions = [];

    if (isset($_GET['ticker']) && $_GET['ticker'] !== '') {
        $search = mysqli_real_escape_string($link, $_GET['ticker']);
        $conditions[] = "ticker LIKE '%$search%'";
    }

    if (isset($_GET['letter']) && $_GET['letter'] !== '') {
        $letter = mysqli_real_escape_string($link, $_GET['letter']);
        $conditions[] = "ticker LIKE '$letter%'";
    }

    $where = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";
    $limit = $conditions ? 100 : 50;

    $get_ticker = "SELECT * FROM tickers $where ORDER BY ticker ASC LIMIT $limit";

    $result = mysqli_query($link, $get_ticker) or die("MySQL ERROR: " . mysqli_error($link));

    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>".$row['ticker']."</td>";
        echo "<td>".$row['company_name']."</td>";
        echo "<td>".$row['short_name']."</td>";
        echo "<td>".$row['industry']."</td>";
        echo "<td class='description' title='" . htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td><a href='".$row['website']."' target='_blank'>".$row['website']."</a></td>";
        echo "</tr>";
    }