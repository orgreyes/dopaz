//?--------------------------------------------------------------

import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje"
import { validarFormulario, Toast } from "../funciones"
import Swal from "sweetalert2";
import L from "leaflet";
//?--------------------------------------------------------------

const formulario = document.querySelector('form');
const btnFormulario = document.getElementById('btnFormulario');
const btnModificar = document.getElementById('btnModificar');
const btnGuardar = document.getElementById('btnGuardar');
const btnBuscar = document.getElementById('btnBuscar');
const btnCancelar = document.getElementById('btnCancelar');
const tablaMisionContainer = document.getElementById('tablaMisionContainer');


//?--------------------------------------------------------------


let contenedor = 1;

const datatable = new Datatable('#tablaMision', {
    language : lenguaje,
    data : null,
    columns : [
        {
            title : 'NO',
            render: () => contenedor++ 
            
        },
        {
            title : 'MISIONES',
            data: 'mis_nombre'
        },
        {
            title : 'LATITUD',
            data: 'mis_latitud'
        },
        {
            title : 'LONGITUD',
            data: 'mis_longitud',
        },
        {
            title : 'MODIFICAR DATOS',
            data: 'mis_id',
            searchable: false,
            orderable: false,
            render : (data, type, row, meta) => `<button class="btn btn-warning" data-id='${data}' data-nombre='${row["mis_nombre"]}' data-latitud='${row["mis_latitud"]}' data-longitud='${row["mis_longitud"]}'>Modificar Datos</button>`
        },
        {
            title : 'ELIMINAR',
            data: 'mis_id',
            searchable: false,
            orderable: false,
            render : (data, type, row, meta) => `<button class="btn btn-danger" data-id='${data}'>Eliminar</button>`
        }
    ]
})


//?--------------------------------------------------------------
//!MAPA
const map = L.map('mapa', {
    center: [0, 0], 
    zoom: 2, 
    minZoom: 2, 
    maxZoom: 10,
});

map.on('resize', function () {
    map.invalidateSize();
});

const mapLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 10, 
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

const markerLayer = L.layerGroup();
const icono = L.icon({
    iconUrl: './images/ubicacion.png',
    iconSize: [35, 35]
});

L.circle([15.52, -90.32], { radius: 5000 }).addTo(markerLayer);

markerLayer.addTo(map);

const buscarMapa = async () => {
    const url = `API/misiones/buscarMapa`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        console.log(data);

        if (data && data.length > 0) {
            data.forEach(registro => {
                const latitud = parseFloat(registro.mis_latitud);
                const longitud = parseFloat(registro.mis_longitud);

                if (!isNaN(latitud) && !isNaN(longitud)) {
                    const NuevoMarcador = L.marker([latitud, longitud], {
                        icon: icono,
                        draggable: true
                    });

                    // Agregar un tooltip con el nombre de la ubicación
                    NuevoMarcador.bindTooltip(registro.mis_nombre, { permanent: true, direction: 'auto' });
                    
                    NuevoMarcador.addTo(markerLayer);
                }
            });
        } else {
            Toast.fire({
                title: 'No se encontraron registros',
                icon: 'info'
            });
        }
    } catch (error) {
        console.error('Error al cargar los datos desde la base de datos:', error);
    }
};

btnActualizar.addEventListener("click", () => {
    Toast.fire({
        title: 'Actualizando datos...',
        icon: 'info',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1000
    });
    
    // Reiniciar el mapa a la posición inicial
    map.setView([0, 0], 2);
    // Limpiar los marcadores actuales
    markerLayer.clearLayers();
    buscarMapa();
});

//?--------------------------------------------------------------
//!Aca esta la funcion para buscar
const buscar = async () => {
    contenedor = 1;

    const url = `API/misiones/buscar`;
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
    if (!validarFormulario(formulario, ['mis_id'])) {
        Toast.fire({
            icon: 'info',
            text: 'Debe llenar todos los datos'
        });
        return;
    }

    const body = new FormData(formulario);
    body.delete('mis_id');
    const url = 'API/misiones/guardar';
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
        title: 'Eliminar Datos de la Mision',
        text: '¿Desea eliminar los Datos de esta Mision?',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    });
    
    const button = e.target;
    const id = button.dataset.id
    // alert(id);
    
    if (result.isConfirmed) {
        const body = new FormData();
        body.append('mis_id', id);
        
        const url = `/dopaz/API/misiones/eliminar`;
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
    const mis_id = formulario.mis_id.value;
    const body = new FormData(formulario);
    body.append('mis_id', mis_id);

    const url = `/dopaz/API/misiones/modificar`;
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
tablaMisionContainer.style.display = 'none'; 

//!Mostrar el formulario, ocultar datatable
const mostrarFormulario = () => {
    formulario.style.display = 'block';
    tablaMisionContainer.style.display = 'none'; 
    };

//!Ocultar el formulario, mostrar datatable
const ocultarFormulario = () => {
    // formulario.reset();
    formulario.style.display = 'none';
    tablaMisionContainer.style.display = 'block';
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
    const latitud = button.dataset.latitud;
    const longitud = button.dataset.longitud;

    //! Llenar el formulario con los datos obtenidos
    formulario.mis_id.value = id;
    formulario.mis_nombre.value = nombre;
    formulario.mis_latitud.value = latitud;
    formulario.mis_longitud.value = longitud;

};

//?--------------------------------------------------------------

//!Aca esta la funcino de cancelar la accion de modificar un registro.
const cancelarAccion = () => {
    formulario.reset();
    document.getElementById('tablaMisionContainer').style.display = 'block'; 
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
datatable.on('click','.btn-warning', traeDatos)
datatable.on('click','.btn-warning', mostrarFormulario)
datatable.on('click','.btn-warning', MostrarBtnForumulario)
datatable.on('click','.btn-warning', mostrarBtns)
datatable.on('click','.btn-warning', OcultarTodoForumulario)
//?--------------------------------------------------------------
datatable.on('click','.btn-danger', eliminar)
//?--------------------------------------------------------------
buscar();
buscarMapa();




