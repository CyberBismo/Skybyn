<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WebRTC Peer-to-Peer</title>
</head>
<body>
  <h1>WebRTC Peer-to-Peer Audio/Video</h1>
  <video id="localVideo" autoplay muted playsinline></video>
  <video id="remoteVideo" autoplay playsinline></video>
  <button id="startCall">Start Call</button>
  <button id="hangupCall">Hang Up</button>

  <script>
    const signalingServer = new WebSocket('wss://dev.skybyn.com:4433');
    let localStream;
    let peerConnection;

    // STUN Server Configuration
    const configuration = {
      iceServers: [
        { urls: 'stun:stun.l.google.com:19302' } // Public STUN server
      ]
    };

    // Elements
    const localVideo = document.getElementById('localVideo');
    const remoteVideo = document.getElementById('remoteVideo');
    const startCallButton = document.getElementById('startCall');
    const hangupCallButton = document.getElementById('hangupCall');

    // Get Media (Audio/Video)
    async function getMedia() {
      localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
      localVideo.srcObject = localStream;
    }

    // Create Peer Connection
    function createPeerConnection() {
      peerConnection = new RTCPeerConnection(configuration);

      // Add Local Stream to Peer Connection
      localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

      // Handle Remote Stream
      peerConnection.ontrack = (event) => {
        remoteVideo.srcObject = event.streams[0];
      };

      // Handle ICE Candidates
      peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
          signalingServer.send(JSON.stringify({ type: 'ice-candidate', candidate: event.candidate }));
        }
      };
    }

    // Start Call
    async function startCall() {
      createPeerConnection();

      // Create Offer
      const offer = await peerConnection.createOffer();
      await peerConnection.setLocalDescription(offer);

      signalingServer.send(JSON.stringify({ type: 'offer', offer }));
    }

    // Hang Up Call
    function hangupCall() {
      peerConnection.close();
      peerConnection = null;
      signalingServer.send(JSON.stringify({ type: 'hangup' }));
    }

    // Handle Messages from Signaling Server
    signalingServer.onmessage = async (message) => {
      const data = JSON.parse(message.data);

      if (data.type === 'offer') {
        createPeerConnection();
        await peerConnection.setRemoteDescription(new RTCSessionDescription(data.offer));

        const answer = await peerConnection.createAnswer();
        await peerConnection.setLocalDescription(answer);

        signalingServer.send(JSON.stringify({ type: 'answer', answer }));
      } else if (data.type === 'answer') {
        await peerConnection.setRemoteDescription(new RTCSessionDescription(data.answer));
      } else if (data.type === 'ice-candidate') {
        await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
      } else if (data.type === 'hangup') {
        hangupCall();
      }
    };

    // Event Listeners
    startCallButton.addEventListener('click', startCall);
    hangupCallButton.addEventListener('click', hangupCall);

    // Initialize
    getMedia();
  </script>
</body>
</html>
