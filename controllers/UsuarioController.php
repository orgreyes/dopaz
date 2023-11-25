<?php

namespace Controllers;

use Exception;
use Model\Ingreso;
use Model\Contingente;
use Model\Aspirante;
use Model\Usuario;
use Model\Grado;
use Model\Pdf;
use Model\Requisito;
use Model\Arma;
use Model\Puesto;
use MVC\Router;


class UsuarioController
{
    public static function index(Router $router)
    {
        $contingentes = static::buscaContingentes();
        $grados = static::buscarGrados();
        $puestos = static::buscarPuestoAPI();
        
        $router->render('usuarios/index', [
            'contingentes' => $contingentes,
            'puestos' => $puestos,
            'grados' => $grados,
        ]);
    }

//!Funcion Select Contingentes
public static function buscaContingentes()
{
    $sql = "SELECT *
    FROM contingentes
    WHERE cont_situacion = 1
        AND cont_fecha_inicio > TODAY";

    try {
        $contingentes = Contingente::fetchArray($sql);
        return $contingentes;
    } catch (Exception $e) {
        return [];
    }
}
//!Funcion Select Grados
    public static function buscarGrados()
    {
        $sql = "SELECT * FROM grados
        ORDER BY gra_desc_md ASC";

        try {
            $grados = Grado::fetchArray($sql);
            return $grados;
        } catch (Exception $e) {
            return [];
        }
    }
//!Funcion Select Puestos
    public static function buscarPuestoAPI()
    {
        $grado = $_GET['pue_grado'];

        try {

        $sql = "
        SELECT p.*
                FROM cont_puestos p
                JOIN asig_grado_puesto agp ON p.pue_id = agp.asig_puesto
                JOIN grados g ON agp.asig_grado = g.gra_codigo
                WHERE p.pue_situacion = 1
                AND g.gra_codigo = $grado";

            $puestos = Puesto::fetchArray($sql);
            echo json_encode($puestos);
            return $puestos;
        } catch (Exception $e) {
            return [];
        }
    }


//!Funcion Buscar
public static function buscarAPI()
{
    $catalogo = $_GET['per_catalogo'];
    
    try {
        if ($catalogo != '') {
            $sql = "SELECT 
                per_catalogo,
                per_nom1,
                per_nom2,
                per_ape1,
                per_ape2,
                per_dpi,
                CASE 
                    WHEN per_sexo = 'M' THEN 'MASCULINO'
                    WHEN per_sexo = 'F' THEN 'FEMENINO'
                    ELSE 'DESCONOCIDO'
                END AS per_sexo,
                grados.gra_codigo as per_grado_id,
                armas.arm_desc_md
            FROM mper
            INNER JOIN grados ON mper.per_grado = grados.gra_codigo
            INNER JOIN armas ON mper.per_arma = armas.arm_codigo
            where per_catalogo = $catalogo
            ";
            $usuarios = Usuario::fetchArray($sql);
        
            echo json_encode($usuarios);
        } else {
            echo json_encode([
                'mensaje' => 'Por favor, ingrese un número de catálogo',
                'codigo' => 1
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'Ocurrió un error',
            'codigo' => 0
        ]);
    }
}










//!Funcion Guardar
public static function guardarAPI() {
    try {
        echo json_encode($_POST);
        exit;
        $catalogo_doc = $_POST['asp_catalogo'];
        $codigo = $_POST['ing_codigo'];
        $puesto = $_POST['ing_puesto'];
        $contingente = $_POST['asig_contingente'];
        $fecha_hoy = date("d/m/Y");

        $aspirante = new Aspirante($_POST);
        $resultado = $aspirante->crear();

        // Aca se captura el id que se crea.
        $id_aspirante = $resultado['id'];

        // Aca se recibe los datos que se guardaran en otra tabla.
        $datos['ing_codigo'] = $codigo;
        $datos['ing_aspirante'] = $id_aspirante;
        $datos['ing_fecha_cont'] = $fecha_hoy;
        $datos['ing_puesto'] = $puesto;
        $datos['ing_contingente'] = $contingente;

        $ingresos = new Ingreso($datos);
        $result = $ingresos->guardar();

        if ($result['resultado'] == 1) {
            // Subir archivo y guardar en la base de datos
            $archivo = $_FILES['pdf_ruta'];

            // Agregar una verificación para asegurarse de que se haya enviado un archivo
            if ($archivo['error'] === UPLOAD_ERR_OK) {
                $ruta = "./storage/$catalogo_doc" . uniqid() . ".pdf";
                $subido = move_uploaded_file($archivo['tmp_name'], $ruta);

                if ($subido) {
                    $PDFS['pdf_ingreso'] = $codigo;
                    $PDFS['pdf_ruta'] = $ruta;
                    $pdf = new Pdf($PDFS);
                    $pdfResultado = $pdf->guardar();

                    // Solo envía una respuesta JSON al final
                    echo json_encode([
                        'mensaje' => 'Registro guardado correctamente',
                        'codigo' => 1
                    ]);
                } else {
                    echo json_encode([
                        'mensaje' => 'Error al subir el archivo PDF',
                        'codigo' => 0
                    ]);
                }
            } else {
                echo json_encode([
                    'mensaje' => 'No se ha enviado ningún archivo PDF',
                    'codigo' => 0
                ]);
            }
        } else {
            echo json_encode([
                'mensaje' => 'Ocurrió un error al guardar el aspirante',
                'codigo' => 0
            ]);
        }
    } catch (Exception $e) {
        // Si hay una excepción, envía una respuesta JSON de error
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'Error en el proceso',
            'codigo' => 2
        ]);
    }
}


public static function obtenerRequisitosAPI()
{
    try {
        if (isset($_GET['pue_id'])) {
            $puestoId = $_GET['pue_id'];

            // Validar que $puestoId sea un número válido
            if (!is_numeric($puestoId)) {
                echo json_encode([
                    'mensaje' => 'El ID del puesto debe ser un número válido',
                    'codigo' => 1
                ]);
                return;
            }

            // Consulta SQL para obtener nombres de requisitos
            $query1 = "SELECT
                            cp.pue_id,
                            cp.pue_nombre AS puesto,
                            cr.req_nombre AS requisito
                        FROM
                            cont_puestos cp
                        JOIN
                            cont_asig_requisitos car ON cp.pue_id = car.asig_req_puesto
                        JOIN
                            cont_requisitos cr ON car.asig_req_requisito = cr.req_id
                        WHERE
                            cp.pue_id = $puestoId AND
                            cp.pue_situacion = 1 AND
                            car.asig_req_situacion = 1 AND
                            cr.req_situacion = 1";

            $nombreRequisitos = Requisito::fetchArray($query1);

            // Consulta SQL para obtener la cantidad de requisitos y sus nombres
            $query2 = "SELECT
                        cp.pue_nombre AS puesto,
                        COUNT(car.asig_req_id) AS cantidad_requisitos
                    FROM
                        cont_puestos cp
                    JOIN
                        cont_asig_requisitos car ON cp.pue_id = car.asig_req_puesto
                    JOIN
                        cont_requisitos cr ON car.asig_req_requisito = cr.req_id
                    WHERE
                        cp.pue_id = $puestoId AND
                        cp.pue_situacion = 1 AND
                        car.asig_req_situacion = 1 AND
                        cr.req_situacion = 1
                    GROUP BY
                        cp.pue_nombre";

            // Cambiar $sql a $query2
            $usuarios = Requisito::fetchArray($query2);

            // Crear un array asociativo con ambas consultas
            $response = [
                'nombreRequisitos' => $nombreRequisitos,
                'usuarios' => $usuarios
            ];

            // Devolver el array como JSON
            echo json_encode($response);
        } else {
            echo json_encode([
                'mensaje' => 'Por favor, ingrese un número de catálogo',
                'codigo' => 1
            ]);
        }
    } catch (Exception $e) {
        // Registrar el error en el log
        error_log('Error en la función obtenerRequisitosAPI: ' . $e->getMessage());

        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'Ocurrió un error',
            'codigo' => 0
        ]);
    }
}








// //!Funcion Guardar
// public static function guardarAPI() {
//     try {
//         // Validación y sanitización de las entradas
//         $catalogo = isset($_POST['asp_catalogo']) ? $_POST['asp_catalogo'] : '';
//         $codigo = isset($_POST['ing_codigo']) ? $_POST['ing_codigo'] : '';
//         $puesto = isset($_POST['ing_puesto']) ? $_POST['ing_puesto'] : '';
//         $contingente = isset($_POST['asig_contingente']) ? $_POST['asig_contingente'] : '';

//         // Validar que se haya enviado un archivo
//         if (!isset($_FILES['pdf_ruta']) || !is_uploaded_file($_FILES['pdf_ruta']['tmp_name'])) {
//             throw new Exception('No se ha proporcionado un archivo PDF válido.');
//         }

//         // Mover el archivo a la ruta deseada
//         $archivo = $_FILES['pdf_ruta'];
//         $ruta = "../storage/$catalogo" . uniqid() . ".pdf";
//         if (!move_uploaded_file($archivo['tmp_name'], $ruta)) {
//             throw new Exception('Error al mover el archivo PDF.');
//         }

//         // Crear instancia de Pdf después de asegurarse de que el archivo se ha subido correctamente
//         $pdf = new Pdf([
//             'ing_id' => $Id_Aspirante, // No está claro de dónde proviene esta variable, asegúrate de definirla
//             'pdf_ruta' => $ruta
//         ]);
//         $pdfResultado = $pdf->crear();

//         // Crear instancia de Aspirante y guardar en la base de datos
//         $aspirante = new Aspirante($_POST);
//         $resultado = $aspirante->crear();

//         // Obtener el ID del aspirante creado
//         $id_aspirante = $resultado['id'];

//         // Crear instancia de Ingreso y guardar en la base de datos
//         $datos['ing_codigo'] = $codigo;
//         $datos['ing_aspirante'] = $id_aspirante;
//         $datos['ing_fecha_cont'] = date("Y-m-d"); // Formato de fecha compatible con MySQL
//         $datos['ing_puesto'] = $puesto;
//         $datos['ing_contingente'] = $contingente;

//         $ingresos = new Ingreso($datos);
//         $result = $ingresos->guardar();

//         // Enviar respuesta JSON al final
//         if ($result['resultado'] == 1) {
//             echo json_encode([
//                 'mensaje' => 'Registro guardado correctamente',
//                 'codigo' => 1
//             ]);
//         } else {
//             echo json_encode([
//                 'mensaje' => 'Ocurrió un error al guardar en la base de datos',
//                 'codigo' => 0
//             ]);
//         }
//     } catch (Exception $e) {
//         // Loguear la excepción en lugar de mostrar el mensaje directamente al usuario
//         error_log($e->getMessage());

//         echo json_encode([
//             'detalle' => 'Ocurrió un error en el servidor',
//             'mensaje' => 'Por favor, intenta nuevamente más tarde',
//             'codigo' => 2
//         ]);
//     }
// }






//!ESTA FUNCION IRA EN INGRESOS SOLO PARA COLOCAR LOS PEDFS A LA PAR DE LOS REQUISITOS
// public static function buscarPDF()
// {
    
//     $catalogo = $_GET['catalogo'];
//     $fecha = $_GET['fecha'];

//     $sql = "SELECT  
//     ste_id,
//     sal_id,
//     ste_cat,
//     gra_desc_lg,
//     sol_situacion,
//     TRIM(per_nom1) || ' ' || TRIM(per_nom2) || ' ' || TRIM(per_ape1) || ' ' || TRIM(per_ape2) nombre,
//     sal_salida,
//     sal_ingreso, 
//     pdf_id,
//     pdf_solicitud,
//     sol_id,
//     pdf_ruta
//     FROM se_salpais
//     inner join se_autorizacion on aut_id = sal_autorizacion
//     inner join se_solicitudes on aut_solicitud = sol_id
//     inner join se_solicitante on sol_solicitante=ste_id
//     inner join mper on ste_cat = per_catalogo
//     inner join grados on ste_gra = gra_codigo
//     inner join se_pdf on pdf_solicitud = sol_id 
//     AND (sol_situacion = 1 OR sol_situacion = 7)
//      ORDER BY ste_fecha DESC ";
//      if ($fecha != '') {
//         $sql .= " AND cast(ste_fecha as date) = '$fecha' ";
//     }
//     if ($catalogo != '') {
//         $sql .= " AND ste_cat = '$catalogo'";
//     }

//     $valores = [];

//     try {
//         $resultado = Salidapais::fetchArray($sql);

//         foreach ($resultado as $key => $value) {
//             $paises = '';
//             $ciudad = '';

//             $id = $value['sal_id'];

//             $sql1 = "SELECT 
//             dsal_sol_salida,
//             pai_desc_lg as nombre_pais, 
//             dsal_ciudad as ciudad
//         FROM se_dsalpais
//         INNER JOIN se_salpais ON dsal_sol_salida = sal_id
//         INNER JOIN paises ON dsal_pais = pai_codigo
//         where dsal_sol_salida = $id";

//             $resultado1 = Salidapais::fetchArray($sql1);

//             foreach ($resultado1 as $key1 => $value1) {


//                 $paises .= ($paises != '' && $value1['nombre_pais'] != null) ? ', ' : '';
//                 $paises .= trim($value1['nombre_pais']);

//                 $ciudad .= ($ciudad != '' && $value1['ciudad'] != null) ? ', ' : '';
//                 $ciudad .= trim($value1['ciudad']);
//             }
//             $valores[] = [
//                 'sal_id' => $value['sal_id'],
//                 'gra_desc_lg' => $value['gra_desc_lg'],
//                 'nombre' => $value['nombre'],
//                 'sal_salida' => $value['sal_salida'],
//                 'sal_ingreso' => $value['sal_ingreso'],
//                 'pdf_ruta' => $value['pdf_ruta'],
//                 'pdf_id' => $value['pdf_id'],
//                 'pdf_solicitud' => $value['pdf_solicitud'],
//                 'ste_id' => $value['ste_id'],
//                 'sol_id' => $value['sol_id'],
//                 'sol_situacion' => $value['sol_situacion'],
//                 'paises' => $paises,
//                 'ciudad' => $ciudad
//             ];
//         }
    
//         echo json_encode($valores);
        
//     } catch (Exception $e) {
//         echo json_encode([
//             'detalle' => $e->getMessage(),
//             'mensaje' => 'Ocurrió un error',
//             'codigo' => 0
//         ]);
//     }
// }


//!Esta es la funcion para Que se ejecute el boton para ver el PDF que el asp. subio cuando 
//!se inscribo Esta fucnion ira tambien en Ingresos para ver los PDF.
// public static function VerPdf(Router $router)
// {

//     $ruta = base64_decode(base64_decode(base64_decode($_GET['ruta'])));

//     $router->printPDF($ruta);
// }

}