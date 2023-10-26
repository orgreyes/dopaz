import { Alert, Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario, Toast, confirmacion} from "../funciones";
import Datatable from "datatables.net-bs5";
import { lenguaje  } from "../lenguaje";


const formulario = document.querySelector('#formularioUsuarios');
const btnBuscar = document.getElementById('btnBuscar');

const buscar = async () => {

    let per_catalogo = formulario.per_catalogo.value;

    const url = `/dopaz/API/usuarios/buscar?per_catalogo=${per_catalogo}`;
    const config = {
        method: 'GET'
    }
    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        console.log(data);
        data.forEach(d => {
        
            formulario.per_nom1.value = d.per_nom1
            formulario.per_nom2.value = d.per_nom2
            formulario.per_ape1.value = d.per_ape1
            formulario.per_ape2.value = d.per_ape2
            formulario.per_sexo.value = d.per_sexo
            formulario.per_dpi.value = d.per_dpi
            formulario.gra_desc_md.value = d.gra_desc_md
            formulario.arm_desc_md.value = d.arm_desc_md
            
        });

    } catch (error) {
        console.log(error);
    }
};



// buscar();


btnBuscar.addEventListener('click', buscar);