<?php
    session_start();
    $point=$_SESSION['point'];
    $usr = $_SESSION['nickname'];
    $id = $_SESSION['username'];
    $monster = $_SESSION['monster'];
    $host = "localhost";
    $db_id = "db_id";
    $db_password = "db_pw";
    $db_name = "db_name";
    $connection = mysqli_connect($host,$db_id,$db_password);
    $db = mysqli_select_db($connection,$db_name);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trash_Eater</title>
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
        #image{
            width: 200px;
            height: 30em;
        }
        #upload-img{
            height: 6em;
            cursor: pointer;
        }
        #send{
            height: 7em;
            display: none;
        }
        #cur_point{
            font-size: 35px;
            font-family: 'Jua', sans-serif;
        }
        #title {
            width: 200px;
            margin: 10px;
            height: 7em;
        }
        .addthis_inline_share_toolbox{
            margin: 15px;
        }
        #back {
            height: 5em;
            cursor: pointer;
        }
        #label-container{
            font-size: 25px;
            font-family: 'Jua', sans-serif;
        }
    </style>
</head>
<body>
    <nav>
        <img id= "back" alt="back" src="../images/back.png" onclick="back()">
    </nav>
    <div id = "container">
        <img id = "title" src="../images/trash-title.png"><br>
        <img id = "sub_container">
            <label id = "input-image-label" for="input-image"><img id = "upload-img" src="../images/eat.png" alt="eat"></label>
            <input type="file" id="input-image" accept="image/*" capture="camera" style="display: none;" onclick="readImage(this)">
            <div id="image-container"><img id="image" src="../images/cha.gif"></div>
            <div id="label-container"></div>
            <h3 id = "cur_point">현재 포인트 : 0pt</h3><img src="../images/earn.gif" alt = "send"id ="send" onclick="send()">
                <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <div class="addthis_inline_share_toolbox"></div>
        <div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@1.3.1/dist/tf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@0.8/dist/teachablemachine-image.min.js"></script>
<script type="text/javascript">
    // player point
    let player  = {
        point : 0,
        place : null,
    };
    const target1 = document.getElementById("input-image-label");
    const target2 = document.getElementById("upload-img");
    const target4= document.getElementById("send");
    back = () =>{
        window.location.href = "./info.php?point=0";
    }
    send = () =>{
        alert(player.point+'포인트 적립완료!');
        window.location.href = "./info.php?point="+player.point;
    }

    point_accumulate = () => {
        const point = document.getElementById("cur_point");
        point.innerHTML = '현재 포인트 : '+player.point;
        console.log(player.point);
    }
    // camera img picker
    async function readImage(input) {
        // 인풋 태그에 파일이 있는 경우
        if(input.files && input.files[0]) {
            // FileReader 인스턴스 생성
            const reader = new FileReader();
            // 이미지가 로드가 된 경우
            reader.onload = e => {    
                const previewImage = document.getElementById("image");
                previewImage.src = e.target.result;
            }
            // reader가 이미지 읽도록 하기
            reader.readAsDataURL(input.files[0]);
            init().then(()=>predict());
        }
    }
    // input file에 change 이벤트 부여
    const inputImage = document.getElementById("input-image")
    inputImage.addEventListener("change", e => {
        readImage(e.target)
    })

    // More API functions here:
    // https://github.com/googlecreativelab/teachablemachine-community/tree/master/libraries/image

    // the link to your model provided by Teachable Machine export panel
    const URL = "./my_model/";

    let model, labelContainer, maxPredictions;
    async function init() {
        const modelURL = URL + "model.json";
        const metadataURL = URL + "metadata.json";

        // load the model and metadata
        // Refer to tmImage.loadFromFiles() in the API to support files from a file picker
        // or files from your local hard drive
        // Note: the pose library adds "tmImage" object to your window (window.tmImage)
        model = await tmImage.load(modelURL, metadataURL);
        maxPredictions = model.getTotalClasses();
        labelContainer = document.getElementById("label-container");
        let max = document.createElement("div");
        max.setAttribute("id","max");
        labelContainer.appendChild(max);
        for (let i = 0; i < maxPredictions; i++) { // and class labels
            labelContainer.appendChild(document.createElement("div"));
        }
    }
    // run the webcam image through the image model
    async function predict() {
        spinner_start();
        // predict can take in an image, video or canvas html element
        var image = document.getElementById("image");
        const prediction = await model.predict(image,false);
        let MAX_value = {label :"",probability: 0,};
        let pre_prediction = prediction;
        let sort_prediction;
        sort_prediction = pre_prediction.sort(function(a,b){
            return b.probability - a.probability;
        });
        for (let i = 0; i < maxPredictions; i++) {
            // label 값 실수로 일반 쓰레기를 쓰레기로 함 추후 수정 예정
            if(sort_prediction[i].className == "쓰레기")sort_prediction[i].className = "일반 쓰레기";
            const classPrediction =
                sort_prediction[i].className + ": " + (sort_prediction[i].probability.toFixed(2))*100 + "%";
            if(sort_prediction[i].probability > MAX_value.probability) {
                MAX_value.label = sort_prediction[i].className;
                MAX_value.probability= sort_prediction[i].probability.toFixed(2);
            }
            labelContainer.childNodes[i+1].innerHTML = classPrediction;
        }
        labelContainer.style.backgroundColor = "white";
        labelContainer.style.borderRadius = "8px";
        labelContainer.style.boxShadow = "0 3px 3px 3px rgba(0, 0, 0, 0.684)";
        labelContainer.childNodes[0].innerHTML = "이 쓰레기는 "+ MAX_value.probability*100 + "%로 " + MAX_value.label +" 입니다.";
        // 쓰레기 분류 값에 따라 point 설정
        let get=0;
        if(MAX_value.label == "일반 쓰레기"){
            get = 10;
            player.point+=get;}
        else if(MAX_value.label == "종이"){
            get = 20;
            player.point+=get;}
        else if(MAX_value.label == "플라스틱"){
            get = 30;
            player.point+=get;}
        else if(MAX_value.label == "고철"){
            get = 40;
            player.point+=get;}
        else if(MAX_value.label == "유리"){
            get = 50;
            player.point+=get;}
        point_accumulate();
        spinner_remove();
        alert(get+"포인트 획득!");
        target1.style.display = "none";
        target2.style.display = "none";
        target4.style.display = "inline";
    }
    spinner_start = () =>{
        const w_Bro = window.innerWidth;
        const h_Bro = window.innerHeight;
        const tag_Pra = document.getElementById("container");
        let spinner = document.createElement("img");
        spinner.setAttribute("id","spinner");
        spinner.setAttribute("src","./slime-jump.gif");
        spinner.setAttribute("style","./slime-jump.gif");
        tag_Pra.appendChild(spinner); 
    }
    spinner_remove = () =>{
        const spinner = document.getElementById("spinner");
        spinner.remove();
    }
</script>

<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-61656119fa66a183"></script>

</body>
</html>