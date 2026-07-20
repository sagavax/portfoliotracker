<?php

        include_once("../includes/dbconnect.php");
        include_once("../includes/functions.php");
?>
        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfoliotracker -what's new</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,300italic,700,700italic,400italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css?<?php echo time(); ?>">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <script src="../js/news.js" defer></script>
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
</head>
<body>

<?php include "../includes/header.php" ?>
    <div class="main_wrap">
        <div class="tab_menu">
            <?php include("../includes/vanila_menu.php"); ?>
        </div><!--tab_menu-->
    <div class="content">
        <div class="list">
            <div class="add_news">
                <button type="button" class="button small_button"><i class="fa fa-plus"></i> Add News</button>
            </div>    
            <div class="news_view">
                <?php 
                    $get_news = "SELECT * from news ORDER by news_id DESC";
                    $result_news = mysqli_query($link, $get_news) or die("MySQLi ERROR: ".mysqli_error($link));
                    while($row_news = mysqli_fetch_array($result_news)){
                        $news_id = $row_news['news_id'];
                        $news_text = $row_news['news_text'];
                        $news_date = $row_news['news_date'];
                        $news_author = $row_news['news_author'];
                        echo "<div class='single_news' id='news_$news_id'>
                                <div class='news_header'>
                                    <div class='news_date'>".$news_date."</div>
                                    <div class='news_actions'>
                                        <button type='button' class='button small_button edit_news' data-id='$news_id' title='Edit news'><i class='fa fa-edit'></i></button>
                                        <button type='button' class='button small_button delete_news' data-id='$news_id' title='Delete news'><i class='fa fa-trash'></i></button>
                                    </div>
                                </div>    
                                <div class='news_content'>
                                    $news_text
                                </div>
                                <div class='news_footer'>
                                    <div class='news_author'>by: $news_author</div>
                                </div>    
                             </div>";
                    }
                ?>
        </div><!--list-->
    </div><!--content--> 
    </div><!--main_wrap-->   
    <dialog class="modal_add_news">    
        <div class="add_news_inner_layer">
            <textarea name="news_text" placeholder="text of the news ...."></textarea>
            <div class="action">
                <button type="button" class="button small_button" name="return_to_vanilla">Back</button>
                <button type="button" class="button small_button" name="add_news">Add news</button>
            </div>
        </div>
    </dialog>
</body>
</html>

