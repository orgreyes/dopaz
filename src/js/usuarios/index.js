//?--------------------------------------------------------------
import { Alert, Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario, Toast, confirmacion} from "../funciones";
import Datatable from "datatables.net-bs5";
import { lenguaje  } from "../lenguaje";

//?--------------------------------------------------------------

const btnBuscar = document.getElementById('btnBuscar');
const containerBtn = document.getElementById('containerBtn');
const btnGuardar = document.getElementById('btnGuardar');
const formulario = document.querySelector('#formularioPersonal');
const formularioPrincipal = document.getElementById('formulario');
const btnMilitar = document.getElementById('btnMilitar');
const btnCivil = document.getElementById('btnCivil');
const BtnRegresar = document.getElementById('containerBtnRegresar');
const InputCatalogo = document.getElementById('InputCatalogo');

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
// Definir la función verificarDPI al principio del bloque
const verificarDPI = () => {
    let cui = formulario.asp_dpi.value;

    // Resto del código de validación del DPI
    var cuiRegExp = /^[0-9]{4}\s?[0-9]{5}\s?[0-9]{4}$/;

    if (!cui) {
        Toast.fire({
            icon: 'warning',
            title: 'LLENE EL CAMPO DEL DPI'
        });
        return false;
    }

    if (!cuiRegExp.test(cui)) {
        Toast.fire({
            icon: 'warning',
            title: 'DPI INCORRECTO'
        });
        formulario.asp_dpi.value = ""; // Cambiado a formulario en lugar de formaltas
        return false;
    }

    // Si llega aquí, el DPI es válido
    return true;
};


//!Funcion Guardar
const guardar = async (evento) => {
    evento.preventDefault();
    if (!validarFormulario(formulario, ['asp_catalogo'])) {
        Toast.fire({
            icon: 'info',
            text: 'Debe llenar todos los datos'
        });
        return;
    }
    // Validar DPI
    if (!verificarDPI()) {
        Toast.fire({
            icon: 'warning',
            title: 'DPI NO VALIDO'
        });
        return;
    }

    // Agrega los archivos PDF al formulario con validación de formato
    const archivosPDF = document.querySelectorAll('input[type="file"][name^="pdf_ruta"]');

    // Verifica si hay al menos un archivo PDF adjunto
    if (archivosPDF.length === 0 || !archivosPDF[0].files || archivosPDF[0].files.length === 0) {
        // Muestra un mensaje de error si no hay archivos adjuntos
        Toast.fire({
            icon: 'error',
            text: 'Debe adjuntar al menos un archivo PDF.',
        });
        return;
    }

    // Validación de formato PDF
    for (const archivo of archivosPDF) {
        const nombreArchivo = archivo.files[0].name;
        if (!nombreArchivo.toLowerCase().endsWith('.pdf')) {
            // Muestra un mensaje de error si el formato no es PDF
            Toast.fire({
                icon: 'error',
                text: `El archivo ${nombreArchivo} no tiene el formato PDF.`,
            });
            // Detiene la ejecución de la función
            return;
        }
    }

    // Genera un código aleatorio
    const codigoAleatorio = generarCodigoAleatorio(10);

    // Construye el cuerpo de la solicitud
    const body = new FormData(formulario);
    body.delete('per_catalogo');
    body.append('ing_codigo', codigoAleatorio);

    // Agrega los archivos PDF al formulario
    archivosPDF.forEach((archivo, index) => {
        console.log(`Adjuntando archivo ${index + 1}:`, archivo.files[0]);
        body.append(`pdf_ruta[]`, archivo.files[0]);
    });

    // Agrega estos console.log adicionales
    console.log("Archivos adjuntos:", body.getAll("pdf_ruta[]"));
    console.log("Solicitud antes de enviar:", body);

    // Define la URL del servicio
    const url = '/dopaz/API/usuarios/guardar';
    const headers = new Headers();
    headers.append('X-Requested-With', 'fetch');

    // Define la configuración
    const config = {
        method: 'POST',
        body,
        headers,
    };

    try {
        // Realiza la solicitud
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);

        // Procesa la respuesta
        const { codigo, mensaje, detalle } = data;
        let icon = 'info';
        switch (codigo) {
            case 1:
                icon = 'success';
                'mensaje';
                formulario.reset();
                ocultarBtnGuardar();
                buscar();
                formulario.foto.src = './images/foto.jpg';
                const contenedorDocumentos = document.getElementById('contenedorDocumentos');
                contenedorDocumentos.innerHTML = '';
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

//!Función Buscar
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
        console.log(data);
        if (data === null) {
            Toast.fire({
                icon: 'info',
                text: 'El Catálogo que ingresó es Incorrecto.'
            });
            return;
        }

        if (Array.isArray(data) && data.length > 0) {
            const d = data[0];

            formulario.asp_nom1.value = d.per_nom1;
            formulario.asp_nom2.value = d.per_nom2;
            formulario.asp_ape1.value = d.per_ape1;
            formulario.asp_ape2.value = d.per_ape2;
            formulario.asp_genero.value = d.per_sexo;
            formulario.asp_dpi.value = d.per_dpi;
            formulario.per_grado.value = d.per_grado_id;
            formulario.per_arma.value = d.arm_codigo;
            formulario.foto.src = `https://sistema.ipm.org.gt/sistema/fotos_afiliados/ACTJUB/${d.per_catalogo}.jpg`;

            // Limpiar el contenedor antes de insertar nuevos campos
            const contenedorDocumentos = document.getElementById('contenedorDocumentos');
            contenedorDocumentos.innerHTML = '';

            // Buscar puestos con el grado obtenido
            const url2 = `API/usuarios/buscarPuesto?pue_grado=${d.per_grado_id}`;

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

    // Agrega un evento de escucha al cambio en el select de grados
    formulario.per_grado.addEventListener('change', async () => {
        // Limpia los botones de entrada de documentos generados dinámicamente
        const contenedorDocumentos = document.getElementById('contenedorDocumentos');
        contenedorDocumentos.innerHTML = '';

        const gradoSeleccionado = formulario.per_grado.value;

        if (gradoSeleccionado) {
            const urlNueva = `API/usuarios/buscarPuesto?pue_grado=${gradoSeleccionado}`;

            try {
                const respuestaNueva = await fetch(urlNueva);

                if (respuestaNueva.ok) {
                    const puestosDataNueva = await respuestaNueva.json();

                    formulario.ing_puesto.innerHTML = '<option value="">SELECCIONE...</option>';

                    if (Array.isArray(puestosDataNueva) && puestosDataNueva.length > 0) {
                        puestosDataNueva.forEach((puesto) => {
                            if (puesto && puesto.pue_id && puesto.pue_nombre) {
                                const option = document.createElement('option');
                                option.value = puesto.pue_id;
                                option.textContent = puesto.pue_nombre;
                                formulario.ing_puesto.appendChild(option);
                            }
                        });
                    }

                    console.log(puestosDataNueva);
                } else {
                    console.error(`Error en la solicitud: ${respuestaNueva.status} ${respuestaNueva.statusText}`);
                }
            } catch (error) {
                console.error('Error al procesar la respuesta de puestos:', error);
            }
        }
    });

    // Agrega un evento de escucha al clic en el botón "Limpiar"
    const btnLimpiar = document.getElementById('btnLimpiar');
    btnLimpiar.addEventListener('click', () => {
        // Quita la foto usando la ruta especificada
        formulario.foto.src = './images/foto.jpg';

        // Limpia los botones de entrada de documentos generados dinámicamente
        const contenedorDocumentos = document.getElementById('contenedorDocumentos');
        contenedorDocumentos.innerHTML = '';
    });
};


//!Funcion OBTENER REQUISITOS, ACA SE GENERAN LOS INPUTS DE LOS PDF DINAMICAMENTE

formulario.ing_puesto.addEventListener('change', obtenerRequisitos);

async function obtenerRequisitos() {
    const puestoSeleccionado = formulario.ing_puesto.value;

    const url = `API/usuarios/obtenerRequisitos?pue_id=${puestoSeleccionado}`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);

        // Limpia el contenedor antes de insertar nuevos campos
        const contenedorDocumentos = document.getElementById('contenedorDocumentos');
        contenedorDocumentos.innerHTML = '';

        // Verifica si hay requisitos y crea campos dinámicos
        if (Array.isArray(data.usuarios) && data.usuarios.length > 0) {
            const requisitosFiltrados = data.nombreRequisitos.filter(requisito => !requisito.requisito.toLowerCase().startsWith('psicologico'));
            const cantidadRequisitos = requisitosFiltrados.length;

            // Obtén los nombres de los requisitos
            const nombresRequisitos = requisitosFiltrados.map(requisito => requisito.requisito);

            for (let i = 0; i < cantidadRequisitos; i++) {
                // Crea el contenedor div
                const nuevoDiv = document.createElement('div');
                nuevoDiv.className = 'col-lg-10';

                // Crea una etiqueta (label) para cada input usando el nombre del requisito
                const nuevoLabel = document.createElement('label');
                nuevoLabel.textContent = `PDF ${i + 1} (${nombresRequisitos[i]})`;
                nuevoLabel.htmlFor = `documento${i + 1}`;
                nuevoLabel.className = 'bi bi-file-pdf-fill'; // Agrega la clase del ícono si es necesario

                // Crea el input para el archivo
                const nuevoInput = document.createElement('input');
                nuevoInput.type = 'file';
                nuevoInput.name = `pdf_ruta`;
                nuevoInput.id = `pdf_ruta`;
                nuevoInput.className = 'form-control'; // Agrega la clase del formulario si es necesario

                // Agrega los elementos al contenedor
                nuevoDiv.appendChild(nuevoLabel);
                nuevoDiv.appendChild(nuevoInput);
                contenedorDocumentos.appendChild(nuevoDiv);
            }
            mostrarBtnGuardar();
        } else {
            console.log('No se encontraron requisitos para el puesto seleccionado.');
        }
    } catch (error) {
        console.error('Error al obtener requisitos:', error);
    }
}



//!Ocultar el Datatable al inicio
containerBtn.style.display = 'block';
formularioPrincipal.style.display = 'none'; 

const mostrarFormularioMilitar = () => {
    containerBtn.style.display = 'none';
    formularioPrincipal.style.display = 'block'; 
    };

    const mostrarFormularioCivil = () => {
        containerBtn.style.display = 'none';
        InputCatalogo.style.display = 'none';
        formularioPrincipal.style.display = 'block'; 
        };

const ocultarFormulario = () => {
    containerBtn.style.display = 'block';
    InputCatalogo.style.display = 'block';
    formularioPrincipal.style.display = 'none'; 
    formulario.reset();
    formulario.foto.src = './images/foto.jpg';
    const contenedorDocumentos = document.getElementById('contenedorDocumentos');
    contenedorDocumentos.innerHTML = '';
    btnGuardar.style.display = 'none';
    };
    

//!Ocultar btnGuardar
btnGuardar.style.display = 'none';


//!Mostrar btnGuardar
const mostrarBtnGuardar = () => {
    btnGuardar.style.display = 'block';
    };

//!Ocultar btnGuardar
const ocultarBtnGuardar = () => {
    btnGuardar.style.display = 'none';
    };
    

btnMilitar.addEventListener('click', mostrarFormularioMilitar);
btnCivil.addEventListener('click', mostrarFormularioCivil);
BtnRegresar.addEventListener('click', ocultarFormulario);

btnBuscar.addEventListener('click', buscar);
btnGuardar.addEventListener('click', guardar);


