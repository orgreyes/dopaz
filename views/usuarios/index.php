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
                            <label for="asp_catalogo"><i class="fas fa-id-card"></i> Número de Catálogo:</label>
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
                            <input type="text" class="form-control" id="asp_nom2"  name="asp_nom2" placeholder="Segundo Nombre">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="asp_ape1"><i class="fas fa-user-tie"></i> Apellidos:</label>
                            <input type="text" class="form-control" id="asp_ape1"  name="asp_ape1" placeholder="Primer apellido">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="asp_ape2"><i class="fas fa-user"></i></label>
                            <input type="text" class="form-control" id="asp_ape2"  name="asp_ape2" placeholder="Segundo apellido">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="asp_grado"><i class="fas fa-shield"></i>Grado:</label>
                            <input type="text" class="form-control" id="asp_grado"  name="asp_grado" placeholder="Grado">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="asp_arma"><i class="fas fa-crosshairs"></i>Arma:</label>
                            <input type="text" class="form-control" id="asp_arma"  name="asp_arma" placeholder="Arma">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="asp_genero"><i class="fas fa-venus-mars"></i>Genero:</label>
                            <input type="text" class="form-control" id="asp_genero"  name="asp_genero" placeholder="Genero">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="asp_dpi"><i class="fas fa-id-card"></i>DPI:</label>
                            <input type="text" class="form-control" id="asp_dpi"  name="asp_dpi" placeholder="DPI">
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
    <script src="<?= asset('./build/js/usuarios/index.js') ?>"></script>
</body>

</html>