import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Toast } from "../funciones";
import Swal from "sweetalert2";

//?------------------------------------------------------------
const btnGuardar = document.getElementById('btnGuardar');
const btnEliminar = document.getElementById('btnGuardar');
//?------------------------------------------------------------

let contenedor = 1;
const datatable = new Datatable('#tablaAsigResultados', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'NO',
            render: () => contenedor++
        },
        {
            title: 'Nombre del Aspirante',
            data: 'nombre_aspirante'
        },
        {
            title: 'MISIONES',
            data: 'id_aspirante',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => `<button class="btn btn-info ver-evaluaciones-btn" data-bs-toggle='modal' data-bs-target='#modalEvaluaciones' data-id='${data}'>Asignar Notas</button>`
        }
    ]
});

window.limpiar = () => {
    tablaresultados.clear();
    contenedorr = 1;
};

let contenedorr = 1;
let tablaresultados = new Datatable('#tablaresultados', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'No.',
            render: () => contenedorr++
        },
        {
            title: 'EVALUACIONES ASIGNADAS',
            data: 'mis_nombre'
        },
        {
            title: 'ASIGNAR NOTA',
            data: 'asig_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => `<button class="btn btn-danger" data-id='${data}'>Eliminar</button>`
        }
    ]
});

// Agregar un manejador de eventos para los botones "Ver Misiones"
$('#tablaAsigResultados').on('click', '.ver-evaluaciones-btn', function () {
    const id_aspirante = parseInt($(this).data('id')); // Convertir a entero
    buscarEvaluacionesAPI(id_aspirante);
});

// Agregar un manejador de eventos para el cierre del modal
$('#modalEvaluaciones').on('hidden.bs.modal', function (e) {
    // Restablecer el contador y limpiar la tabla de misiones cuando se cierra el modal
    limpiar();
});

const buscarEvaluacionesAPI = async (id_aspirante) => {
    const url = `API/resultados/buscarEvaluaciones?id_aspirante=${id_aspirante}`;
    const url2 = `API/resultados/guardar?id_aspirante=${id_aspirante}`;
    console.log(url,url2);
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url,url2, config);
        if (respuesta.ok) {
            const data = await respuesta.json();
            console.log(data);
            tablaresultados.clear().draw();
            if (data) {
                tablaresultados.rows.add(data).draw();
            }
        } else {
            console.error('Error en la solicitud: ' + respuesta.status);
        }
    } catch (error) {
        console.error(error);
    }
};

//!Función para buscar registros
const buscar = async () => {
    contenedor = 1;

    const url = `API/resultados/buscar`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        console.log(data);
        datatable.clear().draw();
        if (data) {
            datatable.rows.add(data).draw();
        } else {
            Toast.fire({
                title: 'No se encontraron registros',
                icon: 'info'
            });
        }
    } catch (error) {
        console.log(error);
    }
};

//!Función para guardar un registro
const guardar = async (evento) => {
    evento.preventDefault();
    if (!validarFormulario(formulario, ['res_id'])) {
        Toast.fire({
            icon: 'info',
            text: 'Debe llenar todos los datos'
        });
        return;
    }

    const body = new FormData(formulario);
    body.delete('res_id');
    const url = 'API/resultados/guardar';
    const headers = new Headers();
    headers.append("X-Requested-With", "fetch");
    const config = {
        method: 'POST',
        body
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        const { codigo, mensaje, detalle } = data;
        let icon = 'info';
        switch (codigo) {
            case 1:
                formulario.reset();
                icon = 'success';
                buscar();
                break;

            case 0:
                icon = 'error';
                console.log(detalle);
                break;

            default:
                break;
        }
        Toast.fire({
            icon,
            text: mensaje
        });
    } catch (error) {
        console.log(error);
    }
};

//! Función Eliminar
const eliminar = async (e) => {
    const result = await Swal.fire({
        icon: 'question',
        title: 'Eliminar Datos de Contingente',
        text: '¿Desea eliminar los Datos de este Contingente?',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    });

    const button = e.target;
    const id = button.dataset.id;

    if (result.isConfirmed) {
        const body = new FormData();
        body.append('asig_id', id);

        const url = `API/resultados/eliminar`;
        const config = {
            method: 'POST',
            body,
        };

        try {
            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            console.log(data);
            const { codigo, mensaje, detalle } = data;

            let icon = 'info';
            switch (codigo) {
                case 1:
                    buscarEvaluacionesAPI();
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado Exitosamente',
                        text: mensaje,
                        confirmButtonText: 'OK'
                    });
                    break;
                case 0:
                    console.log(detalle);
                    break;
                default:
                    break;
            }

        } catch (error) {
            console.log(error);
        }
    }
};

// --------------------------------------------------------------
// Bloque para mostrar/ocultar
// --------------------------------------------------------------

buscar();
// --------------------------------------------------------------
// btnBuscar.addEventListener('click', buscar)
// --------------------------------------------------------------
btnGuardar.addEventListener('click', guardar)
// --------------------------------------------------------------
// btnCancelar.addEventListener('click', cancelarAccion);
// --------------------------------------------------------------
// btnModificar.addEventListener('click', modificar);
// --------------------------------------------------------------
// tablaresultados.on('click','.btn-danger', eliminar)
// --------------------------------------------------------------
