<!DOCTYPE html>			
<?php
	// Verbindung zu DB (feedbdb) aufbauen
session_start();
include("PHP/connect.php");
include("PHP/Mysql.class.php");
include("PHP/MysqlStatement.class.php");
?>

<html>
	<!-- Head -->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="stylesheet" type="text/css" href="CSS/style.css" media="only screen and (max-width:480px)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
		<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
		
		<title>Feedback-App</title>
	</head>
	<!-- /Head -->

	<!-- Body -->
	<body>
		<?php
			// Passwort und Username aus DB (users) auslesen
	if (isset($_POST['name']) && isset($_POST['pw'])) {
		$Mysql = new Mysql();
		$sql = "SELECT * FROM users WHERE name=:0 AND pw=:1";
		$MysqlStatement_select = $Mysql->getMysqlStatement($sql);
		$MysqlStatement_select->execute($_POST['name'], $_POST['pw']);
		$pw = $_POST['pw'];
		$user = $_POST['name'];
	}
	?>

		<!-- Seite 1 / Startpage: Login -->
		<div data-role="page" id="pageone">
			<div data-role="header">
				<h1>Feedback-Login</h1>
			</div>

			<!-- Main-Content -->
			<div data-role="main" class="ui-content">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<!-- Username -->
					<input type="text" name="name" placeholder="Username" class="padding-textbox"><br>
					<!-- Passwort -->
					<input type="password" name="pw" placeholder="Passwort" class="padding-textbox"><br>
					<!-- Login-Button -->
					<div class="ui-nodisc-icon ui-noback-icon" id="login-button">
						<a href="javascript:;" onclick="parentNode.parentNode.submit();" data-role="button" data-inline="true">Login</a>
					</div>
				</form>
			</div>

			<?php
				// Passwort und Username aus DB (users) vergleichen & wenn Richtig: Weiterleiten zur Seite 2 (Upload, Tasks, Meine Tasks)
		if (isset($MysqlStatement_select) && $MysqlStatement_select->num_rows >= 1 || (isset($_SESSION['user']) && ($_SESSION['user'] == $user + password_hash($pw, PASSWORD_DEFAULT)))) {
			$_SESSION['user'] = $user + password_hash($pw, PASSWORD_DEFAULT);
			$data = $MysqlStatement_select->fetchArray();
			$_SESSION['user_id'] = $data["ID_user"];
			$_SESSION['user_name'] = $user;
			header("Location: index.php");
			exit();
		}
		?>
			<!-- Footer -->
			<div data-role="footer">
        		<h3>© Sara Unteregger & Simon Wünscher</h3>
			</div>
			<!-- /Footer -->
		</div>
	</body>
	<!-- /Body -->
</html>

