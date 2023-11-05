<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
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
    </style>
<div class="container">
  <div class="row justify-content-center">
            <form class="col-lg-8 border bg-light p-3">
            <h1 class="text-center">Ingreso de Misiones</h1><br>
            <input type="hidden" name="mis_id" id="mis_id">

            <!-- //!Nombre de la Mision -->
                <div class="row mb-3">
                    <div class="col">
                    <label for="mis_nombre">Nombre de la Mision</label>
                        <input type="text" name="mis_nombre" id="mis_nombre" class="form-control" >
                    </div>
                </div>

                <!-- Latitud -->
                <div class="row mb-3">
                    <div class="col">
                        <label for="mis_latitud">Ingrese Coordenadas Decimales en Latitud</label>
                        <input type="number" name="mis_latitud" id="mis_latitud" class="form-control" step="0.000001" min="-90" max="90" required>
                        <small>Ejemplo: -3.458</small>
                    </div>
                </div>

                <!-- Longitud -->
                <div class="row mb-3">
                    <div class="col">
                        <label for="mis_longitud">Ingrese Coordenadas Decimales en Longitud</label>
                        <input type="number" name="mis_longitud" id="mis_longitud" class="form-control" step="0.000001" min="-180" max="180" required>
                        <small>Ejemplo: 27.987</small>
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
                        <button type="button" id="btnFormulario" class="btn btn-info w-100">Agregar Misión</button>
                    </div>
                    <div style="margin-top:8px;" class="col-8">
                        <button type="button" id="btnBuscar" class="btn btn-info w-100">Ver Lista de Misiones</button>
                    </div>
                </div>

        </div>
        <div id="tablaMisionContainer" class="container mt-1">
            <div class="row justify-content-center mt-4">
                <div class="col-12 p-4 shadow"> 
                    <div class="text-center">
                        <h1>Misiones</h1>
                    </div>
            <table id="tablaMision" class="table table-bordered table-hover">
                <!-- Contenido de la tabla -->
            </table>
        </div>
    </div>
</div>
  </div>

  <div class="container">
        <h1>MAPA CON COORDENADAS REGISTRADAS DE LAS MISIONES</h1>
        <div class="map-container" id="mapa"></div>
        <br>
        <button class="btn btn-info btn-sm" id="btnActualizar" name="btnActualizar">ACTUALIZAR</button>
    </div>

<script src="<?= asset('./build/js/misiones/index.js') ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




       