import { Alert, Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario, Toast, confirmacion } from "../funciones";
import Datatable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formulario = document.querySelector('#formularioUsuarios');
const btnBuscar = document.getElementById('btnBuscar');



const buscar = async (e) => {
 

    let catalogo = e.target.value


    if (catalogo.trim().length < 6) {
        catalogoValido = false;
        return
    }
    try {
        const url = `/dopaz/API/usuarios/buscarCatalogo?catalogo=${catalogo}`
        const headers = new Headers();
        headers.append("X-Requested-With", "fetch");

        const config = {
            method: 'GET',
            headers
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);
        
        if (data) {
            catalogoValido = true
            const { grado, nombre } = data;

            if(e.target.id=='catalogo'){
                inputNombre.value = grado + " " + nombre


            }if(e.target.id=='catalogog1'){

                inputnombreg1.value = grado + " " + nombre

            }if(e.target.id=='catalogog3'){
                inputnombreg3.value = grado + " " + nombre

            }

           


        } else {
            catalogoValido = false
        }


    } catch (error) {

    }


    console.log(catalogoValido);
}

btnBuscar.addEventListener('click', buscar);
