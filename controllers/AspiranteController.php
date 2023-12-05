<?php

namespace Controllers;

use Exception;
use Model\Ingreso;
use Model\Aspirante;
use Model\Contingente;
use Model\Requisito;
use Model\Pdf;
use Model\Grado;
use Model\Arma;
use Model\Puesto;
use MVC\Router;

class AspiranteController
{
    public static function index(Router $router)
    {
        $contingentes = static::buscaContingentes();
        $armas = static::buscaArmas();
        $grados = static::buscarGrados();
        $puestos = static::buscarPuestoAPI();
        $router->render('aspirantes/index', [
             'contingentes' => $contingentes,
             'armas' => $armas,
            'puestos' => $puestos,
            'grados' => $grados,
        ]);
    }


    //!Funcion Select Armas
public static function buscaArmas()
{
    $sql = "SELECT *
    FROM armas
    ORDER BY arm_desc_md ASC";

    try {
        $armas = Arma::fetchArray($sql);
        return $armas;
    } catch (Exception $e) {
        return [];
    }
}

    //!Funcion Select Contingentes
public static function buscaContingentes(){
    $sql = "SELECT *
    FROM contingentes
    WHERE cont_situacion = 1
        AND cont_fecha_inicio >= (CURRENT YEAR TO MONTH) + 6 UNITS MONTH
        AND cont_fecha_inicio < (CURRENT YEAR TO MONTH) + 18 UNITS MONTH
    ORDER BY cont_nombre";

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
    $Grado = $_GET['pue_grado'];
    
    try {
        $sql = "SELECT p.*
        FROM cont_puestos p
        JOIN asig_grado_puesto agp ON p.pue_id = agp.asig_puesto
        JOIN grados g ON agp.asig_grado = g.gra_codigo
        WHERE p.pue_situacion = 1
        AND g.gra_codigo = $Grado
        ORDER BY p.pue_nombre";
        

            $Puestos = Puesto::fetchArray($sql);
        echo json_encode($Puestos);
            return $Puestos;
        } catch (Exception $e) {
            return [];
        }
}

//!Funcion Buscar
public static function buscarAPI()
{
    $catalogo = $_GET['asp_catalogo'];
    
    try {
        if ($catalogo != '') {
            $sql = "SELECT 
                        ca.asp_id,
                        ca.asp_catalogo,
                        TRIM(BOTH ' ' FROM ca.asp_dpi) AS asp_dpi, -- Elimina espacios al principio y al final
                        ca.asp_nom1, 
                        ca.asp_nom2, 
                        ca.asp_ape1, 
                        ca.asp_ape2, 
                        CASE
                            WHEN ca.asp_genero = 'M' THEN 'MASCULINO'
                            WHEN ca.asp_genero = 'F' THEN 'FEMENINO'
                            ELSE 'OTRO'
                        END AS asp_genero_desc,
                        mper.per_arma,
                        g.gra_codigo AS per_grado_id,
                        a.arm_codigo
                    FROM 
                        cont_aspirantes ca
                    LEFT JOIN 
                        mper ON ca.asp_catalogo = mper.per_catalogo
                    LEFT JOIN
                        grados g ON mper.per_grado = g.gra_codigo
                    LEFT JOIN
                        armas a ON mper.per_arma = a.arm_codigo
                    WHERE 
                        ca.asp_catalogo = $catalogo";

            $aspirantes = Aspirante::fetchArray($sql);
        
            echo json_encode($aspirantes);
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
public static function guardarAPI()
{
    try {
        $codigo = $_POST['ing_codigo'];
        $puesto = $_POST['ing_puesto'];
        $Id_Aspirante = $_POST['asp_id'];
        $contingente = $_POST['asig_contingente'];
        $fecha_hoy = date("d/m/Y");

        //!Aca se reciben los datos que se guardarán en otra tabla.
        $datos['ing_codigo'] = $codigo;
        $datos['ing_puesto'] = $puesto;
        $datos['ing_aspirante'] = $Id_Aspirante;
        $datos['ing_contingente'] = $contingente;
        $datos['ing_fecha_cont'] = $fecha_hoy;

        $ingresos = new Ingreso($datos);
        $result = $ingresos->guardar();
        //!Aca se captura el id que se crea.
        $ing_id = $result['id'];
        if ($result['resultado'] == 1) {
            //!Subir archivo y guardar en la base de datos
            $archivos = $_FILES['pdf_ruta'];
            $rutas = []; //!Aquí almacenaremos las rutas de los archivos

            foreach ($archivos['name'] as $index => $nombreArchivo) {
                //!Generar una ruta única para cada archivo
                $ruta = "../storage/$nombreArchivo" . uniqid() . ".pdf";
                $rutas[] = $ruta; // Almacenar la ruta en el arreglo

                //!Mover el archivo a la ruta generada
                $subido = move_uploaded_file($archivos['tmp_name'][$index], $ruta);
            }

            $PDFS['pdf_ingreso'] = $ing_id;
            $PDFS['pdf_ruta'] = $rutas;

            foreach ($rutas as $ruta) {
                $PDFS['pdf_ruta'] = $ruta;

                //!Crear un nuevo objeto Pdf con las mismas propiedades
                $pdf = new Pdf($PDFS);
                $pdfResultado = $pdf->guardar();
            }

            //!Solo envía una respuesta JSON al final
            if ($pdfResultado['resultado'] == 1) {
                echo json_encode([
                    'mensaje' => 'Registro guardado correctamente',
                    'codigo' => 1
                ]);
            } else {
                echo json_encode([
                    'mensaje' => 'Ocurrió un error al guardar el PDF',
                    'codigo' => 0
                ]);
            }
        } else {
            echo json_encode([
                'mensaje' => 'Ocurrió un error al guardar el registro principal',
                'codigo' => 0
            ]);
        }
    } catch (Exception $e) {
        //!Si hay una excepción, envía una respuesta JSON de error
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'El Aspirante ya fue Inscrito',
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
}