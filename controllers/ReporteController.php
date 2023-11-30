<?php

namespace Controllers;

use Model\Aprobado;
use Mpdf\Mpdf;
use MVC\Router;

class ReporteController
{

    public static function pdf(Router $router)
    {
        $cont_id = $_GET['cont_id'];

        $sql = "SELECT DISTINCT
                    ca.apro_asp AS asp_id,
                    TRIM(ca1.asp_nom1) || ' ' || TRIM(ca1.asp_nom2) || ' ' || TRIM(ca1.asp_ape1) || ' ' || TRIM(ca1.asp_ape2) AS nombre_aspirante,
                    c.cont_id,
                    c.cont_nombre AS nombre_contingente,
                    c.cont_fecha_inicio,
                    c.cont_fecha_final AS fecha_final_contingente,
                    ci.ing_id,
                    p.pue_nombre AS nombre_puesto
                FROM
                    cont_aprovados ca
                JOIN
                    cont_ingresos ci ON ca.apro_asp = ci.ing_aspirante
                JOIN
                    cont_puestos p ON ci.ing_puesto = p.pue_id
                JOIN
                    contingentes c ON ci.ing_contingente = c.cont_id
                JOIN
                    cont_aspirantes ca1 ON ca.apro_asp = ca1.asp_id
                WHERE
                    c.cont_id = $cont_id
                    AND ci.ing_situacion = 4
                ORDER BY
                    p.pue_nombre";

    $solicitud = Aprobado::fetchArray($sql);
    

        $mpdf = new Mpdf([
            "orientation" => "P",
            "default_font_size" => 12,
            "default_font" => "arial",
            "format" => "Legal",
            "mode" => 'utf-8'
        ]);

        $mpdf->SetMargins(30, 35, 25);

        $html = $router->load('reporte/pdf', ['solicitud' => $solicitud]);

        $htmlFooter = $router->load('reporte3/footer3',['solicitud' => $solicitud]);
        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        $mpdf->Output();

        
    }
};