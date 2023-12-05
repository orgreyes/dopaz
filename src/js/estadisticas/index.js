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
    });
});

// Colores predefinidos con opacidad
const colores = ['rgba(0, 0, 255, 0.5)', 'rgba(255, 0, 0, 0.5)', 'rgba(255, 255, 0, 0.5)', 'rgba(0, 128, 0, 0.5)'];

const chartEstados = new Chart(context, {
    type: 'bar',
    data: {
        labels: ['Personal Pendiente a Inicar Proceso', 'Personal Pendiente a Calificar Notas', 'Personal Pendiente a Revisaar Requisitos', 'Personal Que Aprovo Una Plaza'],
        datasets: [{
            label: 'Estados',
            data: [],
            backgroundColor: colores, // Utilizamos colores predefinidos
            borderColor: colores.map(color => color.replace('0.5', '1')), // Añadimos un borde para resaltar las barras
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
            const activosCount = data.filter(registro => registro.estado === 'Solicitante       ').length;
            const inactivosCount = data.filter(registro => registro.estado === 'CalificarNotas    ').length;
            const requisitosCount = data.filter(registro => registro.estado === 'RevisionRequisitos').length;
            const aprobadosCount = data.filter(registro => registro.estado === 'Aprobados         ').length;

            updateChart([activosCount, inactivosCount, requisitosCount, aprobadosCount]);
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
