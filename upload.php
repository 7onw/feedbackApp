<?php
session_start();
if ( isset( $_SESSION['user'] ) ) {
} else {
    header("Location: login.php");
}

include("PHP/connect.php");
include("PHP/Mysql.class.php");
include("PHP/MysqlStatement.class.php");

if ( 0 < $_FILES['file']['error'] ) {
    echo 'Error: ' . $_FILES['file']['error'] . '<br>';
}
else {
    $file_name = $_FILES['file']['name'];
    $file_extension = substr($file_name, strripos($file_name, '.'));
    $file_name = substr($file_name, 0, strripos($file_name, '.'));
    $image_path = 'uploads/' . $file_name . $file_extension;

    $index = 1;
    while(file_exists($image_path))
    {
        $image_path = 'uploads/' . $file_name .$index. $file_extension;
        $index = $index + 1;
    }
    move_uploaded_file($_FILES['file']['tmp_name'], $image_path);

    echo $_POST["name"] . $_POST["description"] . $image_path . "1" . $_SESSION["user_id"];

    // TODO create SQL database entry
    $Mysql = new Mysql();
    $sql = "INSERT INTO `aufgaben`(`a_name`, `a_inhalt`, `a_image`, `a_aktiv`, `ID_user`) VALUES (:0,:1,:2,:3,:4);";
    $MysqlStatement_select = $Mysql->getMysqlStatement($sql);
    $MysqlStatement_select->execute($_POST["name"], $_POST["description"], $image_path, "1", $_SESSION["user_id"]);
}
?>