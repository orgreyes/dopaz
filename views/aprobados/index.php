<body>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        #containerBtn {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        #containerBtn2 {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        #containerBtn3 {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        #containerBtn h1 {
            text-align: center;
            color: #333;
        }

        #contenedorBotones {
            text-align: center;
            margin-top: 20px;
        }

        /* Estilo para los botones (puedes ajustar según tus necesidades) */
        #containerBtn .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-align: center;
            text-decoration: none;
            background-color: #4CAF50;
            color: #fff;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Cambio de color al pasar el ratón sobre el botón */
        #containerBtn .btn:hover {
            background-color: #45a049;
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
<div class="container"  id="containerBtn">
<center><h1>Seleccion el Listado De Personal Mediante Contingentes</h1></center>
    <div id="contenedorBotones" class="row justify-content-center mt-4">
                             <div class="row mb-3 col-md-6">
                                <div class="col">
                                    <label for="cont_id"><i  class="fas fa-globe"></i> Seleccione Un Contingente </label><br>
                                    <select name="cont_id" id="cont_id"  style="width: 100%; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                                        <option value="">SELECCIONE...</option>
                                        <?php foreach ($contingentes as $contingente) : ?>
                                        <option value="<?= $contingente['cont_id'] ?>">
                                            <?= $contingente['cont_nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
    </div>
</div>

<center>
    <div class="form-row" disabled style="margin-top:15px">
        <div class="form-group col-md-12">
            <button type="button" id="btnPdf" class="btn btn-outline-dark custom-button button-with-shadow">
                <img src="./images/PDF.png" alt="pdf" disabled style="width: 50px; height: 50px; display: block; margin: 0 auto;">
            </button>
        </div>
    </div>
</center>



<div id="tablaEvaluacionContainer" class="container mt-1">
            <div class="row justify-content-center mt-4">
                <div class="col-12 p-4 shadow"> 
                    <div class="text-center">
                        <h1>Lista De Personal que ha Aprobado el Proceso de Seleccion</h1>
                    </div>
            <table id="tablaEvaluacion" class="table table-bordered table-hover">
                <!-- Contenido de la tabla -->
            </table>
        </div>
    </div>

    <!-- //!Modal -->
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="modalDetallesLabel">INFORMACION PERSONAL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div id="formulario" class="container mt-4">
            <form class="form-container" id="formularioPersonal" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <img for="asp_catalogo" id="foto" src="./images/foto.jpg" alt="Fotografía">
                    </div>
                    <div class="col-md-9">

                        <div class="form-row" id="InputCatalogo">
                            <div class="form-group col-md-6">
                                <label for="asp_catalogo"><i class="fas fa-id-card"></i>  Catalogo</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" disabled id="asp_catalogo" name="asp_catalogo">
                                </div>
                            </div>
                        </div>

                            <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="asp_nom1"><i class="fas fa-user-tie"></i> Nombres:</label>
                                <input type="text" class="form-control" disabled id="asp_nom1" name="asp_nom1" placeholder="Primer Nombre">
                            </div>


                                <div class="form-group col-md-6">
                                    <label for="asp_nom2"><i class="fas fa-user"></i></label>
                                    <input type="text" class="form-control" disabled id="asp_nom2" name="asp_nom2" placeholder="Segundo Nombre">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="asp_ape1"><i class="fas fa-user-tie"></i> Apellidos:</label>
                                    <input type="text" class="form-control" disabled id="asp_ape1" name="asp_ape1" placeholder="Primer Apellido">
                                </div>
                            <div class="form-group col-md-6">
                                <label for="asp_ape2"><i class="fas fa-user"></i></label>
                                <input type="text" class="form-control" id="asp_ape2" name="asp_ape2" disabled placeholder="Segundo Apellido">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="asp_genero"><i class="fas fa-venus-mars"></i>Genero:</label>
                                <input type="text" class="form-control" id="asp_genero" disabled  name= "asp_genero"  placeholder="Genero">
                            </div>


                            <!--//!------------------------->
                            <div class="form-group col-md-6">
                                <label for="per_grado"><i class="fas fa-shield"></i> Grado:</label>
                                <input type="text" class="form-control" id="per_grado" disabled  name= "per_grado"  placeholder="Grado">
                            </div>

                            

                            
                            <div class="form-group col-md-6">
                                <label for="asp_puesto"><i class="fas fa-id-shield"></i>Puesto:</label>
                                <input type="text" class="form-control" id="asp_puesto" disabled  name= "asp_puesto" placeholder="PUESTO">
                            </div>

                            
                            <div class="form-group col-md-6">
                                <label for="asp_dpi"><i class="fas fa-id-card"></i>DPI:</label>
                                <input type="text" class="form-control" id="asp_dpi" disabled  name= "asp_dpi" placeholder="DPI">
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="buttonAnterior" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= asset('./build/js/aprobados/index.js') ?>"></script>
</body>
</html>