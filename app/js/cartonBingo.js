document.addEventListener("DOMContentLoaded", () => {

    const params = new URLSearchParams(window.location.search);
    const idBingo = parseInt(params.get('id_bingo')) || 0;
    mostrarNumeroSorteado(idBingo)
    generarCarton(idBingo);
    // Luego, actualizar en tiempo real cada 3 segundos
    setInterval(() => {
        mostrarNumeroSorteado(idBingo);
        verificarBingoGanado(idBingo)
    }, 3000); 
})
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
        const contenedor = document.getElementById("cartonGenerado");
        let direccion = (data.link) ? `<p> <a href=${data.link} a_target>link para entrar a la reunion</a></p>`:''
        
        contenedor.innerHTML = `
      <div class="p-4 rounded" style="background-color: #d0e8f2; border: 1px solid #aaa; max-width: 500px;">
        <h4 class="text-center">BINGO 260</h4>
        <input type="hidden" id="idBingo" value="${data.idBingo}">
        <p>Sorteo #<strong>${data.sorteo_id}</strong> &nbsp;&nbsp; Cartón #<strong>${data.carton_id}</strong></p>
        <p>Fecha: <strong>${data.fecha}</strong></p>
       <p><em>Apuesta por línea:</em> $${data.valor.toFixed(2)}</p>
         ${direccion}
       <p>
        <p style="font-size: 14px;">
          Por cada lanzamiento al aire de tres dados se obtiene un número de puntos, del 3 al 18. De todos los cartones de una línea,
          el jugador que primero acierte los 5 números de la línea, en cualquier orden, gana
          <strong>doscientos sesenta</strong> veces el valor de la apuesta por línea.
        </p>

        <div id="cartonNumeros" class="d-flex gap-2 mt-3 justify-content-center">
          ${data.carton.map(num => `
            <div class="numero border p-3 rounded text-center" style="width: 60px; cursor: pointer; background-color: #fff;">
              ${num}
            </div>`).join('')}
        </div>

        <div class="text-center mt-3">
          <button id="btnBingo" class="btn btn-success" disabled>¡Bingo!</button>
        </div>
      </div>
    `;

        const numeros = document.querySelectorAll(".numero");
        const btnBingo = document.getElementById("btnBingo");

        numeros.forEach(div => {
            div.addEventListener("click", () => {
                div.classList.toggle("bg-danger");
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
                    idBingo:idBingo
                })
            });

            const resultado = await res.json();
            if (resultado.success) {
                alert("¡Bingo registrado con éxito!");
            } else {
                alert("Error: " + resultado.message);
            }
        });

    } else {
        alert(data.message);
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