import Swal from "sweetalert2";
import { Modal } from "bootstrap";
import { validarFormulario, Toast } from "../funciones";

const formLogin = document.getElementById('formLogin')
const loginButton = document.getElementById('loginButton')
const spinner = document.getElementById('spinner')
const divModal = document.getElementById('modalAceptar')
const modalAceptar = new Modal(divModal, {})


let verificado = false
window.verificar = (dato) =>{
    Toast.fire({
        icon: 'success',
        title: 'Captcha verificado'
    })
    verificado = true;
  
}



window.expirado = () => {
    Toast.fire({
        icon: 'warning',
        title: 'Captcha expirado'
    })
  verificado = false;
  modalAceptar.hide();
}

window.error = () => {
    Toast.fire({
        icon: 'error',
        title: 'Ocurrió un error en la verificación de captcha'
    })
  verificado = false;
  modalAceptar.hide();
}


const login = async e => {
    e.preventDefault();
    loginButton.disabled = true;
    spinner.classList.remove('d-none')
    if(!verificado){
        Toast.fire({
            icon: 'warning',
            title: 'Debe verificar el captcha'
        })
        loginButton.disabled = false;
        spinner.classList.add('d-none')
        return;
    }

    if(!validarFormulario(formLogin)){
        Toast.fire({
            icon: 'info',
            title: 'Debe llenar todos los campos'
        })
        loginButton.disabled = false;
        spinner.classList.add('d-none')
        return
    }

    try {
        const url = '/login/API/login'

        const body2 = new FormData(formLogin);
        
        const headers = new Headers();
        headers.append("X-Requested-With", "fetch");

        const config = {
            method: 'POST',
            headers,
            body : body2
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);
        if(data.codigo == 0){
            Toast.fire({
                icon: 'error',
                title: data.mensaje
            })
        }else{
            // location.href = "/menu"
            
            modalAceptar.show();
        }
        

    } catch (error) {
        console.log(error);
    }
    loginButton.disabled = false;
    spinner.classList.add('d-none')
    
}

divModal.addEventListener('hide.bs.modal',e=>{
    location.href = '/login/logout'
})

formLogin.addEventListener('submit', login );
