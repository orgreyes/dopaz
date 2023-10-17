<div class="row justify-content-center mt-5">
    <div class="card col-lg-7 p-0" style="min-height:60vh;">
        <img src="<?= asset('./images/header.jpg') ?>" class="card-img-top" alt="header">
        <div class="card-body text-center">
            <p class="card-text fw-bold">BIENVENIDO: <?= $usuario['grado'] .' '. $usuario['nombre'] ?> </p>
            <p class="card-text ">DEPENDENCIA:  <?= $usuario['dependencia'] ?> </p>
            <figure>
                <blockquote class="blockquote">
                    <p class="text-muted">"La nación que insista apartar a los hombres de guerra de los pensadores, se expone a que sus guerras las hagan los necios y sus pensamientos los cobardes".</p>
                </blockquote>
                <figcaption class="blockquote-footer text-muted">
                    W. M. Butler.
                </figcaption>
            </figure>
        </div>
    </div>

</div>
<script src="build/js/inicio.js"></script>