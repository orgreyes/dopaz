<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>AUTOCOM</title>
</head>

<body class="bg-light" >
    <nav class="navbar navbar-info bg-info fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/menu/"><img src="<?= asset('./images/A1.png') ?>" alt="logo" width="125px"></a>
            <div class="offcanvas offcanvas-start text-bg-light pb-3" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header bg-info">
                    <img src="<?= asset('./images/cit.png') ?>" alt="logo" width="45px">
                    <h5 class="offcanvas-title text-white" id="offcanvasDarkNavbarLabel">Menú principal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body d-flex flex-column justify-content-between">
                    <div class="accordion " id="menuPrincipal">
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="personal">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#personalDiv" aria-expanded="false" aria-controls="personal">
                                    <i class="bi bi-people-fill me-2"></i>Personal
                                </button>
                            </h2>
                            <div id="personalDiv" class="accordion-collapse collapse" aria-labelledby="personal" data-bs-parent="#menuPrincipal">
                                <ul class="list-group list-group-flush">
                                    <a href="" class="list-group-item list-group-item-action"><i class="bi bi-file-person me-2"></i>Información personal</a>
                                    <a href="" class="list-group-item list-group-item-action"><i class="bi bi-list me-2"></i>Sanciones</a>
                                    <a href="/sicomar/" class="list-group-item list-group-item-action"><i class="bi bi-list me-2"></i>Sicomar</a>
                                    <a href="/CODEMAR/" class="list-group-item list-group-item-action"><i class="bi bi-list me-2"></i>Codemar</a>
                                </ul>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="personal">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#inteligenciaDiv" aria-expanded="false" aria-controls="personal">
                                    <i class="bi bi-incognito me-2"></i>Inteligencia
                                </button>
                            </h2>
                            <div id="inteligenciaDiv" class="accordion-collapse collapse" aria-labelledby="personal" data-bs-parent="#menuPrincipal">
                                <ul class="list-group list-group-flush">
                                    <a href="/medios-comunicacion/" class="list-group-item list-group-item-action"><i class="bi bi-newspaper me-2"></i>Análisis de medios</a>
                                </ul>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="personal">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ministerioDiv" aria-expanded="false" aria-controls="personal">
                                    <i class="bi bi-bank me-2"></i>MDN
                                </button>
                            </h2>
                            <div id="ministerioDiv" class="accordion-collapse collapse" aria-labelledby="personal" data-bs-parent="#menuPrincipal">
                                <ul class="list-group list-group-flush">
                                    <a href="/correspondencia/" class="list-group-item list-group-item-action"><i class="bi bi-envelope-paper me-2"></i>Correspondencia</a>
                                </ul>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="personal">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#otrosDiv" aria-expanded="false" aria-controls="personal">
                                    <i class="bi bi-boxes me-2"></i>Otros
                                </button>
                            </h2>
                            <div id="otrosDiv" class="accordion-collapse collapse" aria-labelledby="personal" data-bs-parent="#menuPrincipal">
                                <ul class="list-group list-group-flush">
                                    <a href="/app-011/" class="list-group-item list-group-item-action"><i class="bi bi-shield-lock"></i> Ciberdefensa</a>
                                </ul>
                            </div>
                            <div id="otrosDiv" class="accordion-collapse collapse" aria-labelledby="personal" data-bs-parent="#menuPrincipal">
                                <ul class="list-group list-group-flush">
                                    <a href="/prueba-scanner-qr/" class="list-group-item list-group-item-action"><i class="bi bi-qr-code-scan me-2"></i>Scanner qr</a>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col px-3">
                            <a href="/login/logout" class="btn btn-danger w-100"><i class="bi bi-"></i>Salir</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">

        <?php echo $contenido; ?>
    </div>
    <div class="container-fluid ">
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold;">
                    Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>
</body>

</html>