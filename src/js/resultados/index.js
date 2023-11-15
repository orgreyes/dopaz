import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Toast } from "../funciones";
import Swal from "sweetalert2";

const btnCancelar = document.getElementById('btnCerrarIngresoNota');
const btnCerrar = document.getElementById('btnCerrar');

// Declaración de variables
let contenedor = 1;
let contenedorr = 1;

//!Función Guardar
const guardarAPI = async (id_aspirante, asig_req_id) => {
    const url = `API/resultados/guardar?id_aspirante=${id_aspirante}&asig_req_id=${asig_req_id}`;
    console.log(url);

    const config = {
        method: 'GET', // Cambiado de POST a GET
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        // Procesa la respuesta según tus necesidades
        const { codigo, mensaje, detalle } = data;
        let icon = 'info';
        switch (codigo) {
            case 1:
                icon = 'success';
                datatableRequisitos.clear().draw(); // Limpiar la tabla después de guardar
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
        console.error(error);
    }
};


// !Tabla de Ingresos
const datatableIngresos = new Datatable('#tablaIngesos', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'NO',
            render: () => contenedor++
        },
        {
            title: 'PUESTO SOLICITANTE',
            data: 'nombre_aspirante'
        },
        {
            title: 'EVALUACIONES',
            data: 'puesto_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => `<button class="btn btn-info ver-notas-btn" data-bs-toggle='modal' data-bs-target='#modalRequisito' data-puesto_id='${data}' data-id_aspirante='${row["id_aspirante"]}'>Evaluaciones a Calificar</button>`
        },
    ]
});

// !Tabla de requisitos
const datatableRequisitos = new Datatable('#tablaRequisitos', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'No.',
            render: () => contenedorr++
        },
        {
            title: 'REQUISITOS ASIGNADOS PARA EL PUESTO',
            data: 'nombre_evaluacion'
        },
        {
            title: 'EVALUACIONES',
            data: 'id_evaluacion',
            searchable: false,
            orderable: false,
            render: (data, type, row) => `<button class="btn btn-info ver-notas-btn" data-bs-toggle='modal' data-bs-target='#modalNota' data-puesto_id='${data}' data-id_aspirante='${row["id_aspirante"]}'>Ingreso de Notas</button>`
        },
    ]
});

let ing_puesto;
let id_aspirante;
// Agregar manejador de eventos para los botones "Ver Misiones"
$('#tablaIngesos').on('click', '.ver-notas-btn', function () {
    ing_puesto = parseInt($(this).data('puesto_id'));
    id_aspirante = parseInt($(this).data('id_aspirante'));
    buscarRequisitoPuestoAPI(ing_puesto, id_aspirante);
});


// Agregar manejador de eventos para los botones de aprobación de requisitos
$('.dataTable').on('click', '.btn-aprobar-requisito', function () {
    const asig_req_id = $(this).data('asig-req-id');
    const id_aspirante = $(this).data('ing-id');
    // Llamar a la función guardarAPI con los datos capturados
    guardarAPI(id_aspirante, asig_req_id);
    buscarRequisitoPuestoAPI(ing_puesto_global, id_aspirante_global);
});

// Función para buscar requisitos por puesto
const buscarRequisitoPuestoAPI = async (ing_puesto, id_aspirante) => {
    contenedor = 1;
    contenedorr = 1;

    const url = `API/resultados/buscarEvaluaciones?ing_puesto=${ing_puesto}&id_aspirante=${id_aspirante}`;
    console.log(url);

    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        if (respuesta.ok) {
            const data = await respuesta.json();
            console.log(data);
            datatableRequisitos.clear().draw();
            if (data) {
                datatableRequisitos.rows.add(data).draw();
            }
        } else {
            console.error('Error en la solicitud: ' + respuesta.status);
        }
    } catch (error) {
        console.error(error);
    }
};

//!Función para buscar resultados
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
        datatableIngresos.clear().draw();
        if (data) {
            datatableIngresos.rows.add(data).draw();
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
//?------------------------
let ing_puesto_global;
let id_aspirante_global;
$('#tablaIngesos').on('click', '.ver-notas-btn', function () {
    ing_puesto_global = parseInt($(this).data('puesto_id'));
    id_aspirante_global = parseInt($(this).data('id_aspirante'));
});
//?------------------------


//!Funcion desaprovar
const desaprobar = async e => {
    const result = await Swal.fire({
        icon: 'question',
        title: 'Desaprovar Requisito',
        text: '¿Desea Desaprovar este Requisito?',
        showCancelButton: true,
        confirmButtonText: 'Desaprovar',
        cancelButtonText: 'Cancelar'
    });
    
    const button = e.target;
    const id = button.dataset.id;

    if (result.isConfirmed) {
        const body = new FormData();
        body.append('apro_id', id);
        
        const url = `/dopaz/API/resultados/desaprovar`;
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
                    buscarRequisitoPuestoAPI(ing_puesto_global, id_aspirante_global);
                    Swal.fire({
                        icon: 'success',
                        title: 'Desaprovado Exitosamente',
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


//!Funcion aprovar
const aprovar = async e => {
    const result = await Swal.fire({
        icon: 'question',
        title: 'Aprovar Requisito',
        text: '¿Desea Aprovar este Requisito?',
        showCancelButton: true,
        confirmButtonText: 'Aprovar',
        cancelButtonText: 'Cancelar'
    });
    
    const button = e.target;
    const id = button.dataset.id;

    if (result.isConfirmed) {
        const body = new FormData();
        body.append('apro_id', id);
        
        const url = `/dopaz/API/resultados/aprovar`;
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
                    buscarRequisitoPuestoAPI(ing_puesto_global, id_aspirante_global);
                    Swal.fire({
                        icon: 'success',
                        title: 'Aprobado Exitosamente',
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

datatableRequisitos.on('click','.btn-desaprobar-requisito', desaprobar)
datatableRequisitos.on('click','.btn-reaprobar-requisito', aprovar)



//!---------------Modals Anidados Funcionales----------------!//
//?----------------------------------------------------------------------------------------------
let backdrop = null; // Variable global para almacenar el fondo oscuro
const Abrir_Modal = () => {
    // Obtén el modal mediante su id
    const modal = document.getElementById('modalRequisito');

    // Si ya existe un fondo oscuro, no hagas nada
    if (backdrop) {
        return;
    }

    // Agrega la clase 'show' al modal y quita la clase 'hide'
    modal.classList.add('show');
    modal.classList.remove('hide');

    // Asegúrate de que el modal sea visible cambiando su estilo
    modal.style.display = 'block';

    // Agrega la clase 'modal-open' al body para atenuar el fondo
    document.body.classList.add('modal-open');

    // Agrega el estilo al fondo oscuro (modal-backdrop)
    backdrop = document.createElement('div');
    backdrop.classList.add('modal-backdrop');
    backdrop.style.opacity = '0.5'; // Ajusta la opacidad según tus necesidades
    document.body.appendChild(backdrop);
};

const Cerrar_Modal = () => {
    const modal = document.getElementById('modalRequisito');

    // Quita la clase 'show' y agrega la clase 'hide' al modal
    modal.classList.remove('show');
    modal.classList.add('hide');

    // Asegúrate de que el modal no sea visible cambiando su estilo
    modal.style.display = 'none';

    // Quita la clase 'modal-open' del body para quitar la atenuación del fondo
    document.body.classList.remove('modal-open');

    // Elimina el fondo oscuro si existe
    if (backdrop) {
        document.body.removeChild(backdrop);
        backdrop = null; // Restablece la variable a null para que se pueda volver a crear
    }
};
btnCancelar.addEventListener('click', Abrir_Modal);
btnCerrar.addEventListener('click', Cerrar_Modal);
//?----------------------------------------------------------------------------------------------



buscar();
