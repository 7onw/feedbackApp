<!DOCTYPE html>
    <?php
        // Überprüfe: User ist bereits eingeloggt || auf Login-Seite springen
    session_start();
    if (isset($_SESSION['user'])) {
    } else {
        header("Location: login.php");
    }
        // Verbindung zu DB (feedbdb) aufbauen
    include("PHP/connect.php");
    include("PHP/Mysql.class.php");
    include("PHP/MysqlStatement.class.php");
    ?>

    <!-- Head -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="CSS/style.css" media="only screen and (max-width:480px)">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
        <script language="javascript" type="text/javascript" src="JS/index.js"></script>
        <script language="javascript" type="text/javascript" src="JS/recorder.js"></script>
        
        <title>Feedback-App</title>
    </head>
    <!-- /Head -->

    <!-- Body -->
    <body>
        <!-- Wenn Seite als <form action=...> aufgerufen wurde, werden hier die übergebenen Werte verarbeitet und in die DB geschrieben -->
        <?php
        $Mysql = new Mysql();
        if (isset($_POST['f_inhalt']) && isset($_POST['f_aktiv'])) {
            $sql = "INSERT INTO feedback (f_inhalt, f_aktiv, ID_aufgabe) VALUES (:0,:1,:2)";
            $MysqlStatement_insert = $Mysql->getMysqlStatement($sql);
            $MysqlStatement_insert->execute($_POST['f_inhalt'], $_POST['f_aktiv'], 2);
            echo $MysqlStatement_insert->sql;
        }
        ?>
        
        <!-- Seite 2 nach Startpage / Login: Upload (neue Aufgabe erstellen) -->
        <div data-role="page" id="pagetwo">
            <div data-role="header">
                <h1>Feedback</h1>
            </div>

            <!-- Main-Content -->
            <div data-role="main" class="ui-content">
                <!-- Navbar -->
                <div data-role="tabs" id="tabs">
                    <div data-role="navbar">
                        <ul>
                            <li><a href="#one" class="ui-btn-active" data-ajax="false">Upload</a></li>
                            <li><a href="#two" data-ajax="false">Tasks</a></li>
                            <li><a href="#three" data-ajax="false">Meine Tasks</a></li>
                        </ul>
                    </div>

                    <!-- Navbar: #one Upload -->
                    <div id="one" class="tab-content ui-content" date-role="content">
                        <div class="ui-nodisc-icon ui-noback-icon">
                            <!-- Circle Animation -->
                            <div id="circles-div" class="circle-container">
                                <div class="circle white" style="animation-delay: -6s">
                                </div>
                                <div class="circle white" style="animation-delay: -4s">
                                </div>
                                <div class="circle white" style="animation-delay: -2s">
                                </div>
                                <div class="circle white" style="animation-delay: 0s">
                                </div>
                                <div>Select<br>Image</div>
                            </div>
                            
                            <!-- Neue Aufgabe erstellen -->
                            <div id="image-div" class="collapsed">
                                <!-- Bild wird angezeigt -->
                                <img id="uploaded-image">
                                <!-- Titel & Beschreibung eingeben -->
                                <div class="upload-textbox">
                                    <input type="text" id="upload-name" placeholder="Titel">
                                    <textarea rows="4" cols="50" maxlength="400" type="text" id="upload-description" placeholder="Projektbeschreibung"></textarea>
                                </div>
                            </div>
                            <!-- Button "arrow-up": Aufgabe hochladen -->
                            <a href="javascript:;" onclick="submitTask(this)" data-role="button" data-icon="arrow-u" data-iconpos="notext" data-inline="true" id="submit-button" class="button-disabled"></a>
                            <div>
                                <!-- Button "camera": Bild auswählen -->
                                <a href="javascript:;" onclick="uploadImage(this)" data-role="button" data-icon="camera" data-iconpos="notext" data-inline="true" id="upload-button"></a>
                            </div>
                            <!-- mit PHP (upload.php) an DB schicken -->
                            <form id="hidden-upload-form"  action="upload.php" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                <input id="hidden-upload-button" type="file"/>
                            </form>
                        </div>
                    </div>

                    <!-- Navbar: #two Tasks -->
                    <div id="two" class="tab-content ui-content" date-role="content">

                        <!-- Auflistung: aktive Aufgaben anderer User anzeigen -->
                        <ul data-role="listview" data-inset="true">
                            <?php
                                // Einträge aus DB auswählen
                            $sqlAufgaben = "SELECT * FROM aufgaben WHERE a_aktiv=1 AND ID_user!=:0";
                            $MysqlStatement_select2 = $Mysql->getMysqlStatement($sqlAufgaben);
                            $MysqlStatement_select2->execute($_SESSION['user_id']);
                            while ($data = $MysqlStatement_select2->fetchArray()) {
                                $dataName = $data['a_name'];
                                $ownerID = $data['ID_user'];
                                $dataName = str_replace(" ", "_", $dataName);
                                $dataName = preg_replace("/[^A-Za-z0-9_]/", '', $dataName);
                                $dataID = $data['ID_aufgaben'];
                                $owner = $ownerID;
                                $creatorID = $_SESSION['user_id'];

                                $sqlAufgabeOwner = "SELECT * FROM users WHERE ID_user=:0";
                                $MysqlStatement_select4 = $Mysql->getMysqlStatement($sqlAufgabeOwner);
                                $MysqlStatement_select4->execute($ownerID);
                                while ($data2 = $MysqlStatement_select4->fetchArray()) {
                                    $owner = $data2['name'];
                                    break;
                                }

                                echo '<li>';
                                    // a_name (Titel), a_image (Bild), a_inhalt (Beschreibung) der Tasks aus DB auslesen
                                echo "<a href='#two_" . $dataName . "' data-rel='popup' data-position-to='window' data-transition='pop'>
                                                <div>
                                                    <p><strong>" . $data['a_name'] . "</strong><br></p>
                                                    <div>
                                                        <img width=100px src='" . $data['a_image'] . "'>
                                                    </div>
                                                    <p>$owner</p>
                                                    <p id='text-inhalt'>" . $data['a_inhalt'] . "</p>
                                                </div>
                                            </a>";
                                        
                                    // Pop-Up: Tasks
                                    // a_name (Titel) des Tasks aus DB auslesen 
                                    // Recording-Time
                                    // Aufnahme-Sarten Button
                                    // Abschicken Button
                                    // Aufnahme-Sarten Infotext
                                    // Abschicken Infotext
                                echo "<div data-role='popup' id='two_" . $dataName . "' data-overlay-theme='b' data-theme='a' class='ui-corner-all'>
                                            <p><strong>" . $data['a_name'] . "</strong></br></p>
                                            <div id='popup-line'></div>
                                            <p>" . $owner . "</p>
                                            <form method='post' action=" . htmlspecialchars($_SERVER['PHP_SELF']) . ">
                                                <div class='recordAudioTime'>
                                                    00:00:00.0
                                                </div>
                                                <button type='button' class='recordAudioButton' onclick='toggleRecording(this)'>Aufnahme starten</button>
                                                <button type='button' style='display: none' class='submitAudioButton' onclick='submitRecording(this, $dataID, $creatorID)'>Abschicken</button>
                                            </form>
                                            </br>
                                            <p class='popup-info-aufnahme'>Drücke den Button, um mit der Feedback-Aufnahme<br>zu beginnen.</p>
                                            <p class='popup-info-senden' style='display: none'>Drücke den Button, um die Feedback-Aufnahme<br>zu senden.</p>
                                        </div>";
                                echo '</li>';
                            }
                            ?> 
                        </ul>
                    </div>

                    <!-- Navbar: #three Meine Tasks -->
                    <div id="three" class="tab-content ui-content" date-role="content">

                        <!-- Auflistung: meine Aufgaben anzeigen -->
                        <ul data-role="listview" data-inset="true"><ul data-role="listview" data-inset="true">
                            <?php
                                // Einträge aus DB auswählen
                            $sqlAufgaben = "SELECT * FROM aufgaben WHERE a_aktiv=1 AND ID_user=:0";
                            $MysqlStatement_select2 = $Mysql->getMysqlStatement($sqlAufgaben);
                            $MysqlStatement_select2->execute($_SESSION['user_id']);
                            while ($data = $MysqlStatement_select2->fetchArray()) {
                                $dataName = $data['a_name'];
                                $dataName = str_replace(" ", "_", $dataName);
                                $dataName = preg_replace("/[^A-Za-z0-9_]/", '', $dataName);
                                $dataID = $data['ID_aufgaben'];
                                    
                                    // a_name (Titel), a_image (Bild), a_inhalt (Beschreibung) der Tasks aus DB auslesen
                                echo '<li>';
                                echo "<a href='#three_" . $dataName . "' data-rel='popup' data-position-to='window' data-transition='pop'>
                                            <div>
                                                <p><strong>" . $data['a_name'] . "</strong><br></p>
                                                <div>
                                                    <img width=100px src='" . $data['a_image'] . "'>
                                                </div>
                                                <p id='text-inhalt'>" . $data['a_inhalt'] . "</p>
                                            </div>
                                        </a>";
                                            
                                        // Pop-Up: Meine Tasks 
                                        // aus DB (feedback) f_inhalt (Audiofile), ID_feedback, ID_creator, timestamp (Datum, Uhrzeit) aus DB auslesen
                                echo "<div data-role='popup' id='three_" . $dataName . "' data-overlay-theme='b' data-theme='a' class='ui-corner-all'>
                                            <p><strong>" . $data['a_name'] . "</strong></br></p> <ul>";
                                $sqlFeedbacks = "SELECT * FROM feedback WHERE f_aktiv=1 AND ID_aufgabe=:0";
                                $MysqlStatement_select3 = $Mysql->getMysqlStatement($sqlFeedbacks);
                                $MysqlStatement_select3->execute($dataID);
                                $noRows = true;
                                while ($data2 = $MysqlStatement_select3->fetchArray()) {
                                    $noRows = false;
                                    $filePath = $data2['f_inhalt'];
                                    $feedbackID = $data2['ID_feedback'];
                                    $creatorID = $data2['ID_creator'];
                                    $timestamp = $data2['timestamp'];
                                    $creator = $creatorID;

                                    $sqlFeedbackCreator = "SELECT * FROM users WHERE ID_user=:0";
                                    $MysqlStatement_select4 = $Mysql->getMysqlStatement($sqlFeedbackCreator);
                                    $MysqlStatement_select4->execute($creatorID);
                                    while ($data3 = $MysqlStatement_select4->fetchArray()) {
                                        $creator = $data3['name'];
                                        echo "<b>$creator</b>";
                                        break;
                                    }
                                            // Audio-Container: zum Abspielen der Feedbacks
                                    echo "<li>
                                                    <div class='audioContainer'>
                                                        <div class='audioProgress' id='audioProgress$feedbackID' style='width:0px'></div>
                                                        <div class='audioControl audioPlay' rel='play' id='$feedbackID'></div>
                                                        <div class='audioTime' id='audioTime$feedbackID'>00.00</div>
                                                        <p class='audioTimestamp'>$timestamp</p>
                                                        <audio preload='auto' src='$filePath' type='audio/mpeg' class='a' id='audio$feedbackID'><source></audio>
                                                    </div>   
                                                </li>";
                                }
                                if ($noRows) {
                                    echo "Keine Feedbacks für diese Aufgabe gefunden!";
                                }
                                echo "</ul>
                                        </div>";
                                echo '</li>';
                            }
                            ?> 
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div data-role="footer">
                <h3>© Sara Unteregger & Simon Wünscher | <a href="logout.php">logout</a></h3> 
                <?php
                    echo $_SESSION['user_name'];
                ?>
            </div>
            <!-- /Footer -->
        </div>
    </body>
    <!-- /Body -->
</html>