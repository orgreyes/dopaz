//?--------------------------------------------------------------

import { Alert, Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario, Toast, confirmacion} from "../funciones";
import Datatable from "datatables.net-bs5";
import { lenguaje  } from "../lenguaje";

//?--------------------------------------------------------------

const btnBuscar = document.getElementById('btnBuscar');
const btnGuardar = document.getElementById('btnGuardar');
const btnIniciar = document.getElementById('btnIniciar');
const btnCancelar = document.getElementById('btnCancelar');
const btnSiguiente1 = document.getElementById('btnSiguiente1');
const formulario = document.querySelector('#formularioPersonal');
const formularioGuardar = document.getElementById('formularioGuardar');


//?--------------------------------------------------------------

// //!Funcion Guardar
const guardar = async (evento) => {
    evento.preventDefault();

    if (!validarFormulario(formulario, ['asp_id'])) {
        Toast.fire({
            icon: 'info',
            text: 'Debe llenar todos los datos'
        });
        return;
    }
    const body = new FormData(formulario);
    body.delete('asp_id');
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
console.log(data)
return
        const { codigo, mensaje, detalle } = data;
        let icon = 'info';
        switch (codigo) {
            case 1:
                icon = 'success', 
                        'mensaje';
                buscar();
                break;

            case 0:
                icon = 'info';
                console.log(detalle);
                break;

            case 2:
                icon = 'info';
                        'mensaje'
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

        if (Array.isArray(data)) {
            data.forEach(d => {
                formulario.asp_nom1.value = d.per_nom1;
                formulario.asp_nom2.value = d.per_nom2;
                formulario.asp_ape1.value = d.per_ape1;
                formulario.asp_ape2.value = d.per_ape2;
                formulario.asp_genero.value = d.per_sexo;
                formulario.asp_dpi.value = d.per_dpi;
                formulario.per_grado.value = d.gra_desc_md;
                formulario.per_arma.value = d.arm_desc_md;
                formulario.foto.src = `https://sistema.ipm.org.gt/sistema/fotos_afiliados/ACTJUB/${d.per_catalogo}.jpg`;
                console.log(data)
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

//?--------------------------------------------------------------
//?block es mostrar 
//?none y ocultar

//!Ocultar el Formulario al inicio
formulario.style.display = 'none';
formularioGuardar.style.display = 'none';
btnIniciar.style.display = 'block';

//!Mostrar el formulario, ocultar btnIniciar
const mostrarFormulario = () => {
    formulario.style.display = 'block';
    titulo.style.display = 'block';
    btnIniciar.style.display = 'none'; 
    };

//!Ocultar el formulario, y pasar al paso 2.
const ocultarFormulario = () => {
    if (!validarFormulario(formulario)) {
        Toast.fire({
            icon: 'info',
            text: 'Debe llenar todos los campos del formulario antes de continuar.'
        });
        return;
    }
    // formulario.reset();
    formulario.style.display = 'none';
    formularioGuardar.style.display = 'block';
};


//!Regresa al Formulario con los campos vacios
const iniciarRegistro = () => {
        formulario.reset();
    formulario.style.display = 'block';
    formularioGuardar.style.display = 'none';
    titulo.style.display = 'block';
    btnIniciar.style.display = 'none';
    };

    //!Regresa al Formulario con los campos vacios

      
//?--------------------------------------------------------------


btnIniciar.addEventListener('click', mostrarFormulario)
btnSiguiente1.addEventListener('click', ocultarFormulario)
btnCancelar.addEventListener('click', iniciarRegistro)
btnBuscar.addEventListener('click', buscar);
btnGuardar.addEventListener('click', guardar);