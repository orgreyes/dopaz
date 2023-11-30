<body>
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
</style>
<div class="container"  id="containerBtn">
<center><h1>Seleccion el Listado De Personal Mediante Contingentes</h1></center>
    <div id="contenedorBotones" class="row justify-content-center mt-4">
                             <div class="row mb-3 col-md-6">
                                <div class="col">
                                    <label for="cont_id"><i  class="fas fa-globe"></i> Seleccione Un Contingente </label><br>
                                    <select name="cont_id" id="cont_id" style="width: 100%; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
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
    <div class="form-row" style="margin-top:15px">
        <div class="form-group col-md-12">
            <button type="button" id="btnPdf" class="btn btn-outline-dark custom-button button-with-shadow">
                <img src="./images/PDF.png" alt="pdf" style="width: 50px; height: 50px; display: block; margin: 0 auto;">
            </button>
        </div>
    </div>
</center>



<div id="tablaEvaluacionContainer" class="container mt-1">
            <div class="row justify-content-center mt-4">
                <div class="col-12 p-4 shadow"> 
                    <div class="text-center">
                        <h1>Lista De Personal que ha Aprovado el Proceso de Seleccion</h1>
                    </div>
            <table id="tablaEvaluacion" class="table table-bordered table-hover">
                <!-- Contenido de la tabla -->
            </table>
        </div>
    </div>

<script src="<?= asset('./build/js/aprobados/index.js') ?>"></script>
</body>
</html>