//?--------------------------------------------------------------

import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje"
import { validarFormulario, Toast } from "../funciones"
import Swal from "sweetalert2";
//?--------------------------------------------------------------

const formulario = document.querySelector('form');
const btnFormulario = document.getElementById('btnFormulario');
const btnModificar = document.getElementById('btnModificar');
const btnGuardar = document.getElementById('btnGuardar');
const btnBuscar = document.getElementById('btnBuscar');
const btnCancelar = document.getElementById('btnCancelar');
const fecha_final = document.getElementById('fecha_final');
const fecha_post = document.getElementById('fecha_post');
const tablaContingenteContainer = document.getElementById('tablaContingenteContainer');


//?--------------------------------------------------------------


let contenedor = 1;

const datatable = new Datatable('#tablaContingente', {
    language : lenguaje,
    data : null,
    columns : [
        {
            title : 'NO',
            render: () => contenedor++ 
            
        },
        {
            title : 'CONTINGENTES',
            data: 'cont_nombre'
        },
        {
            title : 'FECHA PREENTRENO',
            data: 'cont_fecha_pre'
        },
        {
            title : 'FECHA INICIO',
            data: 'cont_fecha_inicio',
            render: (data, type, row) => {
                if (type === 'display' && (data === null || data === '')) {
                    return '<span style="color: red;">PENDIENTE</span>';
                }
                return data;
            },
        },
        {
            title : 'FECHA FINALIZACION',
            data: 'cont_fecha_final',
            render: (data, type, row) => {
                if (type === 'display' && (data === null || data === '')) {
                    return '<span style="color: red;">PENDIENTE</span>';
                }
                return data;
            },
        },
        {
            title : 'FECHA POSTCONTINGENTE',
            data: 'cont_fecha_post',
            render: (data, type, row) => {
                if (type === 'display' && (data === null || data === '')) {
                    return '<span style="color: red;">PENDIENTE</span>';
                }
                return data;
            },
        },
        {
            title : 'MODIFICAR DATOS',
            data: 'cont_id',
            searchable: false,
            orderable: false,
            render : (data, type, row, meta) => `<button class="btn btn-warning" data-id='${data}' data-nombre='${row["cont_nombre"]}' data-fecha_pre='${row["cont_fecha_pre"]}' data-fecha_inicio='${row["cont_fecha_inicio"]}' data-fecha_final='${row["cont_fecha_final"]}' data-fecha_post='${row["cont_fecha_post"]}'>Actualizar Fechas</button>`
        },
        {
            title : 'ELIMINAR',
            data: 'cont_id',
            searchable: false,
            orderable: false,
            render : (data, type, row, meta) => `<button class="btn btn-danger" data-id='${data}'>Eliminar</button>`
        }
    ]
})


//?--------------------------------------------------------------

//!Aca esta la funcion para buscar
const buscar = async () => {
    contenedor = 1;

    const url = `API/contingentes/buscar`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config)
        const data = await respuesta.json();

        console.log(data);
        datatable.clear().draw()
        if (data) {
            datatable.rows.add(data).draw();
        } else {
            Toast.fire({
                title: 'No se encontraron registros',
                icon: 'info'
            })
        }

    } catch (error) {
        console.log(error);
    }
}


//?--------------------------------------------------------------

// //!Funcion Guardar
const guardar = async (evento) => {
    evento.preventDefault();
    if (!validarFormulario(formulario, ['cont_id','cont_fecha_inicio','cont_fecha_final','cont_fecha_post'])) {
        Toast.fire({
            icon: 'info',
            text: 'Debe llenar todos los datos'
        });
        return;
    }

    const body = new FormData(formulario);
    body.delete('cont_id','cont_fecha_inicio','cont_fecha_final','cont_fecha_post');
    const url = 'API/contingentes/guardar';
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
                icon = 'success', 
                        'mensaje';
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
}

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
        body.append('cont_id', id);
        
        const url = `/dopaz/API/contingentes/eliminar`;
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
                    buscar();
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
    const cont_id = formulario.cont_id.value;
    const body = new FormData(formulario);
    body.append('cont_id', cont_id);

    const url = `/dopaz/API/contingentes/modificar`;
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
tablaContingenteContainer.style.display = 'none'; 
fecha_final.style.display = 'none'; 
fecha_post.style.display = 'none'; 

//!Mostrar el formulario, ocultar datatable
const mostrarFormulario = () => {
    formulario.style.display = 'block';
    tablaContingenteContainer.style.display = 'none'; 
    };

//!Ocultar el formulario, mostrar datatable
const ocultarFormulario = () => {
    // formulario.reset();
    formulario.style.display = 'none';
    tablaContingenteContainer.style.display = 'block';
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
    const nombre = button.dataset.nombre;
    const fecha_pre = button.dataset.fecha_pre;
    const fecha_inicio = button.dataset.fecha_inicio;
    const fecha_final = button.dataset.fecha_final;
    const fecha_post = button.dataset.fecha_post;

    //! Llenar el formulario con los datos obtenidos
    formulario.cont_id.value = id;
    formulario.cont_nombre.value = nombre;
    formulario.cont_fecha_pre.value = fecha_pre;
    formulario.cont_fecha_inicio.value = fecha_inicio;
    formulario.cont_fecha_final.value = fecha_final;
    formulario.cont_fecha_post.value = fecha_post;
};

//?--------------------------------------------------------------

//!Aca esta la funcino de cancelar la accion de modificar un registro.
const cancelarAccion = () => {
    formulario.reset();
    document.getElementById('tablaContingenteContainer').style.display = 'block'; 
};
//?--------------------------------------------------------------

//!Ocultar los los inputs de fechas
const ocultarFechas = () => {
    fecha_final.style.display = 'none'; 
    fecha_post.style.display = 'none'; 
};

//!Mostrar los los inputs de fechas
const mostrarFechas = () => {
    fecha_final.style.display = 'block'; 
    fecha_post.style.display = 'block'; 
};
//?--------------------------------------------------------------




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
btnFormulario.addEventListener('click', ocultarFechas)
//?--------------------------------------------------------------
btnCancelar.addEventListener('click', ocultarFormulario);
btnCancelar.addEventListener('click', cancelarAccion);
btnCancelar.addEventListener('click', ocultarBtnForumulario);
btnCancelar.addEventListener('click', ocultarBtns);
btnCancelar.addEventListener('click', ocultarFechas);
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
datatable.on('click','.btn-warning', traeDatos)
datatable.on('click','.btn-warning', mostrarFechas)
datatable.on('click','.btn-warning', mostrarFormulario)
datatable.on('click','.btn-warning', MostrarBtnForumulario)
datatable.on('click','.btn-warning', mostrarBtns)
datatable.on('click','.btn-warning', OcultarTodoForumulario)
//?--------------------------------------------------------------
datatable.on('click','.btn-danger', eliminar)
//?--------------------------------------------------------------




