<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WebSocket Video Call</title>
  <script src="assets/js/ws.js"></script>
  <style>
    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      background-color: #f0f0f0;
    }
    video {
      width: 100%;
      max-width: 300px;
      max-height: 200px;
      border: 1px solid black;
      margin-bottom: 10px;
    }
    button {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
    }
    @media (min-width: 600px) {
      body {
        flex-direction: row;
      }
      video {
        max-width: 450px;
      }
      button {
        font-size: 18px;
      }
    }
  </style>
</head>
<body>
  <video id="localVideo" autoplay muted></video>
  <video id="remoteVideo" autoplay></video>
  <button onclick="startCall()">Call</button>

  <script>
    let localStream;
    let peerConnection;
    const localVideo = document.getElementById('localVideo');
    const remoteVideo = document.getElementById('remoteVideo');

    ws.onmessage = (event) => {
      const data = JSON.parse(event.data);
      if (data.type === 'offer') {
        handleOffer(data.offer);
      } else if (data.type === 'answer') {
        handleAnswer(data.answer);
      } else if (data.type === 'candidate') {
        handleCandidate(data.candidate);
      }
    };

    const config = {
      iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
    };

    navigator.mediaDevices.getUserMedia({ video: true, audio: true })
      .then(stream => {
        localVideo.srcObject = stream;
        localStream = stream;
      });

    function handleOffer(offer) {
      peerConnection = new RTCPeerConnection(config);
      localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));
      peerConnection.onicecandidate = (e) => {
        if (e.candidate) {
          ws.send(JSON.stringify({ type: 'candidate', candidate: e.candidate }));
        }
      };
      peerConnection.ontrack = (e) => {
        remoteVideo.srcObject = e.streams[0];
      };
      peerConnection.setRemoteDescription(offer);
      peerConnection.createAnswer().then(answer => {
        peerConnection.setLocalDescription(answer);
        ws.send(JSON.stringify({ type: 'answer', answer }));
      });
    }

    function handleAnswer(answer) {
      peerConnection.setRemoteDescription(answer);
    }

    function handleCandidate(candidate) {
      peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
    }

    function startCall() {
      peerConnection = new RTCPeerConnection(config);
      localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));
      peerConnection.onicecandidate = (e) => {
        if (e.candidate) {
          ws.send(JSON.stringify({ type: 'candidate', candidate: e.candidate }));
        }
      };
      peerConnection.ontrack = (e) => {
        remoteVideo.srcObject = e.streams[0];
      };
      peerConnection.createOffer().then(offer => {
        peerConnection.setLocalDescription(offer);
        ws.send(JSON.stringify({ type: 'offer', offer }));
      });
    }
  </script>
</body>
</html>
