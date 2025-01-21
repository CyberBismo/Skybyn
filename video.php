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
  <button id="hangupCall" disabled>Hang Up</button>

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
      try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = localStream;
        console.log("Local media stream initialized.");
      } catch (error) {
        console.error("Error accessing media devices:", error);
      }
    }

    // Create Peer Connection
    function createPeerConnection() {
      peerConnection = new RTCPeerConnection(configuration);

      // Add Local Stream to Peer Connection
      localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

      // Handle Remote Stream
      peerConnection.ontrack = (event) => {
        remoteVideo.srcObject = event.streams[0];
        console.log("Remote stream received.");
      };

      // Handle ICE Candidates
      peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
          signalingServer.send(JSON.stringify({ type: 'ice-candidate', candidate: event.candidate }));
          console.log("ICE candidate sent:", event.candidate);
        }
      };

      console.log("Peer connection created.");
    }

    // Start Call
    async function startCall() {
      try {
        if (!localStream) {
          console.error("Local media stream is not initialized.");
          alert("Please allow camera and microphone access.");
          return;
        }

        createPeerConnection();

        // Create WebRTC offer
        console.log("Creating WebRTC offer...");
        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);
        console.log("WebRTC offer created and set as local description.");

        // Send the offer to the signaling server
        if (signalingServer.readyState === WebSocket.OPEN) {
          signalingServer.send(JSON.stringify({ type: 'offer', offer }));
          console.log("Offer sent to signaling server:", offer);
        } else {
          console.error("Signaling server is not connected.");
        }

        // Disable start call button and enable hangup button
        startCallButton.disabled = true;
        hangupCallButton.disabled = false;
      } catch (error) {
        console.error("Error starting call:", error);
      }
    }

    // Hang Up Call
    function hangupCall() {
      if (peerConnection) {
        peerConnection.close();
        peerConnection = null;
        console.log("Call ended.");
      }
      signalingServer.send(JSON.stringify({ type: 'hangup' }));
      startCallButton.disabled = false;
      hangupCallButton.disabled = true;
    }

    // Handle Messages from Signaling Server
    signalingServer.onmessage = async (message) => {
      try {
        const data = JSON.parse(message.data);

        if (data.type === 'offer') {
          console.log("Received offer from signaling server.");
          createPeerConnection();
          await peerConnection.setRemoteDescription(new RTCSessionDescription(data.offer));

          const answer = await peerConnection.createAnswer();
          await peerConnection.setLocalDescription(answer);

          if (signalingServer.readyState === WebSocket.OPEN) {
            signalingServer.send(JSON.stringify({ type: 'answer', answer }));
            console.log("Answer sent to signaling server:", answer);
          }
        } else if (data.type === 'answer') {
          console.log("Received answer from signaling server.");
          await peerConnection.setRemoteDescription(new RTCSessionDescription(data.answer));
        } else if (data.type === 'ice-candidate') {
          console.log("Received ICE candidate from signaling server.");
          await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
        } else if (data.type === 'hangup') {
          console.log("Received hangup signal.");
          hangupCall();
        }
      } catch (error) {
        console.error("Error handling signaling message:", error);
      }
    };

    // WebSocket Open Event Listener
    signalingServer.onopen = () => {
      console.log("Connected to signaling server.");
    };

    // WebSocket Error Handling
    signalingServer.onerror = (error) => {
      console.error("WebSocket error:", error);
    };

    // WebSocket Close Event Listener
    signalingServer.onclose = () => {
      console.warn("Signaling server connection closed.");
    };

    // Event Listeners
    startCallButton.addEventListener('click', startCall);
    hangupCallButton.addEventListener('click', hangupCall);

    // Initialize
    getMedia();
  </script>
</body>
</html>
