import { Dropdown } from "bootstrap";
import Chart from "chart.js/auto";
import { lenguaje } from "../lenguaje"
import { validarFormulario, Toast } from "../funciones"


const canvas = document.getElementById('chartEstados');
const context = canvas.getContext('2d');
const selectContingente = document.getElementById('cont_id');

document.addEventListener('DOMContentLoaded', function () {
    selectContingente.addEventListener('change', function () {
        const selectedContId = this.value;
        console.log('Contingente seleccionado:', selectedContId);
        getEstadisticas(selectedContId);
    });});




const getRandomColors = (count) => {
    const colors = [];
    for (let i = 0; i < count; i++) {
        const r = Math.floor(Math.random() * 256);
        const g = Math.floor(Math.random() * 256);
        const b = Math.floor(Math.random() * 256);
        colors.push(`rgba(${r},${g},${b},0.5)`);
    }
    return colors;
};

const chartEstados = new Chart(context, {
    type: 'polarArea',
    data: {
        labels: ['Personal Pendiente a Inicar Proceso', 'Personal Pendiente a Calificar Notas', 'Personal Pendiente a Revisaar Requisitos', 'Personal Que Aprovo Una Plaza'],
        datasets: [{
            label: 'Estados',
            data: [],
            backgroundColor: getRandomColors(4), // Generamos 3 colores para las 3 barras
            borderColor: [], // Añadimos un borde para resaltar las barras
            borderWidth: 1,   // Ancho del borde
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

const getEstadisticas = async (selectedContId) => {
    const url = `API/estadisticas/grafica?cont_id=${selectedContId}`;
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        console.log("Datos que trae la gráfica:", data);

        if (data) {
            const activosCount = data.filter(registro => registro.estado ===   'Solicitante       ').length;
            const inactivosCount = data.filter(registro => registro.estado === 'CalificarNotas    ').length;
            const requisitosCount = data.filter(registro => registro.estado ==='RevisionRequisitos').length;
            const aprovadosCount = data.filter(registro => registro.estado === 'Aprobados         ').length;

            chartEstados.data.datasets[0].borderColor = getRandomColors(3); // Asignamos colores de borde también

            updateChart([activosCount, inactivosCount, requisitosCount, aprovadosCount]);
        } else {
            Toast.fire({
                title: 'No se encontraron Registros',
                icon: 'info'
            });
        }
    } catch (error) {
        console.log(error);
    }
};

const updateChart = (data) => {
    chartEstados.data.datasets[0].data = data;
    chartEstados.update();
};




