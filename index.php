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
</head>
<body>

	<?php

$Mysql = new Mysql();


$sql = "SELECT * FROM users WHERE name=:0 AND pw=:1";
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
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
			Name: <input type="text" name="name"><br>
			PW: <input type="text" name="pw"><br>
			<input type="submit" value="Login">
		</form>
	</div>
	<?php
		if($MysqlStatement_select->num_rows >= 1)
		{
		echo "Login erfolgreich. Weiter zu <a href='geheim.html'>internen Bereich</a>" ;
		header("Location: http://localhost/feedback/feedbackApp/geheim.html");
		exit();
		}
	?>
</div>


</body>
</html>

