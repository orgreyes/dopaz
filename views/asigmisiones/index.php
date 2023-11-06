<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tu Aplicación</title>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap CSS (opcional) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Bootstrap JS y Popper.js (asegúrate de cargar Popper.js antes de Bootstrap.js) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.1/umd/popper.min.js"></script>
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <form class="col-lg-8 border bg-light p-3">
      <h1 class="text-center">Asignacion de Misiones a Contingentes</h1><br>
      <input type="hidden" name="asig_id" id="asig_id">

      <!-- Nombre del Contingente -->
      <div class="row mb-3">
        <div class="col">
          <label for="asig_contingente">Seleccion Contingente</label>
          <input type="text" name="asig_contingente" id="asig_contingente" class="form-control">
        </div>
      </div>

      <!-- Fecha de Inicio de Preparación -->
      <div class="row mb-3">
        <div class="col">
          <label for="asig_mision">Seleccione la Misión que se llevó a cabo en el Contingente Seleccionado</label>
          <input type="text" name="asig_mision" id="asig_mision" class="form-control">
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

  <div id="tablaAsigMisionesContainer" class="container mt-1">
    <div class="row justify-content-center mt-4">
      <div class="col-12 p-4 shadow">
        <div class="text-center">
          <h1>Contingentes</h1>
        </div>
        <table id="tablaAsigMisiones" class="table table-bordered table-hover">
          <!-- Contenido de la tabla -->
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalMisiones" tabindex="-1" role="dialog" aria-labelledby="modalMisionesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="modalMisionesLabel">Misines del Contingente Seleccionado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container text-center">
                <table id="tablaMisiones" class="table table-hover table-condensed table-bordered w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <?php ?>
                            <th>MISIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí puedes agregar filas de datos si es necesario -->
                        <?php
                        $contadorMisiones = 0; // Inicializa el contador

                        // Aquí puedes iterar a través de tus datos y generar las filas
                        foreach ($tusDatos as $dato) {
                            $contadorMisiones++; // Incrementa el contador en cada iteración
                            echo "<tr>";
                            echo "<td>$contadorMisiones</td>";
                            echo "<td>{$dato['mis_nombre']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="buttonAnterior" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

    
<script src="<?= asset('./build/js/asigmisiones/index.js') ?>"></script>
</body>
</html>
