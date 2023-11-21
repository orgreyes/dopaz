import $ from "jquery";
import "datatables.net-bs5";
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Toast } from "../funciones";
import Swal from "sweetalert2";
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
// Declaración de variables
let contenedor = 1;
let contenedorr = 1;
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//!Función Guardar
const guardarAPI = async (ing_id, asig_req_id) => {
    const url = `API/ingresos/guardar?ing_id=${ing_id}&asig_req_id=${asig_req_id}`;
    console.log(url);

    const config = {
        method: 'GET',
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        const { codigo, mensaje, detalle } = data;
        let icon = 'info';
        switch (codigo) {
            case 1:
                icon = 'success';
                datatableRequisitos.clear().draw(); 
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
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
let contenedornotas = 1;
let datatableNotas; // Declara la variable aquí

// Luego, donde recibes los datos de la búsqueda, actualiza la tabla de notas
const respuesta = await fetch('API/ingresos/buscarNotas');
const data = await respuesta.json();
console.log(data);

// Limpia y dibuja la tabla con los nuevos datos
if (data && data.length > 0) {
    // Obtén las claves de las propiedades dinámicas (Ingles, Matematica, etc.)
    const columnasDinamicas = Object.keys(data[0]).filter(columna => columna !== 'puesto_nombre' && columna !== 'ing_contingente');

    // Borra las columnas existentes y agrega las columnas dinámicas a la configuración de la tabla
    if (datatableNotas) {
        datatableNotas.clear().destroy();
    }

    // Agrega una columna para el promedio
    const columnas = [
        {
            title: 'NO',
            render: () => contenedornotas++
        },
        {
            title: 'Puesto',
            data: 'puesto_nombre'
        },
        ...columnasDinamicas.map(columna => ({
            title: columna,
            data: columna,
            render: function (data, type, row) {
                if (type === 'display' && (data === null || data === undefined || data === '')) {
                    return '<span class="nota-pendiente text-danger">NOTA PENDIENTE</span>';
                } else {
                    return data;
                }
            }
        })),
        {
            title: 'Promedio',
            data: null,
            render: function (data, type, row) {
                const notas = columnasDinamicas.map(columna => row[columna]).filter(valor => valor !== null && !isNaN(valor));
                const sum = notas.reduce((acc, nota) => acc + parseFloat(nota || 0), 0);
                const promedio = notas.length > 0 ? sum / notas.length : 0;
                return type === 'display' ? promedio.toFixed(2) : promedio;
            }
        },
        {
            title: 'APROVAR FASE 1',
            data: 'asig_req_id',
            searchable: false,
            orderable: false,
            render: (data) => `<button class="btn btn-success btn-aprobar-requisito" data-asig-req-id='${data}'>Aprobar fase 1</button>`
        }
    ];

    // Crea la tabla con las nuevas columnas
    datatableNotas = $('#tablaNotas').DataTable({
        language: lenguaje,
        data: data,
        columns: columnas
    });

    // Dibuja la tabla con las nuevas columnas
    datatableNotas.draw();
}
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>






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
            title: 'CODIGO UNICO',
            data: 'ing_codigo'
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
datatableRequisitos;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
// Inicializar búsqueda al cargar la página
buscar();
