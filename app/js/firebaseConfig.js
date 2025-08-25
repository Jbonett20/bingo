// firebaseConfig.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.1/firebase-app.js";
import {
  getFirestore,
  doc,
  setDoc,
  onSnapshot
} from "https://www.gstatic.com/firebasejs/10.12.1/firebase-firestore.js";

const firebaseConfig = {
  apiKey: "AIzaSyCoG-Gr0Ai0qoqABNuxiCLLsgl8nmWHiAg",
  authDomain: "appbingo-1c36b.firebaseapp.com",
  projectId: "appbingo-1c36b",
  storageBucket: "appbingo-1c36b.firebasestorage.app",
  messagingSenderId: "722084299800",
  appId: "1:722084299800:web:67f5fc86602bf7370b6f6d"
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
