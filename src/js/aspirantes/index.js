import { Alert, Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario, Toast, confirmacion} from "../funciones";
import Datatable from "datatables.net-bs5";
import { lenguaje  } from "../lenguaje";


const formulario = document.querySelector('#formularioUsuarios');
const btnBuscar = document.getElementById('btnBuscar');
const btnGuardar = document.getElementById('btnGuardar');


//?--------------------------------------------------------------

// //!Funcion Guardar
const guardar = async (evento) => {
    evento.preventDefault();

    if (!validarFormulario(formulario, ['per_id'])) {
        Toast.fire({
            icon: 'info',
            text: 'Debe llenar todos los datos'
        });
        return;
    }
    const body = new FormData(formulario);
    body.delete('per_id');
    const url = 'API/usuarios/guardar';
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
                icon = 'info';
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

//!Funcion Buscar
const buscar = async () => {
    const per_catalogo = formulario.per_catalogo.value;

    if (!per_catalogo) {
        Toast.fire({
            icon: 'info',
            text: 'Por favor, ingrese un número de catálogo.'
        });
        return;
    }

    const url = `API/aspirantes/buscar?per_catalogo=${per_catalogo}`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        if (data === null) {
            Toast.fire({
                icon: 'info',
                text: 'El Catálogo que ingresó no se ha registrado.'
            });
            return;
        }

        if (Array.isArray(data)) {
            data.forEach(d => {
                formulario.per_nom1.value = d.per_nom1;
                formulario.per_nom2.value = d.per_nom2;
                formulario.per_ape1.value = d.per_ape1;
                formulario.per_ape2.value = d.per_ape2;
                formulario.per_genero.value = d.per_sexo;
                formulario.per_dpi.value = d.per_dpi;
                formulario.per_grado.value = d.gra_desc_md;
                formulario.per_arma.value = d.arm_desc_md;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Los datos de respuesta no son un array.'
            });
        }
    } catch (error) {
        // Manejo de errores
        console.log(error);
    }
};





// buscar();


btnBuscar.addEventListener('click', buscar);
btnGuardar.addEventListener('click', guardar);