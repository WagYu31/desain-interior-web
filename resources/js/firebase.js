import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import axios from 'axios';

const firebaseConfig = {
    apiKey: "AIzaSyCbpTs5h9uXdui-o32rIyxv81cylLQO87s",
    authDomain: "desain-interior-web.firebaseapp.com",
    projectId: "desain-interior-web",
    storageBucket: "desain-interior-web.firebasestorage.app",
    messagingSenderId: "653684993043",
    appId: "1:653684993043:web:0fddc488cefd8b5f5623e6",
    measurementId: "G-7THYGZSC57"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

function sendTokenToServer(token) {
    axios.post('/api/fcm-token', { token: token })
        .then(response => console.log('Token FCM berhasil dikirim ke server.'))
        .catch(error => console.error('Gagal mengirim token FCM ke server: ', error));
}

// =================================================================
// PERBAIKAN DI SINI: Pastikan ada kata kunci "export"
// =================================================================
export function requestFirebasePermission() {
    console.log('Meminta izin notifikasi...');
    Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
            console.log('Izin notifikasi diberikan.');

            // Ganti dengan VAPID key dari Firebase Console Anda
            const vapidKey = 'BGdcGdxjsTir2hEZ6mUNq49_qPVVEE6Z3b8WcRaetJmcq4ajZTe5Fw9wkmDxl7nKp2lIRCkMPBJmGq-pl2jxDPE';

            getToken(messaging, { vapidKey: vapidKey }).then((currentToken) => {
                if (currentToken) {
                    console.log('Token FCM Diterima:', currentToken);
                    sendTokenToServer(currentToken);
                } else {
                    console.log('Gagal mendapatkan token registrasi.');
                }
            }).catch((err) => {
                console.error('Terjadi error saat mengambil token. ', err);
            });
        } else {
            console.log('Tidak dapat izin untuk notifikasi.');
        }
    });
}

onMessage(messaging, (payload) => {
    console.log('%cFCM Foreground Message DITERIMA!', 'color: green; font-weight: bold;', payload);
    document.dispatchEvent(new CustomEvent('fcm-foreground-message', {
        detail: payload
    }));
});