import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { validarFormulario, Toast } from "../funciones";
import Swal from "sweetalert2";

const formulario = document.querySelector('form');
const btnFormulario = document.getElementById('btnFormulario');
const btnModificar = document.getElementById('btnModificar');
const btnGuardar = document.getElementById('btnGuardar');
const btnCerrar = document.getElementById('btnCerrar');
const btnBuscar = document.getElementById('btnBuscar');
const btnCancelar = document.getElementById('btnCancelar');
const tablaAsigMisionesContainer = document.getElementById('tablaAsigMisionesContainer');

let contenedor = 1;
let contenedorr = 1;

const datatable = new Datatable('#tablaAsigMisiones', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'NO',
            render: () => contenedor++
        },
        {
            title: 'asigmisiones',
            data: 'cont_nombre'
        },
        {
            title: 'MISIONES',
            data: 'cont_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => `<button class="btn btn-info ver-misiones-btn" data-bs-toggle='modal' data-bs-target='#modalMisiones' data-id='${data}'>Ver Misiones</button>`
        }
    ]
});

let tablaMisiones = new Datatable('#tablaMisiones', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'No.',
            render: () => contenedorr++
        },
        {
            title: 'MISIONES ASIGNADAS',
            data: 'mis_nombre'
        },
        {
            title: 'ELIMINAR',
            data: 'asig_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => `<button class="btn btn-danger" data-id='${data}'>Eliminar</button>`
        }
    ]
});


let cont_id;

$('#tablaAsigMisiones').on('click', '.ver-misiones-btn', function () {
    cont_id = parseInt($(this).data('id'));
    
});

// Agregar un manejador de eventos para el cierre del modal
$('#modalMisiones').on('hidden.bs.modal', function (e) {
    // Restablecer el contador y limpiar la tabla de misiones cuando se cierra el modal

});

const buscarMisionesContingenteAPI = async (cont_id) => {
    contenedor = 1;
    contenedorr = 1;
    const url = `API/asigmisiones/buscarMisionesContingente?cont_id=${cont_id}`;
    console.log(url);

    const config = {
        method: 'GET'
    };
    
    try {
        const respuesta = await fetch(url, config);
        if (respuesta.ok) {
            const data = await respuesta.json();
            console.log(data);
            tablaMisiones.clear().draw();
            if (data) {
                tablaMisiones.rows.add(data).draw();
            }
        } else {
            console.error('Error en la solicitud: ' + respuesta.status);
        }
    } catch (error) {
        console.error(error);
    }
};



// !Función para buscar registros
const buscar = async () => {
    contenedor = 1;

    const url = `API/asigmisiones/buscar`;
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
    if (!validarFormulario(formulario, ['asig_id'])) {
        Toast.fire({
            icon: 'info',
            text: 'Debe llenar todos los datos'
        });
        return;
    }

    const body = new FormData(formulario);
    body.delete('asig_id');
    const url = 'API/asigmisiones/guardar';
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



//?--------------------------------------------------------------

// //!Funcion Eliminar
const eliminar = async e => {
    const result = await Swal.fire({
        icon: 'question',
        title: 'Eliminar Datos de Contingente',
        text: '¿Desea eliminar los Datos de este Contingente?',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    });
    
    const button = e.target;
    const id = button.dataset.id
    // alert(id);
    
    if (result.isConfirmed) {
        const body = new FormData();
        body.append('asig_id', id);
        
        const url = `/dopaz/API/asigmisiones/eliminar`;
        const config = {
            method: 'POST',
            body,
        };
        
        try {
            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            console.log(data);
            const { codigo, mensaje, detalle } = data;

            let icon='info'
            switch (codigo) {
                case 1:
                    buscarMisionesContingenteAPI(cont_id);
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


//?--------------------------------------------------------------

//!Aca esta la funcion de modificar un registro
const modificar = async () => {
    const asig_id = formulario.asig_id.value;
    const body = new FormData(formulario);
    body.append('asig_id', asig_id);

    const url = `/dopaz/API/asigmisiones/modificar`;
    const config = {
        method: 'POST',
        body,
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);
        const { codigo, mensaje, detalle } = data;

        switch (codigo) {
            case 1:
                formulario.reset();
                cancelarAccion(); // Corrección aquí
                buscar();

                
                ocultarFormulario(); // Ocultar el formulario
                
                Toast.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: mensaje,
                    confirmButtonText: 'OK'
                });
                break;
            case 0:
                Swal.fire({
                    icon: 'info',
                    title: 'Campo Vacio',
                    text: mensaje,
                    confirmButtonText: 'OK'
                });
                break;
            default:
                break;
        }

    } catch (error) {
        console.log(error);
    }
};
//?--------------------------------------------------------------
//?block es mostrar 
//?none y ocultar

//!Ocultar el Datatable al inicio
formulario.style.display = 'block';
tablaAsigMisionesContainer.style.display = 'none'; 

//!Mostrar el formulario, ocultar datatable
const mostrarFormulario = () => {
    formulario.style.display = 'block';
    tablaAsigMisionesContainer.style.display = 'none'; 
    };

//!Ocultar el formulario, mostrar datatable
const ocultarFormulario = () => {
    // formulario.reset();
    formulario.style.display = 'none';
    tablaAsigMisionesContainer.style.display = 'block';
};

//?--------------------------------------------------------------

//!Ocultar el btnFormulario, mostrar btnBuscar al Inicio
btnFormulario.style.display = 'none';
btnBuscar.style.display = 'block';

//!Mostrar el btnFormulario, Ocultar btnBuscar
const ocultarBtnForumulario = () => {
    btnFormulario.style.display = 'block';
    btnBuscar.style.display = 'none';
};

//!Mostrar el btnFormulario, Ocultar btnBuscar
const MostrarBtnForumulario = () => {
    btnFormulario.style.display = 'none';
    btnBuscar.style.display = 'block';
};

//!Mostrar el btnFormulario, Ocultar btnBuscar
const OcultarTodoForumulario = () => {
    btnFormulario.style.display = 'none';
    btnBuscar.style.display = 'none';
};
//?--------------------------------------------------------------

//!Mostrar el btnGuardar, Ocultar los btnModificar y btnCancelar al Inicio
btnGuardar.style.display = 'block';
btnModificar.style.display = 'none';
btnCancelar.style.display = 'none';

//!Mostrar el btnGuardar, Ocultar los btnModificar y btnCancelar 
const ocultarBtns = () => {
    btnGuardar.style.display = 'block';
    btnModificar.style.display = 'none';
    btnCancelar.style.display = 'none';
};

//!Mostrar el btnFormulario, Ocultar btnBuscar
const mostrarBtns = () => {
    btnGuardar.style.display = 'none';
    btnModificar.style.display = 'block';
    btnCancelar.style.display = 'block';
};
//?--------------------------------------------------------------

//!Para colocar los datos sobre el formulario
const traeDatos = (e) => {
    const button = e.target;
    const id = button.dataset.id;
    const nombreContingente = button.dataset.nombreContingente;

    //! Llenar el formulario con los datos obtenidos
    formulario.asig_id.value = id;
    formulario.asig_contingente.value = nombreContingente;
};

//?--------------------------------------------------------------

//!Aca esta la funcino de cancelar la accion de modificar un registro.
const cancelarAccion = () => {
    formulario.reset();
    document.getElementById('tablaAsigMisionesContainer').style.display = 'block'; 
};


//?--------------------------------------------------------------
btnBuscar.addEventListener('click', buscar)
btnBuscar.addEventListener('click', ocultarFormulario)
btnBuscar.addEventListener('click', ocultarBtnForumulario)
btnBuscar.addEventListener('click', ocultarBtns)
//?--------------------------------------------------------------
btnGuardar.addEventListener('click', guardar)
//?--------------------------------------------------------------
btnFormulario.addEventListener('click', mostrarFormulario)
btnFormulario.addEventListener('click', MostrarBtnForumulario)
//?--------------------------------------------------------------
btnCancelar.addEventListener('click', ocultarFormulario);
btnCancelar.addEventListener('click', cancelarAccion);
btnCancelar.addEventListener('click', ocultarBtnForumulario);
btnCancelar.addEventListener('click', ocultarBtns);
//?--------------------------------------------------------------
btnModificar.addEventListener('click', modificar);
btnModificar.addEventListener('click', () => {
    setTimeout(() => {
        btnGuardar.style.display = 'block';
        btnModificar.style.display = 'none';
        btnCancelar.style.display = 'none';
    }, 1600);
});

btnModificar.addEventListener('click', () => {
    setTimeout(() => {
        btnFormulario.style.display = 'block';
        btnBuscar.style.display = 'none';
    }, 1200);
});


//?--------------------------------------------------------------
tablaMisiones.on('click','.btn-danger', eliminar)
//?--------------------------------------------------------------




