<div class="container">
  <div class="row justify-content-center">
            <form class="col-lg-8 border bg-light p-3">
            <h1 class="text-center">Ingreso de Contingentes</h1><br>
            <input type="hidden" name="cont_id" id="cont_id">

            <!-- //!Nombre del Contingente -->
                <div class="row mb-3">
                    <div class="col">
                    <label for="cont_nombre">Nombre del Contingente</label>
                        <input type="text" name="cont_nombre" id="cont_nombre" class="form-control" >
                    </div>
                </div>

                <!-- //!Fecha de Inicio de Preparacion -->
                <div class="row mb-3">
                    <div class="col">
                    <label for="cont_fecha_pre">Fecha de Preparación antes del Contingente</label>
                        <input type="date" name="cont_fecha_pre" id="cont_fecha_pre" class="form-control" >
                    </div>
                </div>

            <!-- //!Fecha de Inicio -->
                <div class="row mb-3">
                    <div class="col">
                    <label for="cont_fecha_inicio">Fecha de Inicio del Contingente</label>
                        <input type="date" name="cont_fecha_inicio" id="cont_fecha_inicio" class="form-control" >
                    </div>
                </div>

                <!-- //!Fecha de Finalizacion de Contingente -->
                <div id="fecha_final" class="row mb-3">
                    <div class="col">
                    <label for="cont_fecha_final">Fecha que Finaliza Contingente</label>
                        <input type="date" name="cont_fecha_final" id="cont_fecha_final" class="form-control" >
                    </div>
                </div>
                
                <!-- //!Fecha de Postentrenamiento -->
                <div id="fecha_post" class="row mb-3">
                    <div class="col">
                    <label for="cont_fecha_post">Fecha de Postentrenamiento del Contingente</label>
                        <input type="date" name="cont_fecha_post" id="cont_fecha_post" class="form-control" >
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" id="btnGuardar" class="btn btn-info w-100">Guardar</button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="btnModificar" class="btn btn-warning w-100">Modificar</button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="btnCancelar" class="btn btn-danger w-100">Cancelar</button>
                    </div>
                </div>
            </form>

            <div style="margin-top:30px; margin-left:32%" class="row mb-3">
                    <div class="col-8">
                        <button type="button" id="btnFormulario" class="btn btn-info w-100">Agregar Contingente</button>
                    </div>
                    <div style="margin-top:8px;" class="col-8">
                        <button type="button" id="btnBuscar" class="btn btn-info w-100">Ver Lista de Contingentes</button>
                    </div>
                </div>

        </div>
        <div id="tablaContingenteContainer" class="container mt-1">
            <div class="row justify-content-center mt-4">
                <div class="col-12 p-4 shadow"> 
                    <div class="text-center">
                        <h1>Contingentes</h1>
                    </div>
            <table id="tablaContingente" class="table table-bordered table-hover">
                <!-- Contenido de la tabla -->
            </table>
        </div>
    </div>
</div>
<script src="<?= asset('./build/js/contingentes/index.js') ?>"></script>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




       