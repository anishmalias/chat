<?php
session_start();
if(isset($_SESSION['name'])){
    $text = $_POST['text'];
     
    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgln'>(".date("g:i A").") <span class='user'>".$_SESSION['name']."</span>: ".stripslashes(htmlspecialchars($text))."<br></div>");
    fclose($fp);
}
?>