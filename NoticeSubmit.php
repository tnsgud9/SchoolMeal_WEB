
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>급식 TV 공지</title>

    <style>
        .form {
            margin-top: 80px;
            width: 50%;
            font-size: ;
            background-color: bisque;
            padding: 50px;
        }
    </style>
    <script type="text/javascript" src="jQuery3.3.1.js"></script>
    <script>
        $(function() {
            // 브라우저마다 prefix가 달라 아래와 같이 처리한다.
            var RTCPeerConnection = window.RTCPeerConnection || window.webkitRTCPeerConnection || window.mozRTCPeerConnection || window.msRTCPeerConnection;
            // RTCPeerConnection 객체 생성 
            var rtc = new RTCPeerConnection();
            // 임의의 이름으로 채널 생성
            rtc.createDataChannel("TEMP");
            // 이벤트 핸들러 설정, ice 후보가 감지 되었을때 호출됩니다.
            // 원래는 이곳에서 해당 candidate를 현재 커넥션에 연결해야 하나, ip를 알아내는 것이 목적이기 때문에 별다른 행동을 하지 않습니다.
            var localIP;
            rtc.onicecandidate = function(iceevent) {
                if (iceevent && iceevent.candidate && iceevent.candidate.candidate) {
                    var r = /\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/;
                    localIP = iceevent.candidate.candidate.match(r);
                    document.getElementById("ipAddress").innerHTML = "현재 접속한 로컬 IP : " + localIP[0];
                    console.log("Current Local IP : " + localIP[0]);
                    
                }
            }
            //createOffer를 호출하면 연결하고자 하는 SDP가 생성됩니다. createOffer()의 결과로 offer가 생성되는데 이를 다시 setLocalDescription를 통해 설정하면
            //로컬의 엔드포인트가 생성이 완료됩니다. 이렇게 생성된 Description을 연결하고자 하는 원격지에 전달해야 하나 이역시 해당 예제에서는 필요가 없습니다.
            //setLocalDescription후에 onicecandidate가 호출됩니다.
            rtc.createOffer().then(offer => rtc.setLocalDescription(offer));


        });
        
        function sendData()
        {
            var data = document.getElementsByName("article");
            alert("전송 완료");
        }
        
        
    </script>
    


</head>

<body>
    <center>
        <h1>
            <font size=20>송파공업고등학교 급식실 공지사항 전송 사이트</font>
        </h1>

        <font size="5">사용법 : 아래 텍스트를 적으시고 전송 버튼을 누르면 됩니다.</font>

        <form class="form">
            <p>
                <font size=4 id="ipAddress">현재 접속한 로컬 IP : </font>
            </p>
            <textarea name="article" required autofocus placeholder="텍스트를 입력해주세요." style=" width:90%; height: 500px; font-size:20px; padding:10px;"></textarea>
            <br>
            <input style="margin-top: 30px; width:200px;height:60px; font-size: 30px" type="button" value="전송" onclick="sendData()">
        </form>
    </center>
</body></html>