// Import the functions you need from the SDKs you need
import { firestore } from "./main.js";
import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/9.2.0/firebase-auth.js";

console.log(firestore);

// -----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----
// Register
//

function checkRegisterFormat(email, username, password1, password2) {
    // Email
    if (email === "") {
        alert('電子郵件必須填寫');
        InputEmail.focus();
        return false;
    }
    else if (!validateEmail(email)) {
        alert('電子郵件不符合格式，請重新輸入');
        InputEmail.focus();
        return false;
    }
    // Username
    if (username === "") {
        alert('使用者名稱必須填寫');
        InputUsername.focus();
        return false;
    }
    // Password
    if (password1 === "") {
        alert('密碼必須填寫');
        InputPassword1.focus();
        return false;
    }
    else if (password2 === "") {
        alert('密碼必須填寫');
        InputPassword2.focus();
        return false;
    }
    else if (!validatePassword(password1, password2)) {
        alert('密碼不相同，請重新輸入');
        InputPassword2.focus();
        return false;
    }

    return true;
}

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validatePassword(password1, password2) {
    return password1 == password2;    
}

const auth = getAuth();

export function registerUser() {
    const email = document.getElementById('InputEmail').value;
    const username = document.getElementById('InputUsername').value;
    const password1 = document.getElementById('InputPassword1').value;
    const password2 = document.getElementById('InputPassword2').value;


    if (checkRegisterFormat(email, username, password1, password2)) {
        console.log({email, username, password1, password2});

        createUserWithEmailAndPassword(auth, email, password1)
        .then((userCredential) => {
            // Signed in 
            const user = userCredential.user;

            console.log(user);
            // ...
        })
        .catch((error) => {
            const errorCode = error.code;
            const errorMessage = error.message;
            // ..
            console.log(errorCode, errorMessage);
        });
    }
}