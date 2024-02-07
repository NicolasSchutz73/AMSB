importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyCxxKnWhC3mcOalpB-FCWJoA9Kg9jSCnPs",
    authDomain: "push-notification-56ed1.firebaseapp.com",
    projectId: "push-notification-56ed1",
    storageBucket: "push-notification-56ed1.appspot.com",
    messagingSenderId: "693422657082",
    appId: "1:693422657082:web:9998341db608b8d576af03",
    measurementId: "G-FJVMG71W9Q"
});

// Ajoutez ceci dans votre firebase-messaging-sw.js
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SHOW_NOTIFICATION') {
        showCustomNotification(event.data.title, event.data.body, event.data.icon);
    }
});


const messaging = firebase.messaging();

// Fonction pour afficher une notification personnalisée
function showCustomNotification(title, body, icon) {
    const notificationOptions = {
        body: body,
        icon: icon
    };
    return self.registration.showNotification(title, notificationOptions);
}

messaging.setBackgroundMessageHandler(function(payload) {
    console.log("[firebase-messaging-sw.js] Received background message ", payload);

    // Exemple de données de payload - vous pouvez personnaliser ces valeurs
    const notificationTitle = payload.data.title || "Default Title";
    const notificationBody = payload.data.body || "Default body";
    const notificationIcon = payload.data.icon || "/default-icon.png";

    // Appel de la fonction personnalisée avec les données du payload
    return showCustomNotification(notificationTitle, notificationBody, notificationIcon);
});
