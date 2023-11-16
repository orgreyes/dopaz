<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tu Aplicación</title>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap CSS y JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Popper.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.1/umd/popper.min.js"></script>
  <!-- Otros estilos y scripts -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
</head>
<body>
    
<div class="container">
    <div id="tablaIngresosContainer" class="container mt-1">
        <div class="row justify-content-center mt-4">
            <div class="col-12 p-4 shadow"> 
                <div class="text-center">
                    <h1>Ingreso de Notas para Personal Aspirante</h1>
                </div>
                <table id="tablaIngesos" class="table table-bordered table-hover">
                    <!-- Contenido de la tabla -->
                </table>
            </div>
        </div>
    </div>
</div>

<!--//!Modal -->
<div class="modal fade" id="modalRequisito" tabindex="-1" role="dialog" aria-labelledby="modalRequisitoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">


          <!-- //?------------------------------------------------------------------------------->
            <div class="modal-header">
                <h5 class="modal-title" id="modalRequisitoLabel">Formulario para Subir Notas</h5>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <form class="col-lg-8 border bg-light p-3">
                        <h1 class="text-center">Ingreso de Evaluaciones</h1><br>
                        <input type="text" name="eva_id" id="eva_id">
                        <input type="text" name="ing_id" id="ing_id">
                        <input type="text" name="res_id" id="res_id">
                        <!-- Nombre de La Evaluacion A Calificar -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="res_nota">Evaluacion</label>
                                <input type="text" name="eva_nombre" id="eva_nombre" disabled class="form-control">
                            </div>
                        </div>
                        <!-- Ingreso de la Nota -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="res_nota">Ingrese la Nota</label>
                                <input type="number" name="res_nota" id="res_nota" class="form-control">
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
                </div>
            </div>

            <!-- //?------------------------------------------------------------------------------------ -->
            <div class="modal-body container text-center">
              <h1>Lista de Evaluaciones a Calificar</h1>
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


<script src="<?= asset('./build/js/resultados/index.js') ?>"></script>
</body>
</html>
