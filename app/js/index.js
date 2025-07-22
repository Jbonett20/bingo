document.addEventListener("DOMContentLoaded", () => {
  getBingoAll();
});


document.getElementById("generarCarton").addEventListener("click", async () => {
 const bingoSelect = document.getElementById("bingoAll");
    if (!bingoSelect) {
      console.error("El elemento con ID 'bingoAll' no se encontró en el DOM.");
      return;
    }
    
    const bingoId = bingoSelect.value;


  const res = await fetch("../controllers/GenerarCartonController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ bingo_id: bingoId })
  });

  const data = await res.json();

  if (data.success) {
    const contenedor = document.getElementById("cartonGenerado");
    contenedor.innerHTML = `
      <div class="p-4 rounded" style="background-color: #d0e8f2; border: 1px solid #aaa; max-width: 500px;">
        <h4 class="text-center">BINGO 260</h4>
        <p>Sorteo #<strong>${data.sorteo_id}</strong> &nbsp;&nbsp; Cartón #<strong>${data.carton_id}</strong></p>
        <p>Fecha: <strong>${data.fecha}</strong></p>
       <p><em>Apuesta por línea:</em> $${data.valor.toFixed(2)}</p>
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
          sorteo_fecha: data.fecha
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
});


function getBingoAll() {
    fetch("../controllers/GetBingoAllController.php")
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById("bingoAll");
            if (!select) return;
            
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }

            select.innerHTML = data.map(bingo =>
                `<option value="${bingo.id}">${bingo.nombre}</option>`
            ).join('');
        })
        .catch(error => console.error("Error fetching bingo data:", error));
}




