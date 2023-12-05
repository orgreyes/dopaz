<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Militar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        #containerBtn {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        #containerBtnRegresar {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

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

    <div id="containerBtn">
        <div class="row mb-3 d-flex">
            <center>
                <h1>Seleccione Una Opcion</h1>
            </center>
            <br><br>
            <div class="col-6">
                <button type="button" id="btnMilitar" class="btn btn-info w-100">Aspirante Militar</button>
            </div>
            <div class="col-6">
                <button type="button" id="btnCivil" class="btn btn-warning w-100">Aspirante Civil</button>
            </div>
        </div>
    </div>

    <div id="formulario" class="container mt-4">
        <h2 id="titulo">Formulario para personal que ha participado en Contingente antes</h2><br>

        <form class="form-container" id="formularioPersonal" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-3">
                    <img for="asp_catalogo" id="foto" src="./images/foto.jpg" alt="Fotografía">
                    <input type="hidden" name="asp_id" id="asp_id">
                </div>
                <div class="col-md-9">
                    <div class="form-row" id="InputCatalogo">
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
                            <input type="text" class="form-control" id="asp_nom1" name="asp_nom1" placeholder="Primer Nombre" oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="asp_nom2"><i class="fas fa-user"></i></label>
                            <input type="text" class="form-control" id="asp_nom2" name="asp_nom2" placeholder="Segundo Nombre" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="asp_ape1"><i class="fas fa-user-tie"></i> Apellidos:</label>
                            <input type="text" class="form-control" id="asp_ape1" name="asp_ape1" placeholder="Primer Apellido" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="asp_ape2"><i class="fas fa-user"></i></label>
                            <input type="text" class="form-control" id="asp_ape2" name="asp_ape2" placeholder="Segundo Apellido" oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="asp_genero"><i class="fas fa-venus-mars"></i>Genero:</label>
                            <input type="text" class="form-control" id="asp_genero" name="asp_genero" placeholder="Genero">
                        </div>

                        <!--//!------------------------->
                        <div class="form-group col-md-6">
                                <label for="per_grado"><i class="fas fa-shield"></i> Seleccione Grado</label><br>
                                <select name="per_grado" id="per_grado" class="form-control">
                                    <option value="">SELECCIONE...</option>
                                    <?php foreach ($grados as $grado) : ?>
                                        <option value="<?= $grado['gra_codigo'] ?>">
                                            <?= $grado['gra_desc_md'] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class=" form-group col-md-6">
                                <label for="per_arma"><i class="fas fa-shield"></i> Seleccione Arma</label><br>
                                <select name="per_arma" id="per_arma" class="form-control">
                                    <option value="">SELECCIONE...</option>
                                    <?php foreach ($armas as $arma) : ?>
                                        <option value="<?= $arma['arm_codigo'] ?>">
                                            <?= $arma['arm_desc_md'] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                    <label for="asig_contingente"><i  class="fas fa-globe"></i> Seleccione Un Contingente </label><br>
                                    <select name="asig_contingente" id="asig_contingente" class="form-control">
                                        <option value="">SELECCIONE...</option>
                                        <?php foreach ($contingentes as $contingente) : ?>
                                        <option value="<?= $contingente['cont_id'] ?>">
                                            <?= $contingente['cont_nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                            </div>

                        <!--//!------------------------->

                        <div class="form-group col-md-6">
                            <label for="asp_dpi"><i class="fas fa-id-card"></i>DPI:</label>
                            <input type="text" class="form-control" id="asp_dpi" name="asp_dpi" placeholder="DPI">
                        </div>

                        <div class="form-group col-md-6">
                                    <label for="ing_puesto"><i class="fas fa-shield"></i> Seleccione Puesto a Desempeñar</label><br>
                                    <select name="ing_puesto" id="ing_puesto" class="form-control">
                                        <option value="">SELECCIONE...</option>
                                        <?php foreach ($puestos as $puesto) : ?>
                                        <option value="<?= $puesto['pue_id'] ?>">
                                            <?= $puesto['pue_nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                            </div>

                        <div id="contenedorDocumentos" class="col-lg-12">
                            <!-- Aquí se insertarán dinámicamente los campos -->
                        </div>

                        <div class="col-6 mx-auto" style="margin-top: 12px">
                            <button type="button" id="btnGuardar" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Guardar Registro
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </form>
        <br>
        <div id="containerBtnRegresar">
            <div class="row mb-3 d-flex">
                <center>
                    <h1>Menu Anterior</h1>
                </center>
                <br><br>
                <div style="margin-left:16%" class="col-8">
                    <button type="button" id="btnMilitar" class="btn btn-danger w-100">Regresar</button>
                </div>
            </div>
        </div>
        <br>
    </div>
    <script src="<?= asset('./build/js/aspirantes/index.js') ?>"></script>

</body>

</html>
