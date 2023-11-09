//?--------------------------------------------------------------

import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje"
import { validarFormulario, Toast } from "../funciones"
import Swal from "sweetalert2";
//?--------------------------------------------------------------

const btnGuardar = document.getElementById('btnGuardar');

//?--------------------------------------------------------------


let contenedor = 1;

const datatable = new Datatable('#tablaIngesos', {
    language : lenguaje,
    data : null,
    columns : [
        {
            title : 'NO',
            render: () => contenedor++ 
            
        },
        {
            title : 'ingresos',
            data: 'ing_codigo'
        },
        {
            title : 'ingresos',
            data: 'pue_nombre'
        },
        {
            title : 'ingresos',
            data: 'cont_nombre'
        },
        {
            title : 'NOTA INGLES',
            data: ''
        },
        {
            title : 'PUESTO',
            data: 'ing_pue',
            searchable: false,
            orderable: false,
            render : (data, type, row, meta) => `<button class="btn btn-warning" data-id='${data}'data-nombre='${row["eva_nombre"]}'>Modificar</button>`
        },
        {
            title : 'ELIMINAR',
            data: 'eva_id',
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

//?--------------------------------------------------------------
buscar();




