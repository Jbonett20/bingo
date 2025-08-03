
import { db, lanzarDados, escucharLanzamiento } from "./firebaseConfig.js"
let NumerosJugados=[];
document.addEventListener("DOMContentLoaded", () => {
    
    const diceContainer = document.getElementById("diceContainer");
    const params = new URLSearchParams(window.location.search);
    const idBingo = parseInt(params.get('id_bingo')) || 0;
    mostrarNumeroSorteado(idBingo)
    generarCarton(idBingo);
    iniciarCuentaRegresiva(idBingo);
    // Luego, actualizar en tiempo real cada 3 segundos
    setInterval(() => {
        mostrarNumeroSorteado(idBingo);
        verificarBingoGanado(idBingo)
    }, 3000);
    escucharLanzamiento((data) => {
        if (!data) return;

        const { dado1, dado2, dado3, bingoId } = data;

        let respNum = dado1 + dado2 + dado3;


        const diceElements = diceContainer.querySelectorAll(".dice");

        if (diceElements.length >= 3) {
            diceElements[0].textContent = getEmoji(dado1);
            diceElements[1].textContent = getEmoji(dado2);
            diceElements[2].textContent = getEmoji(dado3);
        }
        if (idBingo == bingoId) {
            cantarNumeroSorteado(respNum);
        }


    });

})
let audioDesbloqueado = false;
document.getElementById("iniciarJuego").addEventListener("click", (e) => {
    e.target.classList.add('d-none')
    let cardPrincipal = document.getElementById('cardPrincipal')
    let cardResultados = document.getElementById('cardResultados')
    cardResultados.classList.remove('d-none')

    cardPrincipal.classList.remove('d-none')
    const audio = new Audio('../audios/dados.mp3');
    audio.volume = 0; // sin sonido
    audio.play().then(() => {
        audioDesbloqueado = true;
        document.getElementById("iniciarJuego").style.display = "none";
    }).catch(err => {
        console.warn("Fallo al desbloquear el audio:", err);
    });
});
function getEmoji(valor) {
    const dados = ["", "⚀", "⚁", "⚂", "⚃", "⚄", "⚅"];
    return dados[valor] || "❓";
}
async function generarCarton(idBingo) {

    const res = await fetch("../controllers/GenerarCartonController.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ bingo_id: idBingo })
    });

    const data = await res.json();

    if (data.success) {
        console.log(data)
        const contenedor = document.getElementById("cartonGenerado");
        let direccion = (data.link) ? `<p> <a href=${data.link} a_target>link para entrar a la reunion</a></p>` : ''

        contenedor.innerHTML = `
      <div class="p-4 rounded" style="background-color: #081013ff; border: 1px solid #aaa; max-width: 500px;">
        <h4 class="text-center">BINGO</h4>
        <p>Frejeman.com</p>
        <input type="hidden" id="idBingo" value="${data.idBingo}">
        <p>Sorteo #<strong>${data.sorteo_id}</strong> &nbsp;&nbsp; Cartón #<strong>${data.carton_id}</strong></p>
        <p>Fecha: <strong>${data.fecha}</strong></p>
       <p><em>Apuesta por línea:</em> $${data.valor.toFixed(2)}</p>
       <p><em>Gana:</em> $${data.partida}</p>
         ${direccion}
       <p>
        <p style="font-size: 14px;">
        Tres dados virtuales. dieciseis números (3 al 18).
        el jugador que primero acierte los 5 números de la línea, en cualquier orden, gana
        <strong>65%</strong> del recaudo total.
        </p>

        <div id="cartonNumeros" class="d-flex gap-2 mt-3 justify-content-center ">
          <div id="cartonNumeros" class="mt-3">
            <div class="d-flex gap-2 justify-content-center mb-2">
                ${data.carton.slice(0, 4).map(num => `
                <div class="numero border p-3 rounded text-center numeroBingo" style="width: 60px; cursor: pointer; background-color: #000;">
                    ${num}
                </div>`).join('')}
            </div>
            <div class="d-flex gap-2 justify-content-center">
                ${data.carton.slice(4, 8).map(num => `
                <div class="numero border p-3 rounded text-center numeroBingo" style="width: 60px; cursor: pointer; background-color: #000;">
                    ${num}
                </div>`).join('')}
            </div>
             <div class="text-center mt-3">
          <button id="btnBingo" class="btn btn-success" disabled>¡Bingo!</button>
        </div>
        </div>`;

        const numeros = document.querySelectorAll(".numero");
        const btnBingo = document.getElementById("btnBingo");

        numeros.forEach(div => {
            
            div.addEventListener("click", () => {

              let numeroclick= parseInt(div.textContent);
              let numBool =  NumerosJugados.includes(numeroclick)
              if(numBool){
                div.classList.toggle("bg-danger");
                }
                div.classList.toggle("text-white");
                const todosSeleccionados = [...numeros].every(n => n.classList.contains("bg-danger"));
                btnBingo.disabled = !todosSeleccionados;
            });
        });
        btnBingo.addEventListener("click", async () => {
            const res = await fetch("../controllers/GuardarBingoController.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    carton_id: data.carton_id,
                    sorteo_fecha: data.fecha,
                    idBingo: idBingo
                })
            });

            const resultado = await res.json();

            if (resultado.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Bingo registrado con éxito!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resultado.message
                });
            }
        });
    } else {
        Swal.fire(data.message);
    }


}


function mostrarNumeroSorteado(idBingo) {
    fetch(`../controllers/jugar.php?action=ultimoSorteado&id_bingo=${idBingo}`)
        .then(res => res.json())
        .then(data => {
            const resultadoText = document.getElementById('resultadoText');
            resultadoText.innerHTML = '';

            if (Array.isArray(data) && data.length > 0) {
                data.carton.forEach(numero => {
                    const p = document.createElement('p');
                    p.textContent = `Número sorteado: ${numero}`;
                    resultadoText.appendChild(p);
                });
            } else if (data) {
               NumerosJugados=data.carton
                const p = document.createElement('p');
                p.textContent = `Número sorteado: ${data.carton || data}`;
                resultadoText.appendChild(p);
            } else {
                alert('No hay número sorteado para este bingo.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error al obtener el número sorteado.');
        });
}

function verificarBingoGanado(idBingo) {
    fetch(`../controllers/jugar.php?action=Bingoganado&id_bingo=${idBingo}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.ganador) {
                const nombre = data.ganador.nombre;
                const apellido = data.ganador.apellido;

                // Mostrar alerta en la vista
                const caja = document.getElementById('bingostart')
                caja.classList.remove('d-none');
                const resultadoText = document.getElementById('resultadoBingo');
                resultadoText.innerHTML = `<strong>🎉 ¡${nombre} ${apellido} ha ganado el Bingo! 🎉</strong>`;

            } else {
                console.log('No hay ganador aún.');
            }
        })
        .catch(err => {
            console.error('Error al verificar bingo ganado:', err);
        });
}
async function iniciarCuentaRegresiva(idBingo) {
    const res = await fetch(`../controllers/ObtenerFechaJuegoController.php?id_bingo=${idBingo}`);
    const data = await res.json();

    if (data.success) {
        const fechaJuego = new Date(data.fecha_juego); // formato: "YYYY-MM-DD HH:MM:SS"
        const cuentaElement = document.getElementById("cuentaRegresiva");
        let info = document.getElementById('info')
        let buton = document.getElementById('iniciarJuego')

        function actualizarReloj() {
            const ahora = new Date();
            const diferencia = fechaJuego - ahora;

            if (diferencia <= 0) {
                info.classList.add('d-none')
                buton.classList.remove('d-none')
                cuentaElement.textContent = "🎯 ¡Para comenzar presione iniciar juego!";
                clearInterval(intervalo);
                return;
            }

            const horas = Math.floor((diferencia / (1000 * 60 * 60)) % 24);
            const minutos = Math.floor((diferencia / (1000 * 60)) % 60);
            const segundos = Math.floor((diferencia / 1000) % 60);

            cuentaElement.textContent = `${horas}h ${minutos}m ${segundos}s`;
        }

        actualizarReloj();
        const intervalo = setInterval(actualizarReloj, 1000);
    } else {
        console.error("No se pudo obtener la fecha del sorteo:", data.message);
    }
}
function cantarNumeroSorteado(numero) {
    const audio = new Audio(`../audios/${numero}.mp3`);
    audio.play()
        .then(() => {
            console.log(`Audio del número ${numero} reproducido correctamente`);
        })
        .catch(error => {
            console.error(`Error al reproducir el audio del número ${numero}:`, error);
        });
}