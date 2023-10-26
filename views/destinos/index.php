<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
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
<body>
<div class="container">
  <div class="row justify-content-center">
            <form class="col-lg-8 border bg-light p-3">
            <h1 class="text-center">Ingreso de Ubicaciones</h1><br>
            <input type="hidden" name="dest_id" id="dest_id">

            <!-- //!Ubicacion -->
                <div class="row mb-3">
                    <div class="col">
                    <label for="dest_nombre">Nombre de la Ubicacion</label>
                        <input type="text" name="dest_nombre" id="dest_nombre" class="form-control" >
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

        <br><br><br>
    <div class="container">
        <h1>UBICACIONES DEL PERSONAL DESPLEGADO</h1>
        <div class="map-container" id="mapa"></div>
        <br>
        <button class="btn btn-info btn-sm" id="btnActualizar" name="btnActualizar">ACTUALIZAR</button>
    </div>
    <script src="<?= asset('build/js/destinos/index.js') ?>"></script>
</body>
</html>
