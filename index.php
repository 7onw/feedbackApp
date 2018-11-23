<!DOCTYPE html>
    <?php
        session_start();
        if ( isset( $_SESSION['user'] ) ) {
        } else {
            header("Location: login.php");
        }
        include("PHP/connect.php");
        include("PHP/Mysql.class.php");
        include("PHP/MysqlStatement.class.php");
    ?>

    <!-- Head -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="CSS/style.css" media="only screen and (max-width:480px)">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <script language="javascript" type="text/javascript" src="JS/index.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

        <!--script src="recorderControl.js"></script>
        <script src="js/jquery.min.2.1.js"></script> //Jquery
        <script src="js/jquery.stopwatch.js"></script> //Jquery Stop Watch
        <script src="recorder.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){   
            });
        </script-->
        
        <title>Feedback-App</title>
    </head>

    <!-- Body -->
    <body>
        <!-- Form-Eintrag über DB -->
        <?php
            $Mysql = new Mysql();
            // SQL-Statement: $sql
            //$sql = "SELECT * FROM users WHERE name=:0 AND pw=:1";
            // SQL-Statement: $sqlAufgaben
            //$MysqlStatement_select = $Mysql->getMysqlStatement($sql);
            //$MysqlStatement_select->execute($_POST['name'], $_POST['pw']); 

            if(isset($_POST['f_inhalt']) && isset($_POST['f_aktiv']))
            {
                $sql = "INSERT INTO feedback (f_inhalt, f_aktiv, ID_aufgabe) VALUES (:0,:1,:2)";
                $MysqlStatement_insert = $Mysql->getMysqlStatement($sql);
                $MysqlStatement_insert->execute($_POST['f_inhalt'], $_POST['f_aktiv'], 2); 
                echo"bin im if";
                echo $MysqlStatement_insert->sql;
            }      
        ?>
        
        <!-- Seite 2: Hochladen -->
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
                            <li><a href="#two" data-ajax="false">Aufgaben</a></li>
                            <li><a href="#three" data-ajax="false">Statistiken</a></li>
                        </ul>
                    </div>
                    <!-- Navbar: 1 -->
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
                            <div id="image-div" class="collapsed">
                                <img id="uploaded-image">
                                <input type="text" id="upload-name">
                                <input type="text" id="upload-description">
                            </div>
                            <a href="javascript:;" onclick="submitTask(this)" data-role="button" data-icon="arrow-u" data-iconpos="notext" data-inline="true" id="submit-button" class="button-disabled"></a>
                            <div>
                                <a href="javascript:;" onclick="uploadImage(this)" data-role="button" data-icon="camera" data-iconpos="notext" data-inline="true" id="upload-button"></a>
                            </div>
                            <form id="hidden-upload-form"  action="upload.php" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                <input id="hidden-upload-button" type="file"/>
                            </form>
                        </div>
                    </div>
                    <!-- Navbar: 2 -->
                    <div id="two" class="tab-content ui-content" date-role="content">
                            <!--?php echo "<br /> SQL Statement: <br/>" . $MysqlStatement_select->sql; ?-->
                            <!--?php echo "<br /> NUM: " . $MysqlStatement_select->num_rows; ?-->

                        <!-- Listenelemente: Aufgaben -->
                        <ul data-role="listview" data-inset="true">
                            <?php
                                $sqlAufgaben = "SELECT * FROM aufgaben";
                                $MysqlStatement_select2 = $Mysql->getMysqlStatement($sqlAufgaben);
                                $MysqlStatement_select2->execute(); 
                                while ($data = $MysqlStatement_select2->fetchArray()) { 
                                    echo '<li>';
                                            // Weiterleitung auf Aufgabe-Seite -> wird nicht mehr benötigt!
                                            // echo "<a href='givefb.php'>";  

                                        // Aufgaben-Task
                                            // Name der Aufgabe aus DB auslesen 
                                            // Text (aus DB) wird ausgelesen
                                        echo "<a href='#". $data['a_name']."' data-rel='popup' data-position-to='window' data-transition='pop'>
                                                <div>
                                                    <p><strong>". $data['a_name']."</strong><br></p>
                                                    <div>
                                                        <img width=100px src='".$data['a_image']."'>
                                                    </div>
                                                    <p>". $data['a_inhalt']."</p>
                                                </div>
                                            </a>";
                                            
                                        // Pop-Up 
                                            // Name der Aufgabe aus DB auslesen 
                                            // -> Audio-Recording einfügen!
                                        echo "<div data-role='popup' id='". $data['a_name']."' data-overlay-theme='b' data-theme='a' class='ui-corner-all'>
                                            <p><strong>".$data['a_name']."</strong></br></p>
                                               <form method='post' action=".htmlspecialchars($_SERVER['PHP_SELF']).">Feedbackinhalt:
                                                    <input type='text' name='f_inhalt'><br>Aktivieren:
                                                    <input type='radio' name='f_aktiv'><br>
                                                    <button type='submit'>Abschicken</button>

                                                    <div class='audioContainer'>
                                                        <div class='audioProgress' id='audioProgress10'' style='width:0px'>
                                                        </div>
                                                        <div class='audioControl audioPlay' rel='play' id='10'>
                                                        </div>
                                                        <div class='audioTime' id='audioTime10''>
                                                        00.00
                                                        </div>
                                                        <div class='audioBar'>
                                                        </div>
                                                        <audio preload='auto' src='data:audio/mp3;base64,//sUxAAABAArMFRhgA..' type='audio/mpeg' class='a' id='audio10''>
                                                            <source>
                                                        </audio>
                                                    </div>   

                                                    <img width=100px src='images/mic.png' id='recordButton' class='recordOff'>
                                                    <span id='recordHelp'>Allow your microphone.</span>
                                                    <div id='recordContainer' class='startContainer'>
                                                        <div id='recordTime'> <span id='sw_m'>00</span>:<span id='sw_s'>00</span></div>
                                                        <div id='recordCircle' class='startRecord'><div id='recordText'>Record</div></div>
                                                    </div>
                                                </form>
                                        </div>";
                                    echo '</li>';
                                }
                            ?> 
                        </ul>
                    </div>
                    <!-- Navbar: 3 -->
                    <div id="three" class="tab-content ui-content" date-role="content">
                        <ul data-role="listview" data-inset="true">
                        </ul>
                    </div>
                </div>
            </div>
            <div data-role="footer">
                <h3>© Sara Unteregger & Simon Wünscher | <a href="logout.php">logout</a></h3>
            </div>
        </div>
    </body>
</html>