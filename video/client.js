document.addEventListener("DOMContentLoaded", function () {
  const ws = new WebSocket("ws://localhost:3000");
  const configuration = { iceServers: [{ urls: "stun:stun.l.google.com:19302" }] };
  const pc = new RTCPeerConnection(configuration);

  const localVideo = document.getElementById("localVideo");
  const remoteVideo = document.getElementById("remoteVideo");
  const startButton = document.getElementById("startButton");

  navigator.mediaDevices.getUserMedia({ video: true, audio: true })
    .then(stream => {
      localVideo.srcObject = stream;
      stream.getTracks().forEach(track => pc.addTrack(track, stream));
    });

  pc.onicecandidate = ({ candidate }) => {
    ws.send(JSON.stringify({ "ice-candidate": candidate }));
  };

  pc.ontrack = event => {
    remoteVideo.srcObject = event.streams[0];
  };

  startButton.addEventListener("click", () => {
    pc.createOffer()
      .then(offer => pc.setLocalDescription(offer))
      .then(() => {
        ws.send(JSON.stringify({ "offer": pc.localDescription }));
      });
  });

  ws.onmessage = message => {
    const msg = JSON.parse(message.data);

    if (msg["offer"]) {
      const remoteOffer = new RTCSessionDescription(msg["offer"]);
      pc.setRemoteDescription(remoteOffer).then(() => {
        return pc.createAnswer();
      })
      .then(answer => pc.setLocalDescription(answer))
      .then(() => {
        ws.send(JSON.stringify({ "answer": pc.localDescription }));
      });
    } else if (msg["answer"]) {
      const remoteAnswer = new RTCSessionDescription(msg["answer"]);
      pc.setRemoteDescription(remoteAnswer);
    } else if (msg["ice-candidate"]) {
      const iceCandidate = new RTCIceCandidate(msg["ice-candidate"]);
      pc.addIceCandidate(iceCandidate);
    }
  };
});
