<div class="container">
  <div class="row justify-content-center">
            <form class="col-lg-8 border bg-light p-3">
            <h1 class="text-center">Creacion de Requisitos</h1><br>
            <input type="hidden" name="req_id" id="req_id">

            <!-- //!Nombre de Requisito -->
                <div class="row mb-3">
                    <div class="col">
                    <label for="req_nombre">Ingrese Nombre del Requisito</label>
                        <input type="text" name="req_nombre" id="req_nombre" class="form-control" >
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
                        <button type="button" id="btnFormulario" class="btn btn-info w-100">Registrar otro Requisito</button>
                    </div>
                    <div style="margin-top:8px;" class="col-8">
                        <button type="button" id="btnBuscar" class="btn btn-info w-100">Ver Lista de Requisitos Creados</button>
                    </div>
                </div>

        </div>
        <div id="tablaRequisitosContainer" class="container mt-1">
            <div class="row justify-content-center mt-4">
                <div class="col-12 p-4 shadow"> 
                    <div class="text-center">
                        <h1>Lista De Requisitos Registrados</h1>
                    </div>
            <table id="tablaRequisitos" class="table table-bordered table-hover">
                <!-- Contenido de la tabla -->
            </table>
        </div>
    </div>
</div>
<script src="<?= asset('./build/js/requisitos/index.js') ?>"></script>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




       