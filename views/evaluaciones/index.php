<style>
        .container {
            text-align: center;
            margin-top: 20px;
        }

        .map-container {
            margin-top: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            height: 400px;
            overflow: hidden;
        }

        #btnActualizar {
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 15px;
            cursor: pointer;
        }

        .custom-btn {
            font-size: 14px;
            padding: 7px;
        }

        .custom-btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .custom-btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .custom-btn-danger {
            background-color: #dc3545;
            color: #fff;
        }

</style>
<div class="container">
  <div class="row justify-content-center">
            <form class="col-lg-8 border bg-light p-3">
            <h1 class="text-center">Ingreso de Evaluaciones</h1><br>
            <input type="hidden" name="eva_id" id="eva_id">

            <!-- //!Nombre de la Evaluacion -->
                <div class="row mb-3">
                    <div class="col">
                    <label for="eva_nombre">Nombre de la Evaluacion</label>
                        <input type="text" name="eva_nombre" id="eva_nombre" class="form-control" >
                    </div>
                </div>


                <div class="row mb-3">
                <div class="col-8 mx-auto" style="margin-to:10px">
                        <button type="button" id="btnGuardar" class="btn custom-btn custom-btn-primary w-100 bi bi-save">  Guardar</button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="btnModificar" class="btn bi bi-pen custom-btn custom-btn-warning w-100">Modificar</button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="btnCancelar" class="btn custom-btn bi bi-x custom-btn-danger w-100">Cancelar</button>
                    </div>
                </div>
            </form>

            <div style="margin-top:30px; margin-left:32%" class="row mb-3">
                    <div class="col-8">
                        <button type="button" id="btnFormulario" class="btn custom-btn bi bi-save btn-primary w-100">  Agregar de Evaluaciones</button>
                    </div>
                    <div style="margin-top:8px;" class="col-8">
                        <button type="button" id="btnBuscar" class="btn custom-btn bi bi-eye btn-primary w-100">  Ver Lista de Evaluaciones</button>
                    </div>
                </div>

        </div>
        <div id="tablaEvaluacionContainer" class="container mt-1">
            <div class="row justify-content-center mt-4">
                <div class="col-12 p-4 shadow"> 
                    <div class="text-center">
                        <h1>Lista De Evaluaciones para El Personal</h1>
                    </div>
            <table id="tablaEvaluacion" class="table table-bordered table-hover">
                <!-- Contenido de la tabla -->
            </table>
        </div>
    </div>
</div>
<script src="<?= asset('./build/js/evaluaciones/index.js') ?>"></script>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




       