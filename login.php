<!DOCTYPE html>
<?php
	session_start();
	include("PHP/connect.php");
	include("PHP/Mysql.class.php");
	include("PHP/MysqlStatement.class.php");
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="stylesheet" type="text/css" href="CSS/style.css" media="only screen and (max-width:480px)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<!--script language="javascript" type="text/javascript" src="JS/function.js"></script-->
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
		<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
		<title>Feedback-App</title>
	</head>

	<body>
		<?php
			if(isset($_POST['name']) && isset($_POST['pw'])) {
				$Mysql = new Mysql();
				$sql = "SELECT * FROM users WHERE name=:0 AND pw=:1";
				$MysqlStatement_select = $Mysql->getMysqlStatement($sql);
				$MysqlStatement_select->execute($_POST['name'], $_POST['pw']); 
				$pw = $_POST['pw'];
				$user = $_POST['name'];   
			}
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
				if(isset($MysqlStatement_select) && $MysqlStatement_select->num_rows >= 1 || (isset( $_SESSION['user'] ) && ($_SESSION['user'] == $user + password_hash($pw, PASSWORD_DEFAULT))))
				{
					$_SESSION['user'] = $user + password_hash($pw, PASSWORD_DEFAULT);
					header("Location: index.php");
				exit();
				}
			?>
		</div>
        <div data-role="footer">
            <h3>© Sara Unteregger & Simon Wünscher</h3>
        </div>
	</body>
</html>

