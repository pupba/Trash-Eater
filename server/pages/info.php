<?php
    session_start();
    $usr=$_SESSION['username'];
    $pointN = $_GET['point']??0;
    $host = "localhost";
    $db_id = "db_name";
    $db_password = "db_pw";
    $db_name = "db_name";
    $connection = mysqli_connect($host,$db_id,$db_password);
    $db = mysqli_select_db($connection,$db_name);
    if($db){
            $query = "SELECT name,point,monster FROM info WHERE id = '". $usr . "';";
            $result = mysqli_query($connection,$query);
            while($row = mysqli_fetch_array($result)){
                $nickname = $row[0];
                $point = $row[1];
                $monster = $row[2];
                $_SESSION['point'] = $point;
                $_SESSION['nickname'] = $nickname;
                $_SESSION['monster'] = $monster;
            }
            
            $pointsum = $point + $pointN;
            $query1 = "UPDATE info SET point='". $pointsum ."' WHERE id = '".$nickname."';";
            $result1 = mysqli_query($connection,$query1);
            
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>info</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Jua&display=swap');
        html{
          height: 100%;
        }
        body{
            height: 100%;
            padding: 0 10px;
            background-image: linear-gradient(
            rgba(0, 0, 0, 0.39),
            rgba(255, 255, 255, 0.384)
            ),
            url(<?php 
                if($monster == 1){
                    echo "\"./background.gif\"";
                }elseif($monster == 2){
                    echo "\"./background2.gif\"";
                }elseif($monster == 3){
                    echo "\"./background3.gif\"";
                }
            ?>);
            
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }
        #container {
          text-align: center;
          margin: 0;
          border-radius: 8px;
          box-shadow: 0 3px 3px 3px rgba(0, 0, 0, 0.785);
        } 
        #character{
            height: 20em;
        }
        #title{
           height: 7em;
        }
        #info {
            font-size: 25px;
            font-family: 'Jua', sans-serif;
        }
        #eat {
            height: 5em;
            cursor: pointer;
        }
        #logout{
            height: 5em;
            cursor: pointer;
        }
    </style>
</head>
<body>
<img id = "logout" src="../images/logout.png" alt="logout" onclick="logout()">
    <div id="container">
        <img src="../images/info-title.png" id = "title"><br>
        <?php
            echo "<B id = \"info\">닉네임 : {$nickname} | 포인트 : {$pointsum}pt</B><br>";
            if($monster == 1){
                echo "<p><img id = \"character\" src=\"../images/monster1.gif\" alt=\"슬라임\"></p>";
            }
            elseif($monster == 2){
                echo "<p><img id = \"character\" src=\"../images/monster2.gif\" alt=\"고래\"></p>";
            }
            elseif($monster == 3){
                echo "<p><img id = \"character\" src=\"../images/monster3.gif\" alt=\"불\"></p>";
            }
        ?>
                <!-- Go to www.addthis.com/dashboard to customize your tools -->
                <div class="addthis_inline_share_toolbox"></div>
            
        <p><img id = "eat" src="../images/eat.png" alt="eat" onclick="eat()"></p>
    </div>
    <script>
        const eat = () => {
            window.location.href="./trash.php";
        }
        const logout = () =>{
            window.location.href = "./login.php";
        }
    </script>
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-61656119fa66a183"></script>

</body>
</html>