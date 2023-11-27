import $ from "jquery";
import "datatables.net-bs5";
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Toast } from "../funciones";
import Swal from "sweetalert2";
//? ------------------------------------------------------------------------------------------>
const ContenedorbtnInicio = document.getElementsByName('ContenedorbtnInicio')[0];;
const btnFase1 = document.getElementsByName('btnFase1')[0];
const btnFase2 = document.getElementsByName('btnFase2')[0];
const btnRegresarFase1 = document.getElementById('btnRegresarFase1');
const btnRegresar = document.getElementById('btnRegresar');
const btnFaseFinal = document.getElementById('btnFaseFinal');
const containerBtn = document.getElementById('containerBtn');
const containerBtn2 = document.getElementById('containerBtn2');
const containerBtn3 = document.getElementById('containerBtn3');
const tablaSolicitudesContainer = document.getElementById('tablaSolicitudesContainer');
const tablaNotasContainer = document.getElementById('tablaNotasContainer');
const tablaIngresosContainer = document.getElementById('tablaIngresosContainer');
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
const limpiarBotones = () => {
    const contenedorBotones = document.getElementById('contenedorBotones');
    contenedorBotones.innerHTML = '';  // Elimina todo el contenido dentro del contenedor
};
const limpiarBotones2 = () => {
    const contenedorBotones = document.getElementById('contenedorBotones2');
    contenedorBotones.innerHTML = '';  // Elimina todo el contenido dentro del contenedor
};
const limpiarBotones3 = () => {
    const contenedorBotones = document.getElementById('contenedorBotones3');
    contenedorBotones.innerHTML = '';  // Elimina todo el contenido dentro del contenedor
};
//!Función para buscar al personal que solicita iniciar proceso de selección.
const buscarPuestosSolicitudes = async () => {
    const url = `API/ingresos/buscarPuestos`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        console.log(data);
        const contenedorBotones = document.getElementById('contenedorBotones'); // Cambia 'contenedorBotones' por el ID de tu contenedor en el formulario

        if (data && data.length > 0) {
            data.forEach(puesto => {
                const divBoton = document.createElement('div');
                divBoton.classList.add('col-md-3', 'mb-3'); // Clases de Bootstrap para columnas y margen inferior

                const boton = document.createElement('button');
                boton.textContent = puesto.puesto_nombre;
                boton.setAttribute('data-idpuesto', puesto.ing_puesto);
                boton.classList.add('btn', 'btn-primary', 'btn-block'); // Clases de Bootstrap para botones

                boton.addEventListener('click', async () => {
                    const ing_puesto = puesto.ing_puesto;
                    console.log(`Clic en el botón de ${puesto.puesto_nombre}. ing_puesto: ${ing_puesto}`);

                    // Realizar la solicitud a la API sin redirigir
                    const url = `API/ingresos/buscarSolicitudes?ing_puesto=${ing_puesto}`;
                    const config = {
                        method: 'GET'
                    };

                    try {
                        const respuesta = await fetch(url, config);
                        const data = await respuesta.json();

                        // Manejar la respuesta como desees
                        console.log('Respuesta de la API:', data);

                        // Aquí puedes agregar más lógica para trabajar con la respuesta, por ejemplo, mostrar datos en el mismo formulario.
                        if (data) {
                            // Limpia la tabla de solicitudes
                            datatableSolicitudes.clear();
                            contenedorsolicitudes = 1;
                            // Agrega las nuevas filas con los datos obtenidos de la API
                            datatableSolicitudes.rows.add(data).draw();
                        } else {
                            Toast.fire({
                                title: 'No se encontraron registros',
                                icon: 'info'
                            });
                        }
                    } catch (error) {
                        console.error('Error al realizar la solicitud:', error);
                    }
                });

                divBoton.appendChild(boton);
                contenedorBotones.appendChild(divBoton);
            });
        } else {
            const mensajeNoSolicitudes = document.createElement('p');
            mensajeNoSolicitudes.textContent = 'No se encuentran Solicitudes Actualmente';
            mensajeNoSolicitudes.style.color = 'red'; 
            mensajeNoSolicitudes.style.fontSize = '18px';  
            mensajeNoSolicitudes.style.fontWeight = 'bold'; 
            mensajeNoSolicitudes.style.textAlign = 'center'; 
        
            contenedorBotones.appendChild(mensajeNoSolicitudes);
        }
    } catch (error) {
        console.error('Error al buscar puestos:', error);
    }
};



                                        //!DATATABLES!\\
// !Tabla de Solicitudes
let contenedorsolicitudes = 1;
const datatableSolicitudes = new Datatable('#tablaSolicitudes', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'NO',
            render: () => contenedorsolicitudes++
        },
        {
            title: 'NOMBRE DE ASPIRANTE',
            data: 'nombre_aspirante'
        },
        {
            title: 'PUESTO SOLICITANTE',
            data: 'nombre_puesto'
        },
        {
            title: 'CONTINGENTE A PARTICIPAR',
            data: 'nombre_contingente'
        },
        {
            title: 'INICIAR PROCESO DE SELECCION',
            data: 'ing_id',
            searchable: false,
            orderable: false,
            render: (data) => `<button class="btn btn-info btn-iniciar-proceso" data-ing-id='${data}'>Iniciar Proceso</button>`

        }
    ]
}); 
// Agregar manejador de eventos para los botones de aprobación de requisitos
datatableSolicitudes.on('click', '.btn-iniciar-proceso', function () {
    const ing_id = $(this).data('ing-id').toString();
    // Llamar a la iniciarProcesoAPI con los datos capturados
    iniciarProcesoAPI(ing_id);
});

//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>  

let ingPuestoActual = null;
let datatableNotas = null;
let ingPuesto = null; 
const buscarPuestosNotas = async () => {
    const url = `API/ingresos/buscarPuestosNotas`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        console.log(data);
        const contenedorBotones = document.getElementById('contenedorBotones2');

        if (data && data.length > 0) {
            data.forEach(puesto => {
                const divBoton = document.createElement('div');
                divBoton.classList.add('col-md-3', 'mb-3');

                const boton = document.createElement('button');
                boton.textContent = puesto.puesto_nombre;
                boton.setAttribute('data-idpuesto', puesto.ing_puesto);
                boton.classList.add('btn', 'btn-success', 'btn-block');

                boton.addEventListener('click', async () => {
                    // Captura el ing_puesto seleccionado y almacénalo en la variable ingPuesto
                    ingPuesto = puesto.ing_puesto;

                    const ing_puesto = puesto.ing_puesto;
                    if (ing_puesto !== ingPuestoActual) {
                        limpiarDataTable();
                    }
                    console.log(`Clic en el botón de ${puesto.puesto_nombre}. ing_puesto: ${ing_puesto}`);
                    await inicializarDataTable(ing_puesto);
                    ingPuestoActual = ing_puesto;
                });

                divBoton.appendChild(boton);
                contenedorBotones.appendChild(divBoton);
            });
        } else {
            const mensajeNoSolicitudes = document.createElement('p');
            mensajeNoSolicitudes.textContent = 'No se encuentran Personal Para Revision de Notas';
            mensajeNoSolicitudes.style.color = 'red'; 
            mensajeNoSolicitudes.style.fontSize = '18px';  
            mensajeNoSolicitudes.style.fontWeight = 'bold'; 
            mensajeNoSolicitudes.style.textAlign = 'center'; 
        
            contenedorBotones.appendChild(mensajeNoSolicitudes);
        }
    } catch (error) {
        console.error('Error al buscar puestos:', error);
    }
};

const limpiarDataTable = () => {
    if (datatableNotas) {
        datatableNotas.clear().destroy();
        datatableNotas = null;
        $('#tablaNotas').empty();
    }
};

const inicializarDataTable = async (ing_puesto) => {
    const url = `API/ingresos/buscarNotas?ing_puesto=${ing_puesto}`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);

        let contenedornotas = 1;

        if (data && data.length > 0) {
            limpiarDataTable();


            const columnasDinamicas = Object.keys(data[0]).filter(columna => columna !== 'puesto_nombre' && columna !== 'ing_contingente' && columna !== 'ing_id' && columna !== 'ing_codigo');

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
                // Verificar si es un número y si es menor a 60
                if (!isNaN(data) && parseFloat(data) < 60) {
                    return '<span style="color: red;">' + data + '</span>';
                } else {
                    return data;
                }
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
        data: 'ing_id',
        searchable: false,
        orderable: false,
        render: function (data, type, row) {
            const notasPendientes = columnasDinamicas.some(columna => row[columna] === null || row[columna] === undefined || row[columna] === '');

            if (notasPendientes) {
                // Si hay al menos una "NOTA PENDIENTE", mostrar el botón de "PENDIENTE EN INGRESAR NOTAS"
                return '<button class="btn btn-warning btn-pendiente-ingresar-notas">Pendiente a Ingresar Notas</button>';
            } else {
                // Si no hay "NOTA PENDIENTE", mostrar el botón de "APROBAR FASE 1"
                return `<button class="btn btn-success btn-aprobar-fase1" data-ing-id='${data}'>Aprobar fase 1</button>`;
            }
        }
    }
 ];

            datatableNotas = $('#tablaNotas').DataTable({
                data: data,
                columns: columnas
            });
            datatableNotas.draw();
        }
    } catch (error) {
        console.error('Error al buscar notas:', error);
    }
};

let ing_id;
// Agregar manejador de eventos para los botones de aprobación de requisitos
$('#tablaNotas').on('click', '.btn-aprobar-fase1', function () {
    ing_id = $(this).data('ing-id').toString();
    // Llamar a la iniciarProcesoAPI con los datos capturados
    seleccionPorNota(ing_id);
});

//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//!Función para buscar al personal que solicita iniciar proceso de selección.
const buscarPuestosRequisitos = async () => {
    const url = `API/ingresos/buscarPuestosRequisitos`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        console.log(data);
        const contenedorBotones = document.getElementById('contenedorBotones3'); // Cambia 'contenedorBotones' por el ID de tu contenedor en el formulario

        if (data !== null && data.length > 0) {
            data.forEach(puesto => {
                const divBoton = document.createElement('div');
                divBoton.classList.add('col-md-3', 'mb-3'); // Clases de Bootstrap para columnas y margen inferior

                const boton = document.createElement('button');
                boton.textContent = puesto.puesto_nombre;
                boton.setAttribute('data-idpuesto', puesto.ing_puesto);
                boton.classList.add('btn', 'btn-success', 'btn-block'); // Clases de Bootstrap para botones

                boton.addEventListener('click', async () => {
                    const ing_puesto = puesto.ing_puesto;
                    console.log(`Clic en el botón de ${puesto.puesto_nombre}. ing_puesto: ${ing_puesto}`);

                    // Realizar la solicitud a la API sin redirigir
                    const url = `API/ingresos/buscarRequisitosPorPuesto?ing_puesto=${ing_puesto}`;
                    const config = {
                        method: 'GET'
                    };

                    try {
                        const respuesta = await fetch(url, config);
                        const data = await respuesta.json();

                        // Manejar la respuesta como desees
                        console.log('Respuesta de la API:', data);

                        // Aquí puedes agregar más lógica para trabajar con la respuesta, por ejemplo, mostrar datos en el mismo formulario.
                        if (data) {
                            // Limpia la tabla de solicitudes
                            datatableIngresos.clear();
                            contenedor = 1;
                            // Agrega las nuevas filas con los datos obtenidos de la API
                            datatableIngresos.rows.add(data).draw();
                        } else {
                            Toast.fire({
                                title: 'No se encontraron registros',
                                icon: 'info'
                            });
                        }
                    } catch (error) {
                        console.error('Error al realizar la solicitud:', error);
                    }
                });

                divBoton.appendChild(boton);
                contenedorBotones.appendChild(divBoton);
            });
        } else {
            const mensajeNoSolicitudes = document.createElement('p');
            mensajeNoSolicitudes.textContent = 'No se encuentran Personal Para Revision de Documentacion';
            mensajeNoSolicitudes.style.color = 'red'; 
            mensajeNoSolicitudes.style.fontSize = '18px';  
            mensajeNoSolicitudes.style.fontWeight = 'bold'; 
            mensajeNoSolicitudes.style.textAlign = 'center'; 
        
            contenedorBotones.appendChild(mensajeNoSolicitudes);
        }
    } catch (error) {
        console.error('Error al buscar puestos:', error);
    }
};

let contenedor = 1;
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
            title: 'DOCUMENTACION',
            data: 'ing_puesto',
            searchable: false,
            orderable: false,
            render: (data, type, row) => `<button class="btn btn-info ver-documentacion-btn" data-bs-toggle='modal' data-bs-target='#modalDocumentacion' data-ingid='${row["ing_id"]}' data-nombre='${row["eva_nombre"]}'>Ver Documentacion</button>`
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
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
// !Tabla de para Revisar la Documentacion dentro del modal
let contenedorrr = 1;
const datatableDocumentacion = new Datatable('#tablaDocumentos', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'No.',
            render: () => contenedorrr++
        },
        {
            title: 'Documentacion Del Aspirante',
            data: 'nombre_pdf',
            render: function (data, type, row) {
                if (typeof data === 'string' && data.includes('/')) {
                    const partes = data.split('/');
                    const nombreArchivo = partes[partes.length - 1];
                    const nombreSinCodigo = nombreArchivo.replace(/\.\w{16}\.pdf$/, '');
                    return nombreSinCodigo;
                }
                return data;
            }
        },
        {
            title: 'PDF',
            className: 'text-center',
            data: 'nombre_pdf',
            render: function (data) {
                return `<button  class="btn btn-outline-info" data-ruta="${data.substr(10)}"><i class="bi bi-eye"></i>Ver PDF</button>`;
            },
            width: '150px'
        },
    ]
});
const verPDF = (e) => {

    const boton = e.target
    let ruta = boton.dataset.ruta

    let pdf = (ruta);
console.log(pdf)

    window.open(`API/ingresos/pdf?ruta=${pdf}`)

}
// !Tabla de para aprovar requisitos dentro del modal
let contenedorr = 1;
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
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
                                    //!CAPTURA DE DATOS!\\

//!Aca se Capturan los Id de las DataTables, necesarios para correro ciertos querys en el controlador.
// Agregar manejador de eventos para los botones "Ver Misiones"
$('#tablaIngesos').on('click', '.ver-documentacion-btn', function () {
    const ing_id = parseInt($(this).data('ingid'));
    buscarDocumentacionAPI(ing_id);
});
// Agregar manejador de eventos para el cierre del modal
$('#modalDocumentacion').on('hidden.bs.modal', function (e) {
});



//!Aca se Capturan los Id de las DataTables, necesarios para correro ciertos querys en el controlador.
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
let ing_puesto_global;
let ing_id_global;
$('#tablaIngesos').on('click', '.ver-requisitos-btn', function () {
    ing_puesto_global = parseInt($(this).data('ingpuesto'));
    ing_id_global = parseInt($(this).data('ingid'));
});
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>

                                        //!FUNCIONES!//

//!Función para buscar requisitos por puesto
const buscarDocumentacionAPI = async (ing_id) => {
    contenedorrr = 1;
    const url = `API/ingresos/buscarDocumentacion?ing_id=${ing_id}`;
    console.log(url);

    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        if (respuesta.ok) {
            const data = await respuesta.json();
            console.log(data);
            datatableDocumentacion.clear().draw();
            if (data) {
                datatableDocumentacion.rows.add(data).draw();
            }
        } else {
            console.error('Error en la solicitud: ' + respuesta.status);
        }
    } catch (error) {
        console.error(error);
    }
};
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//!Función para buscar al personal que solicita iniciar proceso de seleccion.
let contadorBuscarTodo = 1;
const buscarTodo = async () => {
    contadorBuscarTodo = 1;
    const url = `API/ingresos/buscarTodo`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        console.log(data);
        datatableSolicitudes.clear().draw();
        if (data) {
            // Actualizar la tabla de solicitudes con los nuevos datos
            datatableSolicitudes.rows.add(data).draw();
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
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//!Función para buscar al personal antes de buscar los requisitos.
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
        };

    } catch (error) {
        console.log(error);
    }
};
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//!Función para buscar requisitos por puesto
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
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//!Función Para Aprovar un requisito por primera Vez.
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
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
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
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//!Funcion inciar el Proceso de seleccion
const iniciarProcesoAPI = async (ing_id) => {
    // Verificar si ing_id es un número válido
    if (!isNaN(ing_id) && Number.isInteger(parseFloat(ing_id))) {
        // Convertir ing_id a entero si es necesario
        ing_id = parseInt(ing_id);
        
        // Construir la URL
        const url = `API/ingresos/iniciarProceso?ing_id=${ing_id}`;
        console.log(url);

        const config = {
            method: 'GET',
            // Puedes omitir el cuerpo ya que es una solicitud GET
        };

        try {
            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            console.log(data);

            const { codigo, mensaje, detalle } = data;

            let icon = 'info';
            switch (codigo) {
                case 1:
                    limpiarBotones();
                    buscarPuestosSolicitudes();
                    limpiarBotones2();
                    buscarPuestosNotas();
                    Toast.fire({
                        icon: 'success',
                        title: 'Proceso iniciado exitosamente',
                        text: mensaje
                    });
                    break;
                case 0:
                    console.log(detalle);
                    break;
                default:
                    break;
            }
        } catch (error) {
            console.error(error);
        }
    } else {
        console.error('ing_id no es un número válido:', ing_id);
    }
};

//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
//!Funcion inciar el Proceso de seleccion
const seleccionPorNota = async (ing_id) => {
    // Verificar si ing_id es un número válido
    if (!isNaN(ing_id) && Number.isInteger(parseFloat(ing_id))) {
        // Convertir ing_id a entero si es necesario
        ing_id = parseInt(ing_id);
        
        // Construir la URL
        const url = `API/ingresos/seleccionPorNota?ing_id=${ing_id}`;
        console.log(url);

        const config = {
            method: 'GET',
            // Puedes omitir el cuerpo ya que es una solicitud GET
        };

        try {
            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            console.log(data);

            const { codigo, mensaje, detalle } = data;

            let icon = 'info';
            switch (codigo) {
                case 1:
                    buscar();
                    limpiarBotones2();
                    buscarPuestosNotas();
                    inicializarDataTable(ingPuesto);
                    Toast.fire({
                        icon: 'success',
                        title: 'Proceso iniciado exitosamente',
                        text: mensaje
                    });
                    break;
                case 0:
                    console.log(detalle);
                    break;
                default:
                    break;
            }
        } catch (error) {
            console.error(error);
        }
    } else {
        console.error('ing_id no es un número válido:', ing_id);
    }
};
//? ------------------------------------------------------------------------------------------>
//!Ocultar el Datatables al inicio
tablaNotasContainer.style.display = 'none'; 
tablaIngresosContainer.style.display = 'none'; 
btnFase1.style.display = 'none'; 
btnFase2.style.display = 'none'; 
containerBtn2.style.display ='none';
containerBtn3.style.display ='none';

//!Ocultar la primer datatable y oculta el btnIniciarProceso
const mostrarFase1 = () => {
    containerBtn.style.display ='none';
    tablaSolicitudesContainer.style.display ='none';
    ContenedorbtnInicio.style.display = 'none';
    containerBtn2.style.display ='block';
    btnFase1.style.display = 'block';
    tablaNotasContainer.style.display = 'block';
};

//!Ocultar la primer datatable y oculta el btnIniciarProceso
const mostrarFaseInicio = () => {
    containerBtn.style.display ='block';
    tablaSolicitudesContainer.style.display ='block';
    ContenedorbtnInicio.style.display = 'block';
    containerBtn2.style.display ='none';
    btnFase1.style.display = 'none';
    tablaNotasContainer.style.display = 'none';
};

//!Mostrar Fase Final
const mostrarFaseFinal = () => {
    tablaNotasContainer.style.display ='none';
    btnFase1.style.display = 'none';
    containerBtn3.style.display ='block';
    tablaIngresosContainer.style.display = 'block'; 
    btnFase2.style.display = 'block';
    containerBtn2.style.display ='none';
};

//!Ocultar la primer datatable y oculta el btnIniciarProceso
const mostrarfase1 = () => {
    tablaNotasContainer.style.display ='block';
    btnFase1.style.display = 'block';
    containerBtn2.style.display ='block';
    containerBtn3.style.display ='none';
    tablaIngresosContainer.style.display = 'none'; 
    btnFase2.style.display = 'none';
};
//? ------------------------------------------------------------------------------------------>
//? ------------------------------------------------------------------------------------------>
datatableRequisitos.on('click','.btn-desaprobar-requisito', desaprobar)
datatableRequisitos.on('click','.btn-reaprobar-requisito', aprovar)
datatableRequisitos;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
// Inicializar búsqueda al cargar la página
datatableSolicitudes.on('click','.btn-iniciar-proceso', function () {
    const ing_id = $(this).data('ing-id');
    
    // Verificar si ing_id es un número válido
    if (!isNaN(ing_id) && Number.isInteger(parseFloat(ing_id))) {
        // Convertir ing_id a entero si es necesario
        const ing_id_int = parseInt(ing_id);
        
        // Llamar a la iniciarProcesoAPI con los datos capturados
    } else {
        console.error('ing_id no es un número válido:', ing_id);
    }
});



btnRegresarFase1.addEventListener('click', mostrarfase1)
btnFaseFinal.addEventListener('click', mostrarFaseFinal)
btnRegresar.addEventListener('click', mostrarFaseInicio)
btnInicio.addEventListener('click', mostrarFase1)
datatableDocumentacion.on('click', '.btn-outline-info', verPDF);

const ejecutarBusquedasSecuenciales = async () => {
    try {
        await buscarPuestosSolicitudes();
        await buscarTodo();
        await buscarPuestosNotas();
        await buscarPuestosRequisitos();
        await buscar();
    } catch (error) {
        console.error('Error en las búsquedas secuenciales:', error);
    }
};

// Luego puedes llamar a la función principal
ejecutarBusquedasSecuenciales();

