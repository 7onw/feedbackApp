<!DOCTYPE html>


<?php
include("PHP/connect.php");
include("PHP/Mysql.class.php");
include("PHP/MysqlStatement.class.php");
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="CSS/style.css" media="only screen and (max-width:480px)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script language="javascript" type="text/javascript" src="JS/function.js"></script>
    <title>Web App Übung 1 Seite 1</title>
</head>
<body>
<body>

    <?php

$Mysql = new Mysql();


$sql = "SELECT * FROM users WHERE name=:0 AND pw=:1";
$sqlAufgaben = "SELECT * FROM aufgaben";
$MysqlStatement_select = $Mysql->getMysqlStatement($sql);
$MysqlStatement_select2 = $Mysql->getMysqlStatement($sqlAufgaben);
$MysqlStatement_select->execute($_POST[name], $_POST[pw]); 
$MysqlStatement_select2->execute($_POST[name], $_POST[pw]); 
?>

<div data-role="page" id="pageone">
    <div data-role="header">
        <h1>Aufgaben</h1>

    </div>


    <?php echo "<br /> SQL Statement: <br/>" . $MysqlStatement_select->sql; ?>

    <?php echo "<br /> NUM: " . $MysqlStatement_select->num_rows; ?>
    <?php echo "<br /><br />"  ?>


    <?php
   
        while ($data = $MysqlStatement_select2->fetchArray()) {
        echo"<a href='givefb.php'>";

        echo $data['a_name'];
        echo"</a>";
        echo "<br/>";
    }

    ?>


<div id="content">
    <button onClick="printSomeThing();" class="buttonBlack">neue Aufgabe</button>
    </div>

</body>
</html>