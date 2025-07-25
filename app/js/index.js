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
    window.location.href = `../views/cartonBingo.php?id_bingo=${bingoId}`;

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


