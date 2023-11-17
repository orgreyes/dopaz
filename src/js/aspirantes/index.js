import { Alert, Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario, Toast, confirmacion} from "../funciones";
import Datatable from "datatables.net-bs5";
import { lenguaje  } from "../lenguaje";


const formulario = document.querySelector('#formularioPersonal');
const btnBuscar = document.getElementById('btnBuscar');
const btnGuardar = document.getElementById('btnGuardar');


//?--------------------------------------------------------------
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
    if (!validarFormulario(formulario, ['asp_catalogo'])) {
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
    body.delete('asp_catalogo');
    body.append('ing_codigo', codigoAleatorio);

    // Define la URL del servicio
    const url = '/dopaz/API/aspirantes/guardar';
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
//! Funcion Buscar
const buscar = async () => {
    const asp_catalogo = formulario.catalogo.value;

    if (!asp_catalogo) {
        Toast.fire({
            icon: 'info',
            text: 'Por favor, ingrese un número de catálogo.'
        });
        return;
    }

    const url = `API/aspirantes/buscar?asp_catalogo=${asp_catalogo}`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);

        if (data === null) {
            Swal.fire({
                icon: 'info',
                text: 'El Catalogo que Ingreso, no se ha guardado en la Base de Datos de Primer Ingreso.',
                footer: '<button class="btn btn-warning"><a href="/dopaz/aspirantes" style="color: white; text-decoration: none;">Ir al Formulario de Personal que No ha sido registrado antes</a></button>'
            });
            return;
        }

        if (Array.isArray(data) && data.length > 0) {
            const d = data[0]; // Tomamos el primer elemento del array

            formulario.asp_nom1.value = d.asp_nom1;
            formulario.asp_nom2.value = d.asp_nom2;
            formulario.asp_ape1.value = d.asp_ape1;
            formulario.asp_ape2.value = d.asp_ape2;
            formulario.asp_genero.value = d.asp_genero_desc;
            formulario.asp_dpi.value = d.asp_dpi;
            formulario.per_grado.value = d.per_grado_id;
            formulario.per_arma.value = d.arma;
            formulario.asp_id.value = d.asp_id;
            formulario.foto.src = `https://sistema.ipm.org.gt/sistema/fotos_afiliados/ACTJUB/${d.asp_catalogo}.jpg`;

            // Buscar puestos con el grado obtenido
            const url2 = `API/aspirantes/buscarPuesto?pue_grado=${d.per_grado_id}`;
            console.log(url2);

            const respuesta2 = await fetch(url2);

            if (!respuesta2.ok) {
                console.error(`Error en la solicitud: ${respuesta2.status} ${respuesta2.statusText}`);
                return;
            }

                        try {
                const puestosData = await respuesta2.json();

                // Limpiar el select antes de agregar nuevas opciones
                formulario.ing_puesto.innerHTML = '<option value="">SELECCIONE...</option>';

                if (Array.isArray(puestosData) && puestosData.length > 0) {
                    puestosData.forEach((puesto) => {
                        // Asegurarse de que existan las propiedades necesarias
                        if (puesto && puesto.pue_id && puesto.pue_nombre) {
                            const option = document.createElement('option');
                            option.value = puesto.pue_id;
                            option.textContent = puesto.pue_nombre;
                            formulario.ing_puesto.appendChild(option);
                        }
                    });
                }

                // Ahora puedes manejar los datos de puestosData como desees
                console.log(puestosData);
            } catch (error) {
                console.error('Error al procesar la respuesta de puestos:', error);
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


// buscar();


btnBuscar.addEventListener('click', buscar);
btnGuardar.addEventListener('click', guardar);