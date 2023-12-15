<?php
  session_start();
  $username = $_POST[ 'username' ]??'username';
  $password = $_POST[ 'password' ]??'password';
  $encrypt_pw = -1;
  $host = "localhost";
  $db_id = "db_id";
  $db_password = "db_passwd";
  $db_name = "db_name";
  $connection = mysqli_connect($host,$db_id,$db_password);
  $db = mysqli_select_db($connection,$db_name);
  if($db){
    if(!is_null($username)){
      $query = "SELECT pw FROM info WHERE id = '". $username . "';";
      $result = mysqli_query($connection,$query);
      while($row = mysqli_fetch_array($result)){
        $encrypt_pw = password_hash($row[ 'pw' ],PASSWORD_DEFAULT);
      }
      if(is_null($encrypt_pw)){
        $wp = -1;
      }
      else {
        if(password_verify($password, $encrypt_pw)){
          $_SESSION['username'] = $username;
          header('Location: info.php');
        }else if($encrypt_pw == -1){
          $wp = -1;
        }
        else {
          $wp =1;
        }
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login page</title>
  <style>
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
        #id, #pw{
          height: 3em;
        }
        #container {
          text-align: center;
          margin: 0;
          border-radius: 8px;
          box-shadow: 0 3px 3px 3px rgba(182, 252, 85, 1)
        }
        #loginBtn{
          height: 5em;
        }
        #joinBtn{
          height: 5.5em;
        }
        #id-input, #pw-input{
          background-color: white;
          border: 4px soild greenyellow;
          border-radius: 8px;
        }
    </style>
</head>
<body>
  <div id = "container">
  <img id="title" src="../images/login-title.png">
  <form name="frm" action="login.php" method="POST">
      <img id="id" src="../images/id-text.gif"><input id="id-input" type="text" name="username" placeholder="사용자이름" required>
      <br><img id="pw" src="../images/pw-text.gif"><input id="pw-input" type="password" name="password" placeholder="비밀번호" required>
      <p><label for="login"><img id="loginBtn" src='../images/login.png' alt="login"></label><input id = "login"type="submit" value="로그인" style="display:none">
        <br><img id = "joinBtn" src='../images/join.png' alt =join onclick="join()">
      </p>
  </div>
  <script>
    const join = () =>{
      window.location.href="./join.php"
    }
  </script>
  <?php
    if ( $wp == 1 ) {
      echo"<script>alert('아이디 비밀번호를 확인해 주세요!');</script>";
    }
  ?>
</body>
</html>