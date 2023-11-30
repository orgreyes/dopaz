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
</style>  
  <div class="container">
  <div class="container mt-1">
        <h1 class="text-center">Reporte De la Cantidad de Usuarios por Fases</h1>
        <div class="text-center">
        
        <div class="container"  id="containerBtn">
<center><h1></h1></center>
    <div id="contenedorBotones" class="row justify-content-center mt-4">
                             <div class="row mb-3 col-md-6">
                                <div class="col">
                                    <label for="cont_id"><i  class="fas fa-globe"></i><b> Seleccione Un Contingente Para Consultar Grafica </b></label><br>
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
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow mt-3">
                    <div class="card-body">
                        <canvas id="chartEstados" style="width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?=asset('./build/js/estadisticas/index.js')?>"></script>
  </div>

</body>
</html>