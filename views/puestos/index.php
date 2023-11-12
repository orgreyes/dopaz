<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Puestos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
</head>
<body>
<div class="container">
  <div class="row justify-content-center">
    <form class="col-lg-8 border bg-light p-3">
      <h1 class="text-center">Ingreso de Puestos</h1><br>
      <input type="hidden" name="pue_id" id="pue_id">

      <!-- Nombre del Puesto -->
      <div class="row mb-3">
        <div class="col">
          <label for="pue_nombre">Nombre del Puesto</label>
          <input type="text" name="pue_nombre" id="pue_nombre" class="form-control">
        </div>
      </div>

      <div class="row mb-3 col-md-18">
        <div class="col">
          <label for="pue_grado"><i class="fas fa-shield"></i> Seleccione El Grado al que Corresponde el Puesto</label>
          <select name="pue_grado" id="pue_grado" class="form-control">
            <option value="">SELECCIONE...</option>
            <?php foreach ($grados as $grado) : ?>
              <option value="<?= $grado['gra_codigo'] ?>">
                <?= $grado['gra_desc_md'] ?></option>
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
<!-- Agrega la biblioteca de jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Agrega la biblioteca de Bootstrap (JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Agrega la biblioteca de Select2 (JS) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script>
  // Inicializa Select2 en el elemento select con el id "pue_grado"

</script>
</body>
</html>
