<?php
session_start();

    // Überprüfe: User ist bereits eingeloggt || auf Login-Seite springen
if (isset($_SESSION['user'])) {
} else {
    header("Location: login.php");
}
    // Verbindung zu DB (feedbdb) aufbauen
include("PHP/connect.php");
include("PHP/Mysql.class.php");
include("PHP/MysqlStatement.class.php");

    // Verschlüsselung / hash
$userKey = md5($_SESSION['user_id']);

if (isset($_POST['ID_aufgabe'])) {
    $p_audio_temp = $_FILES['data']['tmp_name'];

        // Audio-Datei temporär mit Datum, Uhrzeit als Name abspeichern
    $file_name = date('m-d-Y-H-i-s') . '_' . $userKey . '.mp3';
        // Audio-Datei in Ordner "sounds" verschieben
    echo "Moved from " . $p_audio_temp . " to " . $file_name;
    $path = "sound/" . $file_name;
    move_uploaded_file($p_audio_temp, $path);

        // Eintrag mit f_inhalt (Audiofile), f_aktiv, ID_aufgabe in der DB (feedback) erstellen
    $Mysql = new Mysql();
    $sql = "INSERT INTO `feedback`(`f_inhalt`, `f_aktiv`, `ID_aufgabe`, `ID_creator`) VALUES (:0,:1,:2,:3);";
    $MysqlStatement_select = $Mysql->getMysqlStatement($sql);
    $MysqlStatement_select->execute($path, "1", $_POST["ID_aufgabe"], $_POST["ID_creator"]);
} else {
    if (0 < $_FILES['file']['error']) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    } else {
        $file_name = $_FILES['file']['name'];
        $file_extension = substr($file_name, strripos($file_name, '.'));
        $file_name = substr($file_name, 0, strripos($file_name, '.'));
        $image_path = 'uploads/' . $file_name . '_' . $userKey . $file_extension;

        $index = 1;
            // Wenn ein Bild zum Upload ausgewählt wurde
        while (file_exists($image_path)) {
            $image_path = 'uploads/' . $file_name . $index . '_' . $userKey . $file_extension;
            $index = $index + 1;
        }
        move_uploaded_file($_FILES['file']['tmp_name'], $image_path);

        echo $_POST["name"] . $_POST["description"] . $image_path . "1" . $_SESSION["user_id"];
        
            // Eintrag mit a_name (Titel), a_imge (Bild), a_aktiv, ID_user in der DB (aufgaben) erstellen
        $Mysql = new Mysql();
        $sql = "INSERT INTO `aufgaben`(`a_name`, `a_inhalt`, `a_image`, `a_aktiv`, `ID_user`) VALUES (:0,:1,:2,:3,:4);";
        $MysqlStatement_select = $Mysql->getMysqlStatement($sql);
        $MysqlStatement_select->execute($_POST["name"], $_POST["description"], $image_path, "1", $_SESSION["user_id"]);
    }
}

?>