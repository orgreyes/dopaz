//?--------------------------------------------------------------

import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje"
import { validarFormulario, Toast } from "../funciones"
import Swal from "sweetalert2";
//?--------------------------------------------------------------

//?--------------------------------------------------------------


let contenedor = 1;
let contenedorr = 1;

const datatable = new Datatable('#tablaIngesos', {
    language : lenguaje,
    data : null,
    columns : [
        {
            title : 'NO',
            render: () => contenedor++ 
            
        },
        // {
        //     title : 'ingresos',
        //     data: 'ing_codigo'
        // },
        {
            title : 'PUESTO SOLICITANTE',
            data: 'pue_nombre'
        },
        {
            title : 'CONTINGENTE A PARTICIPAR',
            data: 'cont_nombre'
        },
        // {
        //     title : 'NOTA INGLES',
        //     data: ''
        // },
        {
            title : 'REQUISITOS',
            data: 'ing_id',
            searchable: false,
            orderable: false,
            render : (data, type, row, meta) => `<button class="btn btn-info ver-requisitos-btn" data-bs-toggle='modal' data-bs-target='#modalRequisito' data-id='${data}'data-nombre='${row["eva_nombre"]}'>Validar Requisitos</button>`
        },
        {
            title : 'ELIMINAR',
            data: 'asig_req_id',
            searchable: false,
            orderable: false,
            render : (data, type, row, meta) => `<button class="btn btn-success" data-id='${data}'>Aprovar Plaza</button>`
        }
    ]
})

let tablaRequisitos = new Datatable('#tablaRequisitos', {
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
            title: 'APROBACIÓN DE REQUISITO',
            data: 'asig_req_aprovada',
            render: (data, type, row) => {
                const aprovada = parseInt(data, 10); // Convierte a número
        
                if (type === 'display') {
                    if (isNaN(aprovada) || aprovada === 0) {
                        return '<span style="color: orange;">PENDIENTE</span>';
                    } else if (aprovada === 1) {
                        return '<span style="color: red;">NO APROBADO</span>';
                    } else if (aprovada === 2) {
                        return '<span style="color: blue;">APROBADO</span>';
                    }
                }
                return data;
            },
        },
        
        {
            title: 'APRUEBA REQUISITO?',
            data: 'asig_req_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => `<button class="btn btn-success" data-id='${data}'>Aprobar Requisito</button>`
        }
    ]
});


// Agregar un manejador de eventos para los botones "Ver Misiones"
$('#tablaIngesos').on('click', '.ver-requisitos-btn', function () {
    const ing_puesto = parseInt($(this).data('id')); // Convertir a entero
    buscarRequisitoPuestoAPI(ing_puesto);
});
// Agregar un manejador de eventos para el cierre del modal
$('#modalRequisito').on('hidden.bs.modal', function (e) {
    // Restablecer el contador y limpiar la tabla de misiones cuando se cierra el modal
    limpiar();
});

const buscarRequisitoPuestoAPI = async (ing_puesto) => {
        // Reiniciar los contadores
        contenedor = 1;
        contenedorr = 1;
    const url = `/dopaz/API/ingresos/buscarRequisitoPuesto?ing_puesto=${ing_puesto}`;
    console.log(url);

    const config = {
        method: 'GET'
    };
    
    try {
        const respuesta = await fetch(url, config);
        if (respuesta.ok) {
            const data = await respuesta.json();
            console.log(data);
            tablaRequisitos.clear().draw();
            if (data) {
                tablaRequisitos.rows.add(data).draw();
            }
        } else {
            console.error('Error en la solicitud: ' + respuesta.status);
        }
    } catch (error) {
        console.error(error);
    }
};

//?--------------------------------------------------------------

//!Aca esta la funcion para buscar
const buscar = async () => {
    contenedor = 1;

    const url = `API/ingresos/buscar`;
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



//!Aca esta la funcion de modificar un registro
const modificar = async e => {
    const result = await Swal.fire({
        icon: 'question',
        title: 'Aprovar Requisito',
        text: '¿Desea Aprovar este Requisito?',
        showCancelButton: true,
        confirmButtonText: 'Aprovar',
        cancelButtonText: 'Cancelar'
    });
    
    const button = e.target;
    const id = button.dataset.id
    // alert(id);
    
    if (result.isConfirmed) {
        const body = new FormData();
        body.append('asig_req_id', id);
        
        const url = `API/ingresos/modificar`;
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
                        title: 'Aprovado Exitosamente',
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
tablaRequisitos.on('click','.btn-success', modificar)
buscar();




