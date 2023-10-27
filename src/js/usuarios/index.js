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

// //!Funcion Buscar
const buscar = async () => {

    let per_catalogo = formulario.asp_catalogo.value;

    const url = `/dopaz/API/usuarios/buscar?per_catalogo=${per_catalogo}`;
    const config = {
        method: 'GET'
    }
    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        console.log(data);
        data.forEach(d => {
        
            formulario.asp_nom1.value = d.per_nom1
            formulario.asp_nom2.value = d.per_nom2
            formulario.asp_ape1.value = d.per_ape1
            formulario.asp_ape2.value = d.per_ape2
            formulario.asp_genero.value = d.per_sexo
            formulario.asp_dpi.value = d.per_dpi
            formulario.asp_grado.value = d.gra_desc_md
            formulario.asp_arma.value = d.arm_desc_md
            
        });

    } catch (error) {
        console.log(error);
    }
};



// buscar();


btnBuscar.addEventListener('click', buscar);
btnGuardar.addEventListener('click', guardar);