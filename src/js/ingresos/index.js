import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Toast } from "../funciones";
import Swal from "sweetalert2";

// Declaración de variables
let contenedor = 1;
let contenedorr = 1;

//!Función Guardar
const guardarAPI = async (ing_id, asig_req_id) => {
    const url = `API/ingresos/guardar?ing_id=${ing_id}&asig_req_id=${asig_req_id}`;
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


// !Tabla de ingresos
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
            data: 'pue_nombre'
        },
        {
            title: 'CONTINGENTE A PARTICIPAR',
            data: 'cont_nombre'
        },
        {
            title: 'EVALUACIONES',
            data: 'id_aspirante',
            searchable: false,
            orderable: false,
            render: (data, type, row) => `<button class="btn btn-info ver-requisitos-btn" data-bs-toggle='modal' data-bs-target='#modalRequisito' data-ingpuesto='${data}' data-ingid='${row["ing_id"]}' data-nombre='${row["eva_nombre"]}'>Revisar Notas</button>`
        },
        {
            title: 'REQUISITOS',
            data: 'ing_puesto',
            searchable: false,
            orderable: false,
            render: (data, type, row) => `<button class="btn btn-info ver-requisitos-btn" data-bs-toggle='modal' data-bs-target='#modalRequisito' data-ingpuesto='${data}' data-ingid='${row["ing_id"]}' data-nombre='${row["eva_nombre"]}'>Validar Requisitos</button>`
        },
        {
            title: 'ELIMINAR',
            data: 'asig_req_id',
            searchable: false,
            orderable: false,
            render: (data) => `<button class="btn btn-success btn-aprobar-requisito" data-asig-req-id='${data}'>Aprobar Plaza</button>`
        }
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
            data: 'req_nombre'
        },
        {
            title: 'REQUISITOS',
            data: 'apro_situacion',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                if (type === 'display') {
                    if (data === null || data === "") {
                        return '<span style="color: red;">NO APROBADO</span>';
                    } else if (data === "1") {
                        // Si está aprobado, muestra el botón de "Desaprobar Requisito"
                        return '<span style="color: green;">APROBADO</span>';
                    } else if (data === "0") {
                        // Si apro_situacion es 0, muestra "NO APROBADO"
                        return '<span style="color: red;">NO APROBADO</span>';
                    } else {
                        return '<span style="color: orange;">PENDIENTE</span>';
                    }
                }
                return data;
            },
        },
        {
            title: 'APROBAR REQUISITO',
            data: 'ing_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                if (type === 'display') {
                    if (row.apro_situacion === "1") {
                        return `<button class="btn btn-danger btn-desaprobar-requisito" data-id="${row.apro_id}" data-ing-id="${data}" data-asig-req-id="${row.asig_req_id}">Desaprobar Requisito</button>`;
                    } else if(row.apro_situacion === "0"){
                        return `<button class="btn btn-success btn-reaprobar-requisito" data-id="${row.apro_id}">Aprobar Requisito</button>`;
                    } else {
                        return `<button class="btn btn-success btn-aprobar-requisito" data-ing-id="${data}" data-asig-req-id="${row.asig_req_id}">Aprobar Requisito</button>`;
                    }
                }
                return data;
            }
        }
    ]
});


// Agregar manejador de eventos para los botones "Ver Misiones"
$('#tablaIngesos').on('click', '.ver-requisitos-btn', function () {
    const ing_puesto = parseInt($(this).data('ingpuesto'));
    const ing_id = parseInt($(this).data('ingid'));
    buscarRequisitoPuestoAPI(ing_puesto, ing_id);
});

// Agregar manejador de eventos para el cierre del modal
$('#modalRequisito').on('hidden.bs.modal', function (e) {
    limpiar();
});

// Agregar manejador de eventos para los botones de aprobación de requisitos
$('.dataTable').on('click', '.btn-aprobar-requisito', function () {
    const asig_req_id = $(this).data('asig-req-id');
    const ing_id = $(this).data('ing-id');
    // Llamar a la función guardarAPI con los datos capturados
    guardarAPI(ing_id, asig_req_id);
    buscarRequisitoPuestoAPI(ing_puesto_global, ing_id_global);
});

// Función para buscar requisitos por puesto
const buscarRequisitoPuestoAPI = async (ing_puesto, ing_id) => {
    contenedor = 1;
    contenedorr = 1;

    const url = `API/ingresos/buscarRequisitoPuesto?ing_puesto=${ing_puesto}&ing_id=${ing_id}`;
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

// Función para buscar ingresos
const buscar = async () => {
    contenedor = 1;

    const url = `API/ingresos/buscar`;
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
let ing_id_global;
$('#tablaIngesos').on('click', '.ver-requisitos-btn', function () {
    ing_puesto_global = parseInt($(this).data('ingpuesto'));
    ing_id_global = parseInt($(this).data('ingid'));
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
        
        const url = `/dopaz/API/ingresos/desaprovar`;
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
                    buscarRequisitoPuestoAPI(ing_puesto_global, ing_id_global);
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
        
        const url = `/dopaz/API/ingresos/aprovar`;
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
                    buscarRequisitoPuestoAPI(ing_puesto_global, ing_id_global);
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

// Inicializar búsqueda al cargar la página
buscar();
