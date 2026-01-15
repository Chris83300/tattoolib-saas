importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyBa0-WHttlZo184ylegIM_nPC9kv0tqF44",
  authDomain: "tattoolib-83300.firebaseapp.com",
  projectId: "tattoolib-83300",
  storageBucket: "tattoolib-83300.firebasestorage.app",
  messagingSenderId: "963411687844",
  appId: "1:963411687844:web:13879e0ec8a8c60d15e931"
});

const messaging = firebase.messaging();
messaging.getToken({
    vapidKey: 'BHJrySPatoJLDgi11i6Lcd4JJxtv2ZWFb1Gqd3KqWZcukSzkZNm1S46BoYwWsktrbFQ0Ktw6cRcnSbIqXknY5EY'
}).then((currentToken) => {
    if (currentToken) {
        console.log('Token FCM:', currentToken);
    } else {
        console.log('Aucun token disponible');
    }
}).catch((err) => {
    console.log('Erreur:', err);
});

messaging.onBackgroundMessage((payload) => {
  console.log('Message reçu en arrière-plan', payload);
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/images/icon-192x192.png'
  };

  return self.registration.showNotification(notificationTitle, notificationOptions);
});
