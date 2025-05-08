  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/11.7.1/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.7.1/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyBULNvIcqVy7stubq2twq2DMH_MZfuxo30",
    authDomain: "skybyn.firebaseapp.com",
    projectId: "skybyn",
    storageBucket: "skybyn.firebasestorage.app",
    messagingSenderId: "214777449741",
    appId: "1:214777449741:web:add15b3ff97a898c617444",
    measurementId: "G-8B2RBVVR1S"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);