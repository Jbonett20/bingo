document.addEventListener("DOMContentLoaded", function () {
    const selectBingo = document.getElementById('selectBingo');
    const tablaJugadores = document.querySelector('#tablaJugadores tbody');
    const form = document.getElementById('form-bingo');

    // 1. Cargar bingos al select
    fetch('../controllers/bingo.php?action=listar')
        .then(res => res.json())
        .then(data => {
            data.forEach(b => {
                const option = document.createElement('option');
                option.value = b.id_bingo;
                option.textContent = b.nombre_bingo;
                selectBingo.appendChild(option);
            });
        });

    // 2. Evento al cambiar bingo
    selectBingo.addEventListener('change', function () {
        const bingo_id = this.value;
        if (!bingo_id) return;

        fetch(`../controllers/jugadores.php?action=listar&bingo_id=${bingo_id}`)
            .then(res => res.json())
            .then(jugadores => {
                tablaJugadores.innerHTML = '';
                jugadores.forEach(j => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${j.nombre}</td>
                        <td>${j.apellido}</td>
                         <td>${j.identificacion}</td>
                         <td>${j.telefono}</td>
                        <td>
                            <span class="badge ${j.pagado == 1 ? 'bg-success' : 'bg-danger'}">
                                ${j.pagado == 1 ? 'Pagado' : 'Pendiente'}
                            </span>
                        </td>
                        <td>
                            ${j.pagado == 1 ? '' : `<button class="btn btn-sm btn-primary pagar-btn" data-id="${j.id_user}">Pagar</button>`}
                        </td>
                    `;
                    tablaJugadores.appendChild(fila);
                });
            });
    });

    // 3. Evento al hacer clic en "Pagar"
    document.body.addEventListener('click', function (e) {
        if (e.target.classList.contains('pagar-btn')) {
            const usuario_id = e.target.dataset.id;
            const bingo_id = selectBingo.value;

            fetch('../controllers/jugadores.php?action=pagar', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `bingo_id=${bingo_id}&usuario_id=${usuario_id}`
            })
            .then(res => res.json())
            .then(r => {
                if (r.success) {
                    alert('Pago registrado exitosamente.');
                    selectBingo.dispatchEvent(new Event('change')); // Recargar tabla
                } else {
                    alert('Error al registrar el pago.');
                }
            });
        }
    });

    // 4. Registrar un nuevo bingo
    form.addEventListener('submit', e => {
        e.preventDefault();
        const payload = {
            nombre: form.nombre.value,
            valor: form.valor.value,
            fecha_juego: form.fecha_juego.value
        };

        fetch('../controllers/bingo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(response => {
            alert(response.message);
            if (response.status === 'success') {
                form.reset();
                location.reload(); // recargar bingos al select
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error de red o del servidor.');
        });
    });
});
