//?--------------------------------------------------------------
import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje"
import { validarFormulario, Toast } from "../funciones"
import { Dropdown } from "bootstrap";
import Swal from 'sweetalert2';
import $ from "jquery";

const btnPdf = document.getElementById('btnPdf');
const selectContingente = document.getElementById('cont_id');

document.addEventListener('DOMContentLoaded', function () {
    selectContingente.addEventListener('change', function () {
        const selectedContId = this.value;
        console.log('Contingente seleccionado:', selectedContId);
        buscarPorContingente(selectedContId);
    });

    btnPdf.addEventListener('click', pdf);
});

btnPdf.style.display = 'none';

const buscarPorContingente = async (selectedContId) => {
    contenedor = 1;

    const url = `API/aprobados/buscarPorContingente?cont_id=${selectedContId}`;
    console.log(url);

    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config)
        const data = await respuesta.json();

        console.log(data);
        datatable.clear().draw();

        if (data && data.length > 0) {
            datatable.rows.add(data).draw();
            btnPdf.style.display = 'block';
        } else {
            btnPdf.style.display = 'none';
            Toast.fire({
                title: 'No se encontraron registros',
                icon: 'info'
            });
        }

    } catch (error) {
        console.log(error);
    }
};

const pdf = async () => {
    const selectedContId = selectContingente.value;

    if (!selectedContId) {
        return;
    }

    const url = `/dopaz/aprobado?cont_id=${selectedContId}`;
    console.log(url);

    const headers = new Headers();
    headers.append("X-Requested-With", "fetch");
    const config = {
        method: 'GET',
        headers,
    };

    try {
        const respuesta = await fetch(url, config)
        if (respuesta.ok) {
            const blob = await respuesta.blob();

            if (blob) {
                const urlBlob = window.URL.createObjectURL(blob);

                window.open(urlBlob, '_blank');
                Swal.fire({
                    title: "PDF abierto correctamente",
                    icon: "success",
                });
            } else {
                console.error('No se pudo obtener el blob del PDF.');
            }
        } else {
            console.error('Error al generar el PDF.');
        }
    } catch (error) {
        console.error(error);
    }
};


let contenedor = 1;
const datatable = new Datatable('#tablaEvaluacion', {
    language: lenguaje,
    data: null,
    columns: [
        {
            title: 'NO',
            render: () => contenedor++

        },
        {
            title: 'NOMBRES DEL ASPIRANTE',
            data: 'nombre_aspirante'
        },
        {
            title: 'PUESTO A DESEMPEÑAR',
            data: 'nombre_puesto'
        },
        {
            title: 'CONTINGENTE',
            data: 'nombre_contingente'
        },
        {
            title: 'FECHA DE INICIO',
            data: 'cont_fecha_inicio'
        },
        {
            title: 'FECHA FINALIZACION',
            data: 'fecha_final_contingente',
            render: (data, type, row) => {
                if (type === 'display' && (data === null || data === '')) {
                    return '<span style="color: red;">PENDIENTE</span>';
                }
                return data;
            },
        },
        {
            title: 'ELIMINAR',
            data: 'ing_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => `<button class="btn btn-danger btn-eliminar bi bi-trash" data-ing-id='${data}'>Eliminar</button>`
        }
    ]
});

$('#tablaEvaluacion').on('click', '.btn-eliminar', function () {
    const ing_id = parseInt($(this).data('ing-id'));
    eliminar(ing_id, selectContingente.value);
});
//! Funcion Eliminar
const eliminar = async (ing_id, selectedContId) => {
    const result = await Swal.fire({
        icon: 'question',
        title: 'Eliminar Evaluacion',
        text: '¿Desea eliminar este Evaluacion?',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (result.isConfirmed) {
        const body = new FormData();
        body.append('ing_id', ing_id);

        const url = `/dopaz/API/aprobados/eliminar`;
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
                    // Recargar la tabla después de eliminar
                    datatable.clear().draw();
                    buscarPorContingente(selectedContId);
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


btnPdf.addEventListener('click', pdf);


