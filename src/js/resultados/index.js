import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Toast , validarFormulario } from "../funciones";
import Swal from "sweetalert2";

const formulario = document.querySelector('form');
const tablaNotasContainer = document.getElementById('tablaNotasContainer');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');


//?------------------------------------------------------------------------------------------------
// !Tabla de Ingresos
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
            data: 'nombre_aspirante'
        },
        {
            title: 'EVALUACIONES',
            data: 'pue_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => `<button class="btn btn-info ver-notas-btn" data-bs-toggle='modal' data-bs-target='#modalRequisito' data-pue_id='${data}'  data-asp_id='${row["asp_id"]}'>Evaluaciones a Calificar</button>`
        },
    ]
});
//!Aca se capturan los Ids. De La #tablaIngresos
$('#tablaIngesos').on('click', '.ver-notas-btn', function () {
    pue_id = parseInt($(this).data('pue_id'));
    asp_id = parseInt($(this).data('asp_id'));
    buscarEvaluacionesAPI(pue_id, asp_id);
});
let pue_id;
let asp_id;
//?------------------------------------------------------------------------------------------------


let contenedorr = 1;
// !Tabla Para ingreso de Notas
const datatableRequisitos = new Datatable('#tablaRequisitos', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'No.',
            render: () => contenedorr++
        },
        {
            title: 'EVALUACION',
            data: 'eva_nombre'
        },
        {
            title: 'NOTA ACTUAL',
            data: 'res_nota',
            render: (data, type, row) => {
                if (type === 'display' && (data === null || data === '')) {
                    return '<span style="color: red;">NOTA PENDIENTE</span>';
                }
                return data;
            },
        },
        {
            title: 'ACCIONES',
            data: 'eva_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                if (row['res_nota'] !== null && row['res_nota'] !== '') {
                    // Si ya tiene una nota, mostrar el botón de Modificación de Nota
                    return `<button class="btn btn-warning modificar-nota-btn" data-id='${data}' data-nombre='${row["eva_nombre"]}'data-res_nota='${row["res_nota"]}' data-res_id='${row["res_id"]}' data-ing_id='${row["ing_id"]}'>Modificación de Nota</button>`;
                } else {
                    // Si no tiene nota, mostrar el botón de Ingreso de Notas
                    return `<button class="btn btn-info ver-calificaiones-btn" data-id='${data}' data-nombre='${row["eva_nombre"]}'data-res_nota='${row["res_nota"]}' data-res_id='${row["res_id"]}' data-ing_id='${row["ing_id"]}'>Ingreso de Nota</button>`;
                }
            },
        },
    ]
});


//!Aca se capturan los Ids. De La #tablaRequisitos
$('#tablaRequisitos').on('click', '.ver-calificaiones-btn', function () {
    eva_id = parseInt($(this).data('id'));
    ing_id = parseInt($(this).data('ing_id'));
});
let eva_id;
let ing_id;


//!Para colocar los datos sobre el formulario
const traeDatos = (e) => {
    const button = e.target;
    const id = button.dataset.id;
    const nombre = button.dataset.nombre;
    const ing_id = button.dataset.ing_id;
    const res_id = button.dataset.res_id;
    const res_nota = button.dataset.res_nota;

//?--------------------------------------------------------------

//! Llenar el formulario con los datos obtenidos
    formulario.eva_id.value = id;
    formulario.eva_nombre.value = nombre;
    formulario.ing_id.value = ing_id;
    formulario.res_id.value = res_id;
    formulario.res_nota.value = res_nota;
};

datatableRequisitos.on('click','.ver-calificaiones-btn', traeDatos)


//?------------------------------------------------------------------------------------------------

//!Funcion Guardar
const guardar = async (ing_id, eva_id, res_nota) => {
    // Añadir res_nota a la URL
    const url = `API/resultados/guardar?ing_id=${ing_id}&eva_id=${eva_id}&res_nota=${res_nota}`;
    console.log(url);
    const config = {
        method: 'GET', 
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
                formulario.reset();
                buscarEvaluacionesAPI(pue_id, asp_id);
                cancelarAccion(); // Corrección aquí
                ocultarFormulario(); // Ocultar el formulario
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



//?------------------------------------------------------------------------------------------------

//!Aca esta la funcion de modificar un registro
const modificar = async () => {
    const res_id = formulario.res_id.value;
    const res_aspirante = formulario.ing_id.value;
    const eva_id = formulario.eva_id.value;

    const body = new FormData(formulario);
    body.append('res_id', res_id);
    body.append('res_evaluacion', eva_id);
    body.append('res_aspirante', res_aspirante);  

    const url = `/dopaz/API/resultados/modificar`;
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
                buscarEvaluacionesAPI(pue_id, asp_id);
                cancelarAccion(); // Corrección aquí
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
                    title: 'Campo Vacío',
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


//?------------------------------------------------------------------------------------------------
//!Función para buscar Las evaluaciones dependiendo del puesto que el aspirante esta optando
const buscarEvaluacionesAPI = async (pue_id, asp_id) => {
    contenedor = 1;
    contenedorr = 1;

    const url = `API/resultados/buscarEvaluaciones?pue_id=${pue_id}&asp_id=${asp_id}`;
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
//?------------------------------------------------------------------------------------------------

//?------------------------------------------------------------------------------------------------
//!Función para buscar a todo el personal que debe ser calificado
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
//?------------------------------------------------------------------------------------------------





//!---------------Modals Anidados Funcionales----------------!//
// //?------------------------------------------------------------------------------------------------
// let backdrop = null; // Variable global para almacenar el fondo oscuro
// const Abrir_Modal = () => {
//     // Obtén el modal mediante su id
//     const modal = document.getElementById('modalRequisito');

//     // Si ya existe un fondo oscuro, no hagas nada
//     if (backdrop) {
//         return;
//     }

//     // Agrega la clase 'show' al modal y quita la clase 'hide'
//     modal.classList.add('show');
//     modal.classList.remove('hide');

//     // Asegúrate de que el modal sea visible cambiando su estilo
//     modal.style.display = 'block';

//     // Agrega la clase 'modal-open' al body para atenuar el fondo
//     document.body.classList.add('modal-open');

//     // Agrega el estilo al fondo oscuro (modal-backdrop)
//     backdrop = document.createElement('div');
//     backdrop.classList.add('modal-backdrop');
//     backdrop.style.opacity = '0.5'; // Ajusta la opacidad según tus necesidades
//     document.body.appendChild(backdrop);
// };

// const Cerrar_Modal = () => {
//     const modal = document.getElementById('modalRequisito');

//     // Quita la clase 'show' y agrega la clase 'hide' al modal
//     modal.classList.remove('show');
//     modal.classList.add('hide');

//     // Asegúrate de que el modal no sea visible cambiando su estilo
//     modal.style.display = 'none';

//     // Quita la clase 'modal-open' del body para quitar la atenuación del fondo
//     document.body.classList.remove('modal-open');

//     // Elimina el fondo oscuro si existe
//     if (backdrop) {
//         document.body.removeChild(backdrop);
//         backdrop = null; // Restablece la variable a null para que se pueda volver a crear
//     }
// };
// btnCancelar.addEventListener('click', Abrir_Modal);
// btnCerrar.addEventListener('click', Cerrar_Modal);
// //?----------------------------------------------------------------------------------------------

const cancelarAccion = () => {
    formulario.reset();
    btnGuardar.style.display = 'block';
    btnModificar.style.display = 'block';
}

btnCancelar.addEventListener('click',cancelarAccion);

//?block es mostrar 
//?none y ocultar

//?--------------------------------------------------------------
//!Ocultar el Formulario al inicio
formulario.style.display = 'none';

//?--------------------------------------------------------------
//!Ocultar el Datatable y mostar el formulario
const MostrarFormulario = () => {
    formulario.style.display = 'block';
    tablaNotasContainer.style.display = 'none';
};
datatableRequisitos.on('click','.modificar-nota-btn', MostrarFormulario)
datatableRequisitos.on('click','.ver-calificaiones-btn', MostrarFormulario)

const ocultarFormulario = () => {
    formulario.style.display = 'none';
    tablaNotasContainer.style.display = 'block';
};
btnCancelar.addEventListener('click',ocultarFormulario);

const ocultarbtnGuardar = () => {
    btnGuardar.style.display = 'none';
};
datatableRequisitos.on('click','.modificar-nota-btn', ocultarbtnGuardar)

const ocultarbtnModificar = () => {
    btnModificar.style.display = 'none';
};
datatableRequisitos.on('click','.ver-calificaiones-btn', ocultarbtnModificar)


//?--------------------------------------------------------------

btnGuardar.addEventListener('click', function () {
    // Obtener los datos del formulario
    const ing_id = formulario.ing_id.value;
    const eva_id = formulario.eva_id.value;
    const res_nota = formulario.res_nota.value; // Obtener el valor de res_nota

    // Llamar a la función guardar con los datos obtenidos
    guardar(ing_id, eva_id, res_nota);
});
btnModificar.addEventListener('click', modificar)
datatableRequisitos.on('click','.modificar-nota-btn', traeDatos)
buscar();
