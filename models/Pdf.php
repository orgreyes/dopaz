<?php

namespace Model;

class Pdf extends ActiveRecord{
    protected static $tabla = 'cont_pdf';
    protected static $columnasDB = ['pdf_ruta','pdf_ingreso','pdf_situacion'];
    protected static $idTabla = 'pdf_id';

    public $pdf_id;
    public $pdf_ruta;
    public $pdf_ingreso;
    public $pdf_situacion;

    public function __construct($args = [])
    {
        $this->pdf_id = $args['pdf_id'] ?? null;
        $this->pdf_ruta = $args['pdf_ruta'] ?? '';
        $this->pdf_ingreso = $args['pdf_ingreso'] ?? '';
        $this->pdf_situacion = $args['pdf_situacion'] ?? '1';
    }
}
