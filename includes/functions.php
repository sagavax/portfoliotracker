<?php
    global $link;

    function GetTransactionNotes($transaction_id) {
        global $link;

        $get_transaction = "SELECT * FROM transaction_notes WHERE transaction_id=$transaction_id";
        $result = mysqli_query($link, $get_transaction)
            or die("MySQLi ERROR: " . mysqli_error($link));

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

    } 

    function GetTransactionDetails($transaction_id) {
    global $link;

    $get_transaction = "SELECT * FROM transactions WHERE id=$transaction_id";
    $result = mysqli_query($link, $get_transaction)
        or die("MySQLi ERROR: " . mysqli_error($link));

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }     
}

    function GetCountTransactionNotes($transaction_id){
        global $link;
        $get_notes = "SELECT * from transaction_notes WHERE transaction_id=$transaction_id";
        $result = mysqli_query($link, $get_notes) or die("MySQLi ERROR: ".mysqli_error($link));
        //number of rows
        $rowcount = mysqli_num_rows($result);
        return $rowcount;
    }

    function GetCountNotes($transaction_id) {
        global $link;
        $get_notes = "SELECT Count(*) as note_count from transaction_notes WHERE transaction_id=$transaction_id";
        $result = mysqli_query($link, $get_notes) or die("MySQLi ERROR: ".mysqli_error($link));
        $row = mysqli_fetch_array($result);
        return $row['note_count'];
    }

    function GetCountPosiotions(){
        global $link;
        $get_positions = "SELECT Count(*) as position_count from transactions";
        $result = mysqli_query($link, $get_positions) or die("MySQLi ERROR: ".mysqli_error($link));
        $row = mysqli_fetch_array($result);
        return $row['position_count'];
    }


    function GetPortfolioValue(){
        global $link;
        $get_positions = "SELECT SUM(qty*price) as portfolio_value from transactions";
        $result = mysqli_query($link, $get_positions) or die("MySQLi ERROR: ".mysqli_error($link));
        $row = mysqli_fetch_array($result);
        return floor($row['portfolio_value']);
    }


    function GetAllTransationsAsset(){
    
        global $link;


    header('Content-Type: application/json');

    $response_data = [];
    
    
    $get_asset = "SELECT DISTINCT symbol FROM transactions";
    $result = mysqli_query($link, $get_asset) or die("MySQL ERROR: " . mysqli_error($link));

    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            // Add each symbol to the PHP array
            $response_data[] = $row['symbol'];
        }
        // Free result set
        mysqli_free_result($result);
    } else {
        // Handle error (optional, depending on your error handling strategy)
        // In a real application, you'd log this and return a 500 Internal Server Error
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . mysqli_error($link)]);
        exit;
    }

    // Encode the entire array into a single, valid JSON string and echo it
    echo json_encode($response_data);
    }


    function assetCharList(){
          //char list
          foreach (range('A', 'Z') as $char) {
                    echo "<button type='button' name='letterButton' class='button small_button'>$char</button>";
                }
    }


    function assetSymbolList(){
        //symbol list
        global $link;
        $get_tickers = "SELECT DISTINCT ticker FROM tickers WHERE LEFT(ticker, 1) = 'A' ORDER BY ticker ASC LIMIT 100";
        $result = mysqli_query($link, $get_tickers) or die("MySQL ERROR: " . mysqli_error($link));
        while ($row = mysqli_fetch_array($result)) {
            $symbol = $row['ticker'];
            echo '<button name="symbol" class="secondary">'.$symbol.'</button>';
        }
    }


    function assetSymbolListPagination(){

    }


    function GetActiveTickers(){
        global $link;
        $get_tickers = "SELECT symbol FROM transactions WHERE is_closed = 0 GROUP BY symbol ORDER BY symbol ASC";
        $result = mysqli_query($link, $get_tickers) or die("MySQL ERROR: " . mysqli_error($link));
        while ($row = mysqli_fetch_array($result)) {
            $ticker = $row['symbol'];
            echo "<button type='button' class='filter_button' data-filter='$ticker'>$ticker</button>";
        }
    }


    function GetBugbusterApiHost() {
        $currAddress = $_SERVER['SERVER_NAME'] ?? '';
        return ($currAddress === 'localhost') ? "http://localhost/bugbuster" : "https://bugbuster.tmisura.sk";
    }

    function GetBugbusterApiJson($query) {
        $apiUrl = GetBugbusterApiHost() . '/api/api.php?' . $query;
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPGET => true,
        ]);
        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            return null;
        }

        $data = json_decode($response, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $data : null;
    }

    function GetCountIdeas() {
        $data = GetBugbusterApiJson('endpoint=ideas&app_name=portfoliotracker');
        return is_array($data) ? count($data) : 0;
    }

    function GetCountBugs() {
        $data = GetBugbusterApiJson('endpoint=bugs&app_name=portfoliotracker');
        return is_array($data) ? count($data) : 0;
    }

    function GetCountIdeaComments($idea_id) {
        $data = GetBugbusterApiJson('endpoint=idea_comments_count&idea_id=' . (int)$idea_id);
        return $data[0]['count'] ?? 0;
    }

    function GetCountLogRecords() {
        // No app_log data source is wired up yet (no bugbuster API endpoint,
        // app_event_tracker.js doesn't exist). Stubbed at 0 until that exists.
        return 0;
    }

    function GetLatestItems($days = 5) {
        global $link;

        $items = [];

        $get_transactions = "SELECT symbol, provider, created_at FROM transactions WHERE created_at >= DATE_SUB(NOW(), INTERVAL $days DAY) ORDER BY created_at DESC";
        $result = mysqli_query($link, $get_transactions) or die("MySQLi ERROR: " . mysqli_error($link));
        while ($row = mysqli_fetch_assoc($result)) {
            $label = $row['symbol'] ? 'Transakcia: ' . $row['symbol'] : 'Nová transakcia';
            if ($row['provider']) {
                $label .= ' (' . $row['provider'] . ')';
            }
            $items[] = ['icon' => 'fa-chart-line', 'label' => $label, 'timestamp' => $row['created_at'], 'link' => 'portfolio/index.php'];
        }

        $get_providers = "SELECT provider_name, created_date FROM providers WHERE created_date >= DATE_SUB(NOW(), INTERVAL $days DAY) ORDER BY created_date DESC";
        $result = mysqli_query($link, $get_providers) or die("MySQLi ERROR: " . mysqli_error($link));
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = ['icon' => 'fa-building', 'label' => 'Poskytovateľ: ' . $row['provider_name'], 'timestamp' => $row['created_date'], 'link' => 'providers.php'];
        }

        $get_notes = "SELECT tn.note_text, tn.created_at, t.symbol FROM transaction_notes tn LEFT JOIN transactions t ON t.id = tn.transaction_id WHERE tn.created_at >= DATE_SUB(NOW(), INTERVAL $days DAY) ORDER BY tn.created_at DESC";
        $result = mysqli_query($link, $get_notes) or die("MySQLi ERROR: " . mysqli_error($link));
        while ($row = mysqli_fetch_assoc($result)) {
            $preview = mb_strimwidth($row['note_text'], 0, 60, '...');
            $label = 'Poznámka' . ($row['symbol'] ? ' (' . $row['symbol'] . ')' : '') . ': ' . $preview;
            $items[] = ['icon' => 'fa-sticky-note', 'label' => $label, 'timestamp' => $row['created_at'], 'link' => 'notes.php'];
        }

        usort($items, function ($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        if (empty($items)) {
            echo "<p>Žiadne nové položky za posledných $days dní.</p>";
            return;
        }

        foreach ($items as $item) {
            echo "<a href='" . $item['link'] . "' class='latest_item'>";
            echo "<i class='fa " . $item['icon'] . "'></i>";
            echo "<span class='latest_item_label'>" . htmlspecialchars($item['label']) . "</span>";
            echo "<small class='latest_item_time'>" . date('d.m.Y H:i', strtotime($item['timestamp'])) . "</small>";
            echo "</a>";
        }
    }

/**
 * Retrieves all influencers from the database and echoes out a button for each one.
 * 
 * @return void
 */
    function GetAllInfluencers(){
        global $link;
        $get_influencers = "SELECT * FROM influencers";
        $result = mysqli_query($link, $get_influencers) or die("MySQLi ERROR: ".mysqli_error($link));
        while ($row = mysqli_fetch_array($result)) {
               $influencer_id = $row['influencer_id'];
               $influencer_name = $row['influencer_name'];
               $influencer_channel_link = $row['influencer_channel_link'];
               $influencer_image = $row['influencer_image'];
               echo '<button name="influencer" class="secondary" id="'.$influencer_id.'">'.$influencer_name.'</button>'; 
        }       

    }

    function parseProviderLogo($logoData) {
        if (empty($logoData)) {
            return '';
        }

        // 1. If it starts with data:image/, it's already a Data URI
        if (strpos($logoData, 'data:image/') === 0) {
            return $logoData;
        }

        // 2. If it's a URL or standard path (contains slashes or file extension dots)
        if (filter_var($logoData, FILTER_VALIDATE_URL) || strpos($logoData, '/') !== false || strpos($logoData, '.') !== false) {
            return $logoData;
        }

        // 3. Try to decode as raw base64 string
        $decoded = base64_decode($logoData, true);
        if ($decoded !== false) {
            $mimeType = 'image/png'; // fallback
            if (class_exists('finfo')) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $detectedMime = @$finfo->buffer($decoded);
                if ($detectedMime && strpos($detectedMime, 'image/') === 0) {
                    $mimeType = $detectedMime;
                }
            } else {
                $signatures = [
                    "\x89PNG\r\n\x1a\n" => 'image/png',
                    'GIF87a' => 'image/gif',
                    'GIF89a' => 'image/gif',
                    "\xff\xd8\xff" => 'image/jpeg',
                    'RIFF' => 'image/webp',
                ];
                foreach ($signatures as $sig => $mime) {
                    if (strpos($decoded, $sig) === 0) {
                        $mimeType = $mime;
                        break;
                    }
                }
            }
            return 'data:' . $mimeType . ';base64,' . $logoData;
        }

        // 4. If nothing else works, check if the raw bytes are an image (binary blob)
        if (class_exists('finfo')) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = @$finfo->buffer($logoData);
            if ($mimeType && strpos($mimeType, 'image/') === 0) {
                return 'data:' . $mimeType . ';base64,' . base64_encode($logoData);
            }
        }

        return $logoData;
    }