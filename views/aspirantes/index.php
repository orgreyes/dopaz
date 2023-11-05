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
            /* Corrección: cambié "margin bottom" por "margin-bottom" */
        }

        .form-container label {
            font-weight: bold;
            /* Corrección: cambié "font weight" por "font-weight" */
        }


        .form-container .name-input-group {
            display: flex;
        }
    </style>
</head>

<body>
    <div class="container mt-4"> 
        <h2>Ingreso de Personal Aspirante para Contingente</h2>

        <form class="form-container" id="formularioUsuarios">

            <div class="row">
                <div class="col-md-3">
                    <img src="./images/foto.jpg" alt="Fotografía">
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
                            </div>
                            <div class="form-group col-md-6">
                                <label for="asp_id"><i class="fas fa-user"></i></label>
                                <input type="text" class="form-control" id="asp_id"  name="asp_id" placeholder="ID">
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
                            <label for="ing_puesto"><i class="fas fa-shield"></i>Seleccione el Puesto solicitado</label>
                            <select name="ing_puesto" id="ing_puesto" class="form-control">
                                <option value="">SELECCIONE...</option>
                                <?php foreach ($puestos as $puesto) : ?>
                                    <option value="<?= $puesto['pue_id'] ?>">
                                        <?= $puesto['pue_nombre'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>


                    
                        <br>
                        <div class="col-12">
                        <button type="button" id="btnGuardar" class="btn btn-info w-100">Mandar solicitud</button>
                    </div>
                    
                    </div>


                </div>
            </div>
        </form>
    </div>
    <script src="<?= asset('./build/js/aspirantes/index.js') ?>"></script>
</body>

</html>