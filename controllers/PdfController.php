<?php

namespace Controllers;

use Exception;
use Model\Autorizacion;
use Model\Matrimonio;
use Model\Motivos;
use Model\ParejaCivil;
use Model\ParejaMilitar;
use Model\Pdf;
use Model\Personal;
use Model\Solicitante;
use Model\Solicitud;

use MVC\Router;

class CasamientoController
{
    public static function index(Router $router)
    {
        $motivos = static::motivos();

        $router->render('casamientos/index', [
            'motivos' => $motivos
        ]);
    }


    public static function guardarApi()
    {

        try {

            //$identificador = static::generaridentificador();
            $catalogo_doc = $_POST['ste_cat'];
            //$_POST['identificador'] = $identificador;

            $fechaAutorizacion = $_POST['aut_fecha'];
            $fechaFormateadaAutorizacion = date('Y-m-d H:i', strtotime($fechaAutorizacion));
            $_POST['aut_fecha'] = $fechaFormateadaAutorizacion;

            $fechaSolicito = $_POST['ste_fecha'];
            $fechaFormateadaSolicito = date('Y-m-d H:i', strtotime($fechaSolicito));
            $_POST['ste_fecha'] = $fechaFormateadaSolicito;

            $fechaIncioLicencia = $_POST['mat_fecha_lic_ini'];
            $fechaFormateadaIniLic = date('Y-m-d H:i', strtotime($fechaIncioLicencia));
            $_POST['mat_fecha_lic_ini'] = $fechaFormateadaIniLic;

            $fechaFinLicencia = $_POST['mat_fecha_lic_fin'];
            $fechaFormateadaFinLic = date('Y-m-d H:i', strtotime($fechaFinLicencia));
            $_POST['mat_fecha_lic_fin'] = $fechaFormateadaFinLic;

            $fechaBodaC = $_POST['mat_fecha_bodac'];
            $fechaFormateadaBodaC = date('Y-m-d H:i', strtotime($fechaBodaC));
            $_POST['mat_fecha_bodac'] =  $fechaFormateadaBodaC;

            $fechaBodaR = $_POST['mat_fecha_bodar'];
            $fechaFormateadaBodaR = date('Y-m-d H:i', strtotime($fechaBodaR));
            $_POST['mat_fecha_bodar'] =  $fechaFormateadaBodaR;
            
            $_POST = array_map('strtoupper', $_POST);

            $solicitante = new Solicitante($_POST);
            $solicitanteResultado = $solicitante->crear();

            if ($solicitanteResultado['resultado'] == 1) {
                $solicitanteId = $solicitanteResultado['id'];

                $solicitud = new Solicitud($_POST);
                $solicitud->sol_solicitante = $solicitanteId;
                $solicitudResultado = $solicitud->crear();

                if ($solicitudResultado['resultado'] == 1) {
                    $solicitudId = $solicitudResultado['id'];

                    $archivo = $_FILES['pdf_ruta'];
                    $ruta = "../storage/matrimonio/$catalogo_doc" . uniqid() . ".pdf";
                    $subido = move_uploaded_file($archivo['tmp_name'], $ruta);

                    if ($subido) {
                        $pdf = new Pdf([
                            'pdf_solicitud' => $solicitudId,
                            'pdf_ruta' => $ruta
                        ]);
                        $pdfResultado = $pdf->crear();

                        if ($pdfResultado['resultado'] == 1) {
                            $autorizacion = new Autorizacion($_POST);
                            $autorizacion->aut_solicitud = $solicitudId;
                            $autorizacionResultado = $autorizacion->crear();
                            if ($autorizacionResultado['resultado'] == 1) {
                                if (!empty($_POST['parejac_nombres']) && !empty($_POST['parejac_apellidos']) && !empty($_POST['parejac_dpi'])) {
                                    $parejaCivil = new ParejaCivil($_POST);
                                    $parejaCivilResultado = $parejaCivil->crear();
                                } else {
                                    $parejaCivilResultado = ['resultado' => 0];
                                }

                                if (!empty($_POST['nombre4']) && !empty($_POST['parejam_cat'])) {
                                    $parejaMilitar = new ParejaMilitar($_POST);
                                    $parejaMilitarResultado = $parejaMilitar->crear();
                                } else {
                                    $parejaMilitarResultado = ['resultado' => 0];
                                }

                                if ($parejaCivilResultado['resultado'] == 1) {
                                    $matrimonio = new Matrimonio($_POST);
                                    $matrimonio->mat_autorizacion = $autorizacionResultado['id'];
                                    $matrimonio->mat_per_civil = $parejaCivilResultado['id'];
                                    $matrimonioResultado = $matrimonio->crear();
                                } elseif ($parejaMilitarResultado['resultado'] == 1) {
                                    $matrimonio = new Matrimonio($_POST);
                                    $matrimonio->mat_autorizacion = $autorizacionResultado['id'];
                                    $matrimonio->mat_per_army = $parejaMilitarResultado['id'];
                                    $matrimonioResultado = $matrimonio->crear();
                                } else {
                               
                                    exit;
                                }
                            } else {
                               
                                exit;
                            }
                        } else {
                           
                            exit;
                        }
                    } else {
                      
                        exit;
                    }
                } else {
                 
                    exit;
                }
            } else {
             
                exit;
            }

            if ($matrimonioResultado['resultado'] == 1) {

                echo json_encode([
                    'mensaje' => 'Registro guardado correctamente',
                    'codigo' => 1
                ]);
            }
            // echo json_encode($resultado);
        } catch (Exception $e) {
            echo json_encode([
                'detalle' => $e->getMessage(),
                'mensaje' => 'Ocurrió un error',
                'codigo' => 0
            ]);
        }
    }


    public static function buscarApi()
    {
        
        $catalogo = $_GET['catalogo'];
        $fecha = $_GET['fecha'];

        $sql = "SELECT  
        ste_id,
        sal_id,
        ste_cat,
        gra_desc_lg,
        sol_situacion,
        TRIM(per_nom1) || ' ' || TRIM(per_nom2) || ' ' || TRIM(per_ape1) || ' ' || TRIM(per_ape2) nombre,
        sal_salida,
        sal_ingreso, 
        pdf_id,
        pdf_solicitud,
        sol_id,
        pdf_ruta
        FROM se_salpais
        inner join se_autorizacion on aut_id = sal_autorizacion
        inner join se_solicitudes on aut_solicitud = sol_id
        inner join se_solicitante on sol_solicitante=ste_id
        inner join mper on ste_cat = per_catalogo
        inner join grados on ste_gra = gra_codigo
        inner join se_pdf on pdf_solicitud = sol_id 
        AND (sol_situacion = 1 OR sol_situacion = 7)
         ORDER BY ste_fecha DESC ";
         if ($fecha != '') {
            $sql .= " AND cast(ste_fecha as date) = '$fecha' ";
        }
        if ($catalogo != '') {
            $sql .= " AND ste_cat = '$catalogo'";
        }

        $valores = [];

        try {
            $resultado = Salidapais::fetchArray($sql);

            foreach ($resultado as $key => $value) {
                $paises = '';
                $ciudad = '';

                $id = $value['sal_id'];

                $sql1 = "SELECT 
                dsal_sol_salida,
                pai_desc_lg as nombre_pais, 
                dsal_ciudad as ciudad
            FROM se_dsalpais
            INNER JOIN se_salpais ON dsal_sol_salida = sal_id
            INNER JOIN paises ON dsal_pais = pai_codigo
            where dsal_sol_salida = $id";

                $resultado1 = Salidapais::fetchArray($sql1);

                foreach ($resultado1 as $key1 => $value1) {


                    $paises .= ($paises != '' && $value1['nombre_pais'] != null) ? ', ' : '';
                    $paises .= trim($value1['nombre_pais']);

                    $ciudad .= ($ciudad != '' && $value1['ciudad'] != null) ? ', ' : '';
                    $ciudad .= trim($value1['ciudad']);
                }
                $valores[] = [
                    'sal_id' => $value['sal_id'],
                    'gra_desc_lg' => $value['gra_desc_lg'],
                    'nombre' => $value['nombre'],
                    'sal_salida' => $value['sal_salida'],
                    'sal_ingreso' => $value['sal_ingreso'],
                    'pdf_ruta' => $value['pdf_ruta'],
                    'pdf_id' => $value['pdf_id'],
                    'pdf_solicitud' => $value['pdf_solicitud'],
                    'ste_id' => $value['ste_id'],
                    'sol_id' => $value['sol_id'],
                    'sol_situacion' => $value['sol_situacion'],
                    'paises' => $paises,
                    'ciudad' => $ciudad
                ];
            }
        
            echo json_encode($valores);
            
        } catch (Exception $e) {
            echo json_encode([
                'detalle' => $e->getMessage(),
                'mensaje' => 'Ocurrió un error',
                'codigo' => 0
            ]);
        }
    }


    //!Esta es la funcion para Que se ejecute el boton para ver el PDF que el asp. subio cuando 
    //se inscribo
    public static function VerPdf(Router $router)
    {

        $ruta = base64_decode(base64_decode(base64_decode($_GET['ruta'])));

        $router->printPDF($ruta);
    }
}