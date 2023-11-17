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

            <form class="form-container" id="formularioPersonal">
                <div class="row">
                    <div class="col-md-3">
                        <img for="asp_catalogo" id="foto" src="./images/foto.jpg" alt="Fotografía">
                        <input type="hidden" name="asp_id" id="asp_id">
                    </div>
                    <div class="col-md-9">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="catalogo"><i class="fas fa-id-card"></i> Buscar Datos por Catalogo:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="catalogo" name="catalogo">
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
                                    <input type="text" class="form-control" id="asp_nom1"  name="asp_nom1" disabled placeholder="Primer Nombre">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="asp_nom2"><i class="fas fa-user"></i></label>
                                    <input type="text" class="form-control" id="asp_nom2"  name= "asp_nom2" disabled placeholder="Segundo Nombre">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="asp_ape1"><i class="fas fa-user-tie"></i> Apellidos:</label>
                                    <input type="text" class="form-control" id="asp_ape1"  name= "asp_ape1" disabled placeholder="Primer apellido">
                                </div>
                            <div class="form-group col-md-6">
                                <label for="asp_ape2"><i class="fas fa-user"></i></label>
                                <input type="text" class="form-control" id="asp_ape2"  name= "asp_ape2" disabled placeholder="Segundo apellido">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="asp_genero"><i class="fas fa-venus-mars"></i>Genero:</label>
                                <input type="text" class="form-control" id="asp_genero"  name= "asp_genero" disabled  placeholder="Genero">
                            </div>

                            <!--//!------------------------->
                            <div class="row mb-3 col-md-6">
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
                                <input type="text" class="form-control" id="per_arma"  name="per_arma" disabled placeholder="Arma">
                            </div>
                            
                            <div class="row mb-3 col-md-6">
                                <div class="col">
                                    <label for="asig_contingente"><i  class="fas fa-globe"></i> Seleccione Un Contingente </label><br>
                                    <select name="asig_contingente" id="asig_contingente" style="width: 100%; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                                        <option value="">SELECCIONE...</option>
                                        <?php foreach ($contingentes as $contingente) : ?>
                                        <option value="<?= $contingente['cont_id'] ?>">
                                            <?= $contingente['cont_nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                            
                            
                            <!--//!------------------------->

                            
                            <div class="form-group col-md-6">
                                <label for="asp_dpi"><i class="fas fa-id-card"></i>DPI:</label>
                                <input type="text" class="form-control" id="asp_dpi" disabled  name= "asp_dpi" placeholder="DPI">
                            </div>

                            <div class="row mb-3 col-md-6">
                                <div class="col">
                                    <label for="ing_puesto"><i class="fas fa-shield"></i> Seleccione Puesto a Desempeñar</label><br>
                                    <select name="ing_puesto" id="ing_puesto"  style="width: 100%; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                                        <option value="">SELECCIONE...</option>
                                        <?php foreach ($puestos as $puesto) : ?>
                                        <option value="<?= $puesto['pue_id'] ?>">
                                            <?= $puesto['pue_nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="col-6 mx-auto">
                                <button type="button" id="btnGuardar" class="btn btn-primary w-100">
                                    <i class="fas fa-save"></i> Guardar Registro
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </form>
            <br>

            <br>
        </div>
        <script src="<?= asset('./build/js/aspirantes/index.js') ?>"></script>

    </body>

    </html>
