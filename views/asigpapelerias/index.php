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
        <style>
            img {
                border: 2px solid #ccc;
                max-width: 100%;
                height: auto;
            }

            h2 {
                text-align: center;
                color: #333;
            }

            .form-container {
                border: 1px solid #ccc;
                padding: 20px;
                background-color: #f9f9f9;
            }

            .form-container .form-group {
                margin-bottom: 20px;
            }

            .form-container label {
                font-weight: bold;
            }

            .form-container .name-input-group {
                display: flex;
            }
        </style>
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <form class="col-lg-8 border bg-light p-3">
      <h1 class="text-center">Asignacion de Papeleria Requerida por Puesto</h1><br>
      <input type="hidden" name="asig_pap_id" id="asig_pap_id">

      <!--//!Nombre del Contingente -->
      <div class="row mb-3 col-md-12">
          <div class="col">
              <label for="asig_pap_puesto"><i  class="fas fa-globe"></i> Seleccione Un Puesto </label><br>
              <select name="asig_pap_puesto" id="asig_pap_puesto" style="width: 100%; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                  <option value="">SELECCIONE...</option>
                  <?php foreach ($puestos as $puesto) : ?>
                  <option value="<?= $puesto['pue_id'] ?>">
                      <?= $puesto['pue_nombre'] ?></option>
                  <?php endforeach ?>
              </select>
          </div>
        </div>
                            
      <!-- //!Fecha de Inicio de Preparación -->
      <div class="row mb-3 col-md-12">
          <div class="col">
              <label for="asig_pap_papeleria"><i class="fas fa-flag"></i> Seleccione la Papeleria Requiere el Puesto Seleccionado</label><br>
              <select name="asig_pap_papeleria" id="asig_pap_papeleria" style="width: 100%; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                  <option value="">SELECCIONE...</option>
                  <?php foreach ($papelerias as $papeleria) : ?>
                  <option value="<?= $papeleria['pap_id'] ?>">
                      <?= $papeleria['pap_nombre'] ?></option>
                  <?php endforeach ?>
              </select>
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
        <button type="button" id="btnFormulario" class="btn btn-info w-100">Agregar Papelerias a Puestos</button>
      </div>
      <div style="margin-top:8px;" class="col-8">
        <button type="button" id="btnBuscar" class="btn btn-info w-100">Ver Lista de Puestos</button>
      </div>
    </div>

  </div>

  <div id="tablaPapeleriaContainer" class="container mt-1">
    <div class="row justify-content-center mt-4">
      <div class="col-12 p-4 shadow">
        <div class="text-center">
          <h1>Puestos</h1>
        </div>
        <table id="tablaPuestos" class="table table-bordered table-hover">
          <!-- Contenido de la tabla -->
        </table>
      </div>
    </div>
  </div>
</div>

<!-- //!Modal -->
<div class="modal fade" id="modalPapeleria" tabindex="-1" role="dialog" aria-labelledby="modalPapeleriaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="modalPapeleriaLabel">Papeleria Que se Necesita Para este Puesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container text-center">
                <table id="tablaPapelerias" class="table table-hover table-condensed table-bordered w-100">
                              <!-- Contenido de la tabla -->
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCerrar" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

    
<script src="<?= asset('./build/js/asigpapelerias/index.js') ?>"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
</body>
</html>
