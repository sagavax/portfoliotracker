<?php
    global $link;

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