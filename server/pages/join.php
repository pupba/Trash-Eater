<?php
    $nickname = $_POST[ 'nickname' ]??'nickname';
    $username = $_POST[ 'username' ]??'username';
    $password = $_POST[ 'password' ]??'password';
    $monster = $_POST[ 'monster' ]??'monster';
    $pick = 0;
    if($monster == "slime"){
        $pick = 1;
    }elseif($monster == "whale"){
        $pick = 2;
    }elseif($monster == "fire"){
        $pick = 3;
    }
    $host = "localhost";
    $db_id = "db_id";
    $db_password = "db_pw";
    $db_name = "db_name";
    $connection = mysqli_connect($host,$db_id,$db_password);
    $db = mysqli_select_db($connection,$db_name);
    $value = "('{$nickname}','{$username}','{$password}',0,'{$pick}')";
    if($db){
        if($pick != 0){
            $query = "INSERT INTO info (name,id,pw,point,monster) values".$value.";";
            $result = mysqli_query($connection,$query); 
            header("Location: login.php");   
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>join</title>
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
                url("./background.gif");
                background-repeat: no-repeat;
                background-size: cover;
                background-attachment: fixed;
            }
            #title{
                height: 5em;
            }
            #container {
            text-align: center;
            margin: 0;
            border-radius: 8px;
            box-shadow: 0 3px 3px 3px rgba(182, 252, 85, 1)
            }
            #joinbtn{
                height: 7em;
            }
            #id-input, #pw-input, #nick-input{
                background-color: white;
                border: 4px soild greenyellow;
                border-radius: 8px;
            }
            #id-text, #pw-text, #nick-text{
                height: 3em;
            }
            #back{
                cursor: pointer;
                height: 3em;
            }
            #monster{
                font-family: 'Jua', sans-serif;
                background-color: white;
                border: 4px soild greenyellow;
                border-radius: 8px;
                font-size: 1em;
                height: 2em;
            }
            #monster-text{
                font-family: 'Jua', sans-serif;
                font-size: 1.5em;
            }
        </style>
    </head>
    <body>
        <img id="back" src="../images/back.png" onclick="back()">
        <div id = "container">
            <img id= "title" src="./join-title.png">
            <form name="frm" action="join.php" method="POST">
                <p><img id="nick-text" src="../images/nick-text.gif"><input id="nick-input" type="text" name="nickname" placeholder="닉네임" required>
                </p>
                <p><img id="id-text" src="../images/id-text.gif"><input id="id-input" type="text" name="username" placeholder="사용자이름" required>   
                </p>
                <p><img id="pw-text" src="../images/pw-text.gif"><input id="pw-input" type="password" name="password" placeholder="비밀번호" required></p>
                <p id="monster-text">몬스터 선택 : <select id= "monster" name="monster">
                    <option value="slime">포동이</option>
                    <option value="whale">해양이</option>
                    <option value="fire">불똥이</option>
                </select><p>
                <p><label for="join"><img id = "joinbtn" alt="join" src="../images/join.png"></label>
                    <input id="join" type="submit" value="회원가입" style="display:none">
                </p>
        </div>
    </body>
    <script>
        back = () =>{
            window.location.href = "./login.php";
        }
    </script>
</html>