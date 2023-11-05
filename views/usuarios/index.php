    <!DOCTYPE html>
    <html>

    <head>
        <title>Formulario Militar</title>
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
                        <img for="asp_catalogo" id="foto" src="./images/foto.jpg" alt="Fotografía">
                    </div>
                    <div class="col-md-9">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="asp_catalogo"><i class="fas fa-id-card"></i> Buscar Datos por Catalogo:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="asp_catalogo" name="asp_catalogo">
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
                                    <label for="asp_nom1"><i class="fas fa-user-tie"></i> Nombres:</label>
                                    <input type="text" class="form-control" id="asp_nom1"  name="asp_nom1" placeholder="Primer Nombre">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="asp_nom2"><i class="fas fa-user"></i></label>
                                    <input type="text" class="form-control" id="asp_nom2"  name "asp_nom2" placeholder="Segundo Nombre">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="asp_ape1"><i class="fas fa-user-tie"></i> Apellidos:</label>
                                    <input type="text" class="form-control" id="asp_ape1"  name "asp_ape1" placeholder="Primer apellido">
                                </div>
                            <div class="form-group col-md-6">
                                <label for="asp_ape2"><i class="fas fa-user"></i></label>
                                <input type="text" class="form-control" id="asp_ape2"  name "asp_ape2" placeholder="Segundo apellido">
                            </div>

                            <!--//!------------------------->
                            <div class="row mb-3 col-md-12">
                                <div class="col">
                                    <label for="per_grado"><i class="fas fa-shield"></i> Seleccione Grado</label><br>
                                    <select name="per_grado" id="per_grado" style="width: 100%; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                                        <option value="">SELECCIONE...</option>
                                        <?php foreach ($grados as $grado) : ?>
                                        <option value="<?= $grado['gra_codigo'] ?>">
                                            <?= $grado['gra_desc_md'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="form-group col-md-6">
                                <label for="per_arma"><i class="fas fa-crosshairs"></i>Arma:</label>
                                <input type="text" class="form-control" id="per_arma"  name "per_arma" placeholder="Arma">
                            </div>
                            <!--//!------------------------->

                            <div class="form-group col-md-6">
                                <label for="asp_genero"><i class="fas fa-venus-mars"></i>Genero:</label>
                                <input type="text" class="form-control" id="asp_genero"  name "asp_genero"  placeholder="Genero">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="asp_dpi"><i class="fas fa-id-card"></i>DPI:</label>
                                <input type="text" class="form-control" id="asp_dpi"  name "asp_dpi" placeholder="DPI">
                            </div>

                            <div class="row mb-3 col-md-6">
                                <div class="col">
                                    <label for="ing_puesto"><i class="fas fa-shield"></i> Seleccione Puesto a Desempeñar</label><br>
                                    <select name="ing_puesto" id="ing_puesto" style="width: 100%; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
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
        </div>
        <script src="<?= asset('./build/js/usuarios/index.js') ?>"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#per_grado').select2();
                $('#ing_puesto').select2();
            });
        </script>
    </body>

    </html>
