<?php
session_start ();
function loginForm() {
	echo '
    <div id="loginform">
    <form action="index.php" method="post">
        <p>Please enter your name to continue:</p>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" />
        <input type="submit" name="enter" id="enter" value="Enter" />
    </form>
    </div>
    ';
}

if (isset ( $_POST ['enter'] )) {
	if ($_POST ['name'] != "") {
		$_SESSION ['name'] = stripslashes ( htmlspecialchars ( $_POST ['name'] ) );
		$fp = fopen ( "log.html", 'a' );
		fwrite ( $fp, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has joined the chat session.</i><br></div>" );
		fclose ( $fp );
	} else {
		echo '<span class="error">Please type in a name</span>';
	}
}

if (isset ( $_GET ['logout'] )) {
	
	// Simple exit message
	$fp = fopen ( "log.html", 'a' );
	fwrite ( $fp, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has left the chat session.</i><br></div>" );
	fclose ( $fp );
	
	session_destroy ();
	header ( "Location: index.php" ); // Redirect the user
}

?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Chat</title>
	<meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
	<script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>
<body>
     <?php
        if (! isset ( $_SESSION ['name'] )) {
            loginForm ();
        } else {
            ?>
    <section class="base-section">
        <header class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-10">
                        <h1>ChatApp</h1>    
                    </div>
                    <div class="col-sm-2 clearfix">
                        <div class="logout">
                            <a id="exit" href="#"></a>
                        </div>
                    </div>
                </div>
                
            </div>
        </header>
        <section class="wrap-section">
            <div class="online-list">
                <ul>
                  <li></li>
                </ul>
            </div>
            <div class="chat-outside">
               
                <div id="wrapper">
                    <div id="menu">
                        <div class="welcome">
                            <p>
                                Welcome, <span><?php echo $_SESSION['name']; ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="chtBx">
                        <div id="chatbox"><?php
                        if (file_exists ( "log.html" ) && filesize ( "log.html" ) > 0) {
                            $handle = fopen ( "log.html", "r" );
                            $contents = fread ( $handle, filesize ( "log.html" ) );
                            fclose ( $handle );

                            echo $contents;
                        }
                        ?></div>
                    </div>
                    <div class="creat-msg">
                        <form name="message" action="">
                            <input class="form-control" name="usermsg" type="text" id="usermsg" size="63" />
                            <div class="msg-enter">
                                <input class="btn btn-success" name="submitmsg" type="submit" id="submitmsg" value="" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </section>
	

	
<?php
	}
	?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
	<script type="text/javascript">
    // jQuery Document
    $(document).ready(function(){
         //If user wants to end session
        $("#exit").click(function(){
            var exit = confirm("Are you sure you want to end the session?");
            if(exit==true){window.location = 'index.php?logout=true';}		
        });


        //If user submits the form
        $("#submitmsg").click(function(){
                var clientmsg = $("#usermsg").val();
                $.post("post.php", {text: clientmsg});				
                $("#usermsg").attr("value", "");
                loadLog;
            return false;
        });

        function loadLog(){		
            var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
            $.ajax({
                url: "log.html",
                cache: false,
                success: function(html){		
                    $("#chatbox").html(html); //Insert chat log into the #chatbox div	
                    console.log(html);
                    //Auto-scroll			
                    var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
                    if(newscrollHeight > oldscrollHeight){
                        $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                    }				
                },
            });
        }
        
        setInterval (loadLog, 1000); 
        
    });

    //jQuery Document

    </script>
</body>
</html>