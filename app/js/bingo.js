document.addEventListener("DOMContentLoaded", function () {

    const selectBingo = document.getElementById('selectBingo');
    // const tablaJugadores = document.querySelector('#tablaJugadores tbody');
    const form = document.getElementById('form-bingo');
    const selectBingoJuego = document.getElementById('selectBingoJuego');



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
            data.forEach(b => {
                const option = document.createElement('option');
                option.value = b.id_bingo;
                option.textContent = b.nombre_bingo;
                selectBingoJuego.appendChild(option);
            });
        });

    // Habilitar botón cuando se seleccione un bingo
    selectBingoJuego.addEventListener('change', () => {
        const idBingo = selectBingoJuego.value;
        if (idBingo !== '') {
            window.location.href = `jugar.php?id_bingo=${idBingo}`;
        }
    });



    // 2. Evento al cambiar bingo
selectBingo.addEventListener('change', function () {
    const bingo_id = this.value;
    if (!bingo_id) return;

    fetch(`../controllers/jugadores.php?action=listar&bingo_id=${bingo_id}`)
        .then(res => res.json())
        .then(jugadores => {
            // Destruir la instancia anterior si existe
            if ($.fn.DataTable.isDataTable('#tablaJugadores')) {
                $('#tablaJugadores').DataTable().clear().destroy();
            }

            // Limpiar el tbody
            $('#tablaJugadores tbody').empty();

            // Insertar filas manualmente
            jugadores.forEach(j => {
                const estado = j.pagado === 'Pagado' 
                    ? `<span class="badge bg-success">Pagado</span>`
                    : `<span class="badge bg-danger">Pendiente</span>`;

                const accion = j.pagado === 'Pagado'
                    ? '✔'
                    : `<button class="btn btn-sm btn-primary pagar-btn" data-id="${j.id_user}">Pagar</button>`;

                $('#tablaJugadores tbody').append(`
                    <tr>
                        <td>${j.nombre}</td>
                        <td>${j.apellido}</td>
                        <td>${j.identificacion}</td>
                        <td>${j.telefono}</td>
                        <td>${estado}</td>
                        <td>${accion}</td>
                    </tr>
                `);
            });

            // Inicializar DataTable en español
            $('#tablaJugadores').DataTable({
                pageLength: 10,
                lengthMenu: [10, 20, 40, 50],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
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
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `bingo_id=${bingo_id}&usuario_id=${usuario_id}`
        })
            .then(res => res.json())
            .then(r => {
                if (r.success) {
                    Swal.fire("Pago registrado exitosamente");
                    selectBingo.dispatchEvent(new Event('change')); // Recargar tabla
                } else {
                    Swal.fire('Error al registrar el pago.');

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
        link: form.link.value,
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
            Swal.fire(response.message).then(() => {
                if (response.status === 'success') {
                    form.reset();
                    location.reload();
                }
            });

        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error de red o del servidor.');
        });
});
});

