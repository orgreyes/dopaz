//?--------------------------------------------------------------

import Swal from "sweetalert2";
import { validarFormulario, Toast, confirmacion} from "../funciones";


//?--------------------------------------------------------------

const btnBuscar = document.getElementById('btnBuscar');
const btnGuardar = document.getElementById('btnGuardar');
const formulario = document.querySelector('#formularioPersonal');

//?--------------------------------------------------------------
// //!Funcion para generar Codigos Aleatorios

function generarCodigoAleatorio(longitud) {
    const caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%?';
    let codigo = '';

    for (let i = 0; i < longitud; i++) {
        const indiceAleatorio = Math.floor(Math.random() * caracteres.length);
        codigo += caracteres.charAt(indiceAleatorio);
    }

    return codigo;
}
//?--------------------------------------------------------------

// //!Funcion Guardar
const guardar = async (evento) => {
    evento.preventDefault();
    if (!validarFormulario(formulario, ['per_catalogo'])) {
        Toast.fire({
            icon: 'info',
            text: 'Debe llenar todos los datos'
        });
        return;
    }

    // Genera un código aleatorio
    const codigoAleatorio = generarCodigoAleatorio(10); // Cambia la longitud según tus necesidades

    // Construye el cuerpo de la solicitud
    const body = new FormData(formulario);
    body.delete('per_catalogo');
    body.append('ing_codigo', codigoAleatorio);

    // Define la URL del servicio
    const url = '/dopaz/API/usuarios/guardar';
    const headers = new Headers();
    headers.append('X-Requested-With', 'fetch');
    const config = {
        method: 'POST',
        body
    };

    try {
        // Realiza la solicitud
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        // Procesa la respuesta
        const { codigo, mensaje, detalle } = data;
        let icon = 'info';
        switch (codigo) {
            case 1:
                icon = 'success';
                        'mensaje';
                formulario.reset();
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
};



//?--------------------------------------------------------------

// //!Funcion Buscar
const buscar = async () => {
    const per_catalogo = formulario.asp_catalogo.value;

    if (!per_catalogo) {
        Toast.fire({
            icon: 'info',
            text: 'Por favor, ingrese un número de catálogo.'
        });
        return;
    }

    const url = `API/usuarios/buscar?per_catalogo=${per_catalogo}`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        if (data === null) {
            Toast.fire({
                icon: 'info',
                text: 'El Catálogo que ingresó es Incorrecto.'
            });
            return;
        }

        if (Array.isArray(data) && data.length > 0) {
            const d = data[0]; // Tomamos el primer elemento del array

            formulario.asp_nom1.value = d.per_nom1;
            formulario.asp_nom2.value = d.per_nom2;
            formulario.asp_ape1.value = d.per_ape1;
            formulario.asp_ape2.value = d.per_ape2;
            formulario.asp_genero.value = d.per_sexo;
            formulario.asp_dpi.value = d.per_dpi;
            formulario.per_grado.value = d.gra_codigo;
            formulario.per_arma.value = d.arm_desc_md;
            formulario.foto.src = `https://sistema.ipm.org.gt/sistema/fotos_afiliados/ACTJUB/${d.per_catalogo}.jpg`;

            // Buscar puestos con el grado obtenido
            const url2 = `API/usuarios/buscarPuesto?pue_grado=${d.gra_codigo}`;
            console.log(url2);

            const respuesta2 = await fetch(url2);

            if (!respuesta2.ok) {
                console.error(`Error en la solicitud: ${respuesta2.status} ${respuesta2.statusText}`);
                return;
            }

            try {
                const puestosData = await respuesta2.json();

                // Ahora puedes manejar los datos de puestosData como desees
                console.log(puestosData);
            } catch (error) {
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Los datos de respuesta no son un array o están vacíos.'
            });
        }
    } catch (error) {
        // Manejo de errores
        console.log('Error en la solicitud:', error);
    }
};





btnBuscar.addEventListener('click', buscar);
btnGuardar.addEventListener('click', guardar);
