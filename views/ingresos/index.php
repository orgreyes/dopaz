<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        #containerBtn {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        #containerBtn2 {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        #containerBtn h1 {
            text-align: center;
            color: #333;
        }

        #contenedorBotones {
            text-align: center;
            margin-top: 20px;
        }

        /* Estilo para los botones (puedes ajustar según tus necesidades) */
        #containerBtn .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-align: center;
            text-decoration: none;
            background-color: #4CAF50;
            color: #fff;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Cambio de color al pasar el ratón sobre el botón */
        #containerBtn .btn:hover {
            background-color: #45a049;
        }
    </style>
<body>

<!-- //? ------------------------------------------------------------------------------------------>
<!-- //? ------------------------------------------------------------------------------------------>
<!-- //? ------------------------------------------------------------------------------------------>
<!-- //!Formulario de Busqueda para personal que solicita iniciar con proceso de seleccion -->
<div class="container" name="containerBtn"  id="containerBtn">
 <center><h1>Listado de Puestos</h1></center>
    <div id="contenedorBotones" class="row justify-content-center mt-4">
        <!-- Aquí se añadirán los botones dinámicamente -->
    </div>
</div>
<!-- //!Tabla de personal que solicita iniciar con seleccion -->
<div class="container">
        <div id="tablaSolicitudesContainer" class="container mt-1">
            <div class="row justify-content-center mt-4">
                <div class="col-12 p-4 shadow"> 
                    <div class="text-center">
                        <h1>
                            Listado de Personal que Solicita una Plaza.
                        </h1>
                    </div>
                    <table id="tablaSolicitudes" class="table table-bordered table-hover">
                        <!-- Contenido de la tabla -->
                    </table>
                 </div>
            </div>
        </div>
</div>
<!-- //? ------------------------------------------------------------------------------------------>
<!-- //? ------------------------------------------------------------------------------------------>
<!-- //? ------------------------------------------------------------------------------------------>
<div class="container"  id="containerBtn2">
<center><h1>Listado de Puestos2</h1></center>
    <div id="contenedorBotones2" class="row justify-content-center mt-4">
        <!-- Aquí se añadirán los botones dinámicamente -->
    </div>
</div>
<!-- //!Tabla de seleccion por Notas -->
<div class="container"> 
        <div id="tablaNotasContainer" class="container mt-1">
            <div class="row justify-content-center mt-4">
                <div class="col-12 p-4 shadow"> 
                    <div class="text-center">
                        <h1>
                            Fase 1, Seleccion por notas.
                        </h1>
                    </div>
                    <table id="tablaNotas" class="table table-bordered table-hover">
                        <!-- Contenido de la tabla -->
                    </table>
                 </div>
            </div>
        </div>
</div>
<!-- //? ------------------------------------------------------------------------------------------>
<!-- //? ------------------------------------------------------------------------------------------>
<!-- //? ------------------------------------------------------------------------------------------>
<div class="container"  id="containerBtn3">
<center><h1>Listado de Puestos2</h1></center>
    <div id="contenedorBotones3" class="row justify-content-center mt-4">
        <!-- Aquí se añadirán los botones dinámicamente -->
    </div>
</div>
<!-- //!Tabla de seleccion por Requisitos -->
<div class="container">
        <div id="tablaIngresosContainer" class="container mt-1">
            <div class="row justify-content-center mt-4">
                <div class="col-12 p-4 shadow"> 
                    <div class="text-center">
                        <h1>
                        Fase 2 Revision de Requisitos.
                        </h1>
                    </div>
                    <table id="tablaIngesos" class="table table-bordered table-hover">
                        <!-- Contenido de la tabla -->
                    </table>
                 </div>
            </div>
        </div>
</div>
<!-- //!Modal -->
<div class="modal fade" id="modalRequisito" tabindex="-1" role="dialog" aria-labelledby="modalRequisitoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="modalRequisitoLabel">Requisitos Para este Puesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container text-center">
                <table id="tablaRequisitos" class="table table-hover table-condensed table-bordered w-100">
                              <!-- Contenido de la tabla -->
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCerrar" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- //? ------------------------------------------------------------------------------------------>
<!-- //? ------------------------------------------------------------------------------------------>
<!-- //? ------------------------------------------------------------------------------------------>

                <div id="containerBtn2" name="ContenedorbtnInicio" style="margin-top:50px" class="row mb-3">
                    <div class="col-12">
                        <button type="button" id="btnInicio" class="btn btn-success w-100">INICIO DE PROCESO DE SELECCION</button>
                    </div>
                </div>
<!-- //? ------------------------------------------------------------------------------------------>
                <div name="btnFase1">
                <div id="containerBtn2"  style="margin-top:50px" class="row mb-3 d-flex">
                    <div class="col-6">
                        <button type="button" id="btnFaseFinal" class="btn btn-info w-100">SIGUIENTE FASE</button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="btnRegresar" class="btn btn-danger w-100">REGRESAR A FASE ANTERIOR</button>
                    </div>
                </div>
                </div>
<!-- //? ------------------------------------------------------------------------------------------>
                <div name="btnFase2">
                <div id="containerBtn2"  style="margin-top:50px" class="row mb-3 d-flex">
                    <div class="col-6">
                        <button type="button" id="btnFase2" class="btn btn-info w-100">SIGUIENTE FASE</button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="btnRegresarFase1" class="btn btn-danger w-100">REGRESAR A FASE ANTERIOR</button>
                    </div>
                </div>
                </div>
<!-- //? ------------------------------------------------------------------------------------------>
<script src="<?= asset('./build/js/ingresos/index.js') ?>"></script>
</body>
</html>
