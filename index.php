<!DOCTYPE html>

<?php
include("PHP/connect.php");
include("PHP/Mysql.class.php");
include("PHP/MysqlStatement.class.php");
?>

<html>
<head>
	<!-- Include meta tag to ensure proper rendering and touch zooming -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Include jQuery Mobile stylesheets -->
	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
	<!-- Include the jQuery library -->
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<!-- Include the jQuery Mobile library -->
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	<title>FeedbackApp</title>
</head>
<body>

	<?php

		$Mysql = new Mysql();


		$sql = "SELECT * FROM users WHERE name=:0 AND pw=:1";
		$sqlAufgaben = "SELECT * FROM aufgaben WHERE a_aktiv = true";
		$MysqlStatement_select = $Mysql->getMysqlStatement($sql);
		$MysqlStatement_select->execute($_POST[name], $_POST[pw]); 
		$pw = $_POST['pw'];
		$user = $_POST['name'];   
	?>

	<div data-role="page" id="pageone">
		<div data-role="header">
			<h1>Login</h1>
		</div>

		<div data-role="main" class="ui-content">
		<div data-role="tabs" id="tabs">
				<div data-role="navbar">
					<ul>
						<li><a href="#one" class="ui-btn-active" data-ajax="false">Upload</a></li>
						<li><a href="#two" data-ajax="false">Tasks</a></li>
						<li><a href="#three" data-ajax="false">History</a></li>
					</ul>
				</div>
				<div id="one" class="tab-content ui-content" date-role="content">
					<div class="ui-nodisc-icon ui-noback-icon">
						
						<a ref="#pagetwo" data-role="button" data-icon="camera" data-iconpos="notext" data-inline="true"></a>

						<div><a href="#pagetwo" data-role="button" data-icon="arrow-u" data-iconpos="left" data-inline="true" id="upload-button">Upload</a>
						</div>
					</div>
				</div>
				<div id="two" class="tab-content ui-content" date-role="content">
					<ul data-role="listview" data-inset="true">
						
				</div>
				
				<div id="three" class="tab-content ui-content" date-role="content">
					<ul data-role="listview" data-inset="true">
						<li>
							<a href="#popupPeter" data-rel="popup" data-position-to="window" data-transition="pop">
								<div>
									<div>
										<img src="img/p3.jpg">
									</div>
									<p><strong>Peter</strong><br></p>
									<p>Which program for illustrating do<br>you recommend?</p>
								</div>
							</a>
							<div data-role="popup" id="popupPeter" data-overlay-theme="b" data-theme="a" class="ui-corner-all">
								<h1>Peter</h1>
								<a ref="#pagetwo" data-role="button" data-icon="comment" data-iconpos="notext" data-inline="true"></a>
					            <img src="img/p3.jpg">
								<p>Which program for illustrating do<br>you recommend?</p>
							</div>
						</li>
						<li>
							<a href="#popupSara" data-rel="popup" data-position-to="window" data-transition="pop">
								<div>
									<div>
										<img src="img/p1.jpg">
									</div>
									<p><strong>Sara</strong><br></p>
									<p>New Moodboard for my company!<br> What do you think?</p>
								</div>
							</a>
							<div data-role="popup" id="popupSara" data-overlay-theme="b" data-theme="a" class="ui-corner-all">
								<h1>Sara</h1>
								<a ref="#pagetwo" data-role="button" data-icon="comment" data-iconpos="notext" data-inline="true"></a>
					            <img src="img/p1.jpg">
								<p>New Moodboard for my company!<br> What do you think?</p>
							</div>
						</li>
						<li>
							<a href="#popupSusi" data-rel="popup" data-position-to="window" data-transition="pop">
								<div>
									<div>
										<img src="img/p2.jpg">
									</div>
									<p><strong>Susi</strong><br></p>
									<p>Don't know what to choose..<br> Please help!</p>
								</div>
							</a>
							<div data-role="popup" id="popupSusi" data-overlay-theme="b" data-theme="a" class="ui-corner-all">
								<h1>Susi</h1>
								<a ref="#pagetwo" data-role="button" data-icon="comment" data-iconpos="notext" data-inline="true"></a>
					            <img src="img/p2.jpg">
								<p>Don't know what to choose..<br> Please help!</p>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
				Name: <input type="text" name="name"><br>
				PW: <input type="text" name="pw"><br>
				<input type="submit" value="Login">
			</form>
		</div>
		<?php
			if($MysqlStatement_select->num_rows >= 1)
			{
					header("Location: http://localhost/feedback/feedbackApp/main.php");
			exit();
			}
		?>
	</div>


</body>
</html>

