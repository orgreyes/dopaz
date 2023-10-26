<div class="container">
  <div class="row justify-content-center">
            <form class="col-lg-8 border bg-light p-3">
            <h1 class="text-center">Ingreso de Puestos</h1><br>
            <input type="hidden" name="pue_id" id="pue_id">

            <!-- //!Nombre del Puesto -->
                <div class="row mb-3">
                    <div class="col">
                    <label for="pue_nombre">Nombre del Puesto</label>
                        <input type="text" name="pue_nombre" id="pue_nombre" class="form-control" >
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
                        <button type="button" id="btnFormulario" class="btn btn-info w-100">Ingreso de Puestos</button>
                    </div>
                    <div style="margin-top:8px;" class="col-8">
                        <button type="button" id="btnBuscar" class="btn btn-info w-100">Ver Lista de Puestos</button>
                    </div>
                </div>

        </div>
        <div id="tablaPuestosContainer" class="container mt-1">
            <div class="row justify-content-center mt-4">
                <div class="col-12 p-4 shadow"> 
                    <div class="text-center">
                        <h1>Lista De Puestos para El Personal</h1>
                    </div>
            <table id="tablaPuestos" class="table table-bordered table-hover">
                <!-- Contenido de la tabla -->
            </table>
        </div>
    </div>
</div>
<script src="<?= asset('./build/js/puestos/index.js') ?>"></script>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




       