// firebaseConfig.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.1/firebase-app.js";
import {
  getFirestore,
  doc,
  setDoc,
  onSnapshot
} from "https://www.gstatic.com/firebasejs/10.12.1/firebase-firestore.js";

const firebaseConfig = {
  apiKey: "AIzaSyDdvhFfGY3bd5YC67SF92Cc8NEpVLirzZ4",
  authDomain: "bingoapp-bc32f.firebaseapp.com",
  projectId: "bingoapp-bc32f",
  storageBucket: "bingoapp-bc32f.firebasestorage.app",
  messagingSenderId: "489302159395",
  appId: "1:489302159395:web:ae5118d1bca34803440196"
};

const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
// Función para lanzar dados
async function lanzarDados(dado1, dado2, dado3,idBingo) {
  await setDoc(doc(db, "bingo", "lanzamientoActual"), {
    dado1,
    dado2,
    dado3,
    timestamp: Date.now(),
    bingoId: idBingo
  });
}


// Función para escuchar en tiempo real
function escucharLanzamiento(callback) {
  const ref = doc(db, "bingo", "lanzamientoActual");
  return onSnapshot(ref, (docSnap) => {
    if (docSnap.exists()) {
      callback(docSnap.data());
    }
  });
}

export { db, lanzarDados, escucharLanzamiento };
