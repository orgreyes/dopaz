<!DOCTYPE html>
<html>

<head>
    <title>Formulario Militar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <div class="container mt-4">
        <h2 id="titulo">Formulario para personal que viaja por primera vez a Contingente</h2><br>

        <div class="col-6 mx-auto">
            <button type="button" id="btnIniciar" class="btn btn-primary w-100">
                <i class="fas fa-play-circle"></i> Iniciar el Registro de Personal nuevo
            </button>
        </div>

        <form class="form-container" id="formularioPersonal">
            <h5><b>Paso 1.</b> Ingrese la Información Requerida del Aspirante</h5>
            <br>
            <div class="row">
                <div class="col-md-3">
                    <img for="per_catalogo" id="foto" src="./images/foto.jpg" alt="Fotografía">
                </div>
                <div class="col-md-9">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="per_catalogo"><i class="fas fa-id-card"></i> Buscar Datos por Catalogo:</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="per_catalogo" name="per_catalogo">
                                <div class="input-group-append">
                                    <button type="button" id="btnBuscar" class="btn btn-outline-success">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                    <button type="reset" id="btnLimpiar" class="btn btn-outline-primary">
                                        <i class="fas fa-eraser"></i> Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="per_nom1"><i class="fas fa-user-tie"></i> Nombres:</label>
                                <input type="text" class="form-control" id="per_nom1"  name="per_nom1" placeholder="Primer Nombre">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="per_nom2"><i class="fas fa-user"></i></label>
                                <input type="text" class="form-control" id="per_nom2"  name="per_nom2" placeholder="Segundo Nombre">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="per_ape1"><i class="fas fa-user-tie"></i> Apellidos:</label>
                                <input type="text" class="form-control" id="per_ape1"  name="per_ape1" placeholder="Primer apellido">
                            </div>
                        <div class="form-group col-md-6">
                            <label for="per_ape2"><i class="fas fa-user"></i></label>
                            <input type="text" class="form-control" id="per_ape2"  name="per_ape2" placeholder="Segundo apellido">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="per_grado"><i class="fas fa-shield"></i>Grado:</label>
                            <input type="text" class="form-control" id="per_grado"  name="per_grado" placeholder="Grado">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="per_arma"><i class="fas fa-crosshairs"></i>Arma:</label>
                            <input type="text" class="form-control" id="per_arma"  name="per_arma" placeholder="Arma">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="per_genero"><i class="fas fa-venus-mars"></i>Genero:</label>
                            <input type="text" class="form-control" id="per_genero"  name="per_genero" placeholder="Genero">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="per_dpi"><i class="fas fa-id-card"></i>DPI:</label>
                            <input type="text" class="form-control" id="per_dpi"  name="per_dpi" placeholder="DPI">
                        </div>

                        <div class="row mb-3 col-md-7">
                            <div class="col">
                                <label for="per_puesto"><i class="fas fa-shield"></i>Seleccione el Puesto solicitado</label>
                                <select name="per_puesto" id="per_puesto" class="form-control">
                                    <option value="">SELECCIONE...</option>
                                    <?php foreach ($puestos as $puesto) : ?>
                                        <option value="<?= $puesto['pue_id'] ?>">
                                            <?= $puesto['pue_nombre'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="col-6">
                            <button type="button" id="btnSiguiente1" class="btn btn-primary w-100">
                                Siguiente <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </form>
        <br>

        <form class="form-container" id="formularioGuardar">
            <center><h5><b>Paso 2.</b> Guarde la Información</h5></center>
            <br>
            <div class="col-6 mx-auto">
                <button type="button" id="btnGuardar" class="btn btn-primary w-100">
                    <i class="fas fa-save"></i> Guardar Registro
                </button>
            </div>
            <br>
            <div class="col-6 mx-auto">
                <button type="button" id="btnCancelar" class="btn btn-danger w-100">
                    <i class="fas fa-times"></i> Cancelar Registro
                </button>
            </div>
        </form>
        <br>

        <form class="form-container" id="formularioEnviar">
            <center><h5><b>Paso 3.</b> Envíe la Solicitud para Optar a una Plaza en el Contingente</h5></center>
            <br>
            <div class="col-6 mx-auto">
                <button type="button" id="btnEnviar" class="btn btn-primary w-100">
                    <i class="fas fa-paper-plane"></i> Enviar Solicitud
                </button>
            </div>
            <br>
        </form>
    </div>
    <script src="<?= asset('./build/js/usuarios/index.js') ?>"></script>
</body>

</html>
