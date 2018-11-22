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
    <title>Feedback-App</title>
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

    

    <div id="content">
    <div data-role="main" class="ui-content">
        <div data-role="tabs" id="tabs">
            <div data-role="navbar">
                <ul>
                    <li><a href="#one" class="ui-btn-active" data-ajax="false">Upload</a></li>
                    <li><a href="#two" data-ajax="false">Aufgaben</a></li>
                    <li><a href="#three" data-ajax="false">History</a></li>
                </ul>
            </div>
            <div id="one" class="tab-content ui-content" date-role="content">
                <div class="ui-nodisc-icon ui-noback-icon">

                    <a ref="#pagetwo" data-role="button" data-icon="camera" data-iconpos="notext" data-inline="true"></a>

                    <div><a href="#pagetwo" data-role="button" data-icon="arrow-u" data-iconpos="left" data-inline="true" id="upload-button">Upload</a></div>
                </div>
            </div>
            <div id="two" class="tab-content ui-content" date-role="content">
            <?php echo "<br /> SQL Statement: <br/>" . $MysqlStatement_select->sql; ?>

<?php echo "<br /> NUM: " . $MysqlStatement_select->num_rows; ?>
<?php echo "<br /><br />"  ?>

<?php

    while ($data = $MysqlStatement_select2->fetchArray()) {
    echo"<a href='givefb.php'>";

    echo $data['a_name'];
    echo"</a>";
    echo "<br/>";}
?>
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
                    <!--?php
                        for($i = 0; $i < 20; $i++) {
                            echo   '<li><a href="#">
                                        <div>
                                            <div>
                                                <img src="img/p1.jpg">
                                            </div>
                                            <p>Hello user '.($i+1).'</p>
                                        </div>
                                    </a></li>'
                                    ;
                        }
                    ?-->
                </ul>
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
    </div>



        <button onClick="printSomeThing();" class="buttonBlack">neue Aufgabe</button>
    </div>

</body>
</html>