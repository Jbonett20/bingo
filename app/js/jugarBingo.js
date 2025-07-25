document.addEventListener("DOMContentLoaded", function () {
    const lanzarBtn = document.getElementById('lanzarBtn');
    const diceContainer = document.getElementById('diceContainer');
    const params = new URLSearchParams(window.location.search);
    const idBingo = parseInt(params.get('id_bingo')) || 0;
    
// Luego, actualizar en tiempo real cada 3 segundos
    setInterval(() => {
        mostrarNumeroSorteado(idBingo);
        verificarBingoGanado(idBingo);
    }, 3000);
    
  const audioDados = new Audio('../audios/dados.mp3');
audioDados.loop = true; // Reproduce en bucle mientras giran los dados

lanzarBtn.addEventListener('click', () => {
    lanzarBtn.disabled = true;
    const dice = diceContainer.querySelectorAll('.dice');
    const dadoEmojis = ['⚀', '⚁', '⚂', '⚃', '⚄', '⚅'];

    // Reproducir audio de dados
    audioDados.currentTime = 0;
    audioDados.play().catch(err => {
        console.warn('No se pudo reproducir dados.mp3:', err);
    });

    // Mostrar 🎲 mientras giran
    let interval = setInterval(() => {
       dice.forEach(d => {
    d.textContent = Math.floor(Math.random() * 6) + 1;
});

    }, 80);

    setTimeout(() => {
        clearInterval(interval);
        audioDados.pause();
        audioDados.currentTime = 0;

        const valores = [1, 2, 3].map(() => Math.floor(Math.random() * 6));
        dice.forEach((d, i) => d.textContent = dadoEmojis[valores[i]]);

        const resultado = valores.reduce((a, b) => a + b + 1, 0); 
        cantarNumeroSorteado(resultado);

        let numerojugado = document.getElementById('resultadoText');
        numerojugado.textContent = `Número sorteado: ${resultado}`;

        fetch('../controllers/jugar.php?action=sorteado', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `bingo_id=${idBingo}&numero=${resultado}`
        });

        lanzarBtn.disabled = false;
    }, 2000);
});

});

function mostrarNumeroSorteado(idBingo) {
    fetch(`../controllers/jugar.php?action=ultimoSorteado&id_bingo=${idBingo}`)
        .then(res => res.json())
        .then(data => {
            console.log(data,'numeros sortaedos ');
            const resultadoText = document.getElementById('resultadoText');
            resultadoText.innerHTML = ''; 

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(numero => {
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

                // Deshabilitar el botón
                const lanzarBtn = document.getElementById('lanzarBtn');
                lanzarBtn.disabled = true;
            } else {
                console.log('No hay ganador aún.');
            }
        })
        .catch(err => {
            console.error('Error al verificar bingo ganado:', err);
        });
}




