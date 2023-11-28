<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de Puestos</title>
    <style>
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
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <form style="margin-top:6%" class="col-lg-8 border bg-light p-4 rounded">
            <h1 class="text-center mb-4">Ingreso de Puestos</h1>
            <input type="hidden" name="pue_id" id="pue_id">

            <!-- Nombre del Puesto -->
            <div class="mb-3">
                <label for="pue_nombre" class="form-label">Nombre del Puesto</label>
                <input type="text" name="pue_nombre" id="pue_nombre" class="form-control">
            </div>

            <div class="row mb-3">
            <div class="col-8 mx-auto" style="margin-to:10px">
                <button type="button" id="btnGuardar" class="btn custom-btn custom-btn-primary w-100 bi bi-save">    Guardar</button>
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
            <div class="col-6">
                <center><button style="margin-left:20%" type="button" id="btnFormulario" class="btn bi bi-save custom-btn custom-btn-primary w-100">  Ingreso de Puestos</button></center>
            </div>
            <div style="margin-top:8px;" class="col-8">
                <button type="button" id="btnBuscar" class="btn custom-btn custom-btn-primary bi bi-eye w-100">  Ver Lista de Puestos</button>
            </div>
        </div>
    </div>

    <div id="tablaPuestosContainer" class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-8 p-4 shadow">
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

</body>
</html>
