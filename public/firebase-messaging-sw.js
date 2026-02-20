importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

const firebaseConfig = {
    apiKey: "AIzaSyCbpTs5h9uXdui-o32rIyxv81cylLQO87s",
    authDomain: "desain-interior-web.firebaseapp.com",
    projectId: "desain-interior-web",
    storageBucket: "desain-interior-web.firebasestorage.app",
    messagingSenderId: "653684993043",
    appId: "1:653684993043:web:0fddc488cefd8b5f5623e6",
    measurementId: "G-7THYGZSC57"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

self.addEventListener('push', function(event) {
    const payload = event.data.json();
    
    console.log('[SW] Push event diterima:', payload);

    const notificationData = payload.data; 

    const notificationTitle = notificationData.title;
    const notificationOptions = {
        body: notificationData.body,
        icon: notificationData.icon,
        data: notificationData,
    };

    event.waitUntil(
        self.registration.showNotification(notificationTitle, notificationOptions)
    );
});

self.addEventListener('notificationclick', function(event) {
    console.log('[SW] Notifikasi diklik:', event.notification);
    
    event.notification.close();
    
    const urlToOpen = event.notification.data.click_action;
    
    if (urlToOpen) {
        event.waitUntil(clients.openWindow(urlToOpen));
    }
});