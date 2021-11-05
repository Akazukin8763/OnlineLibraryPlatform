// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.2.0/firebase-app.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/9.2.0/firebase-firestore.js";

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
// Initialize Firebase
const firebaseApp = initializeApp({
    apiKey: "AIzaSyByaSMEqW4_ZosfsVgDLSjYZR_FHkLbpWw",
    authDomain: "online-library-platform.firebaseapp.com",
    projectId: "online-library-platform",
    storageBucket: "online-library-platform.appspot.com",
    messagingSenderId: "1021031975005",
    appId: "1:1021031975005:web:6c7cb5c0b8f5fcdbb765b4",
    measurementId: "G-06PEYJPWQ9"
});

export const firestore = getFirestore();
//console.log(firestore);