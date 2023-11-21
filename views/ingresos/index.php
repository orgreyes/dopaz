<body>
<!-- //? ------------------------------------------------------------------------------------------>
<!-- //? ------------------------------------------------------------------------------------------>
<!-- //? ------------------------------------------------------------------------------------------>
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
<script src="<?= asset('./build/js/ingresos/index.js') ?>"></script>
</body>
</html>
