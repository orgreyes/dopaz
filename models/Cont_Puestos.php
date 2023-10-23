<?php

namespace Model;

class Cont_Puestos extends ActiveRecord{
    protected static $tabla = 'cont_puestos';
    protected static $columnasDB = ['pue_nombre','pue_grado','pue_situacion'];
    protected static $idTabla = 'pue_id';

    public $pue_id;
    public $pue_nombre;
    public $pue_grado;
    public $pue_situacion;

    public function __construct($args = [])
    {
        $this->pue_id = $args['pue_id'] ?? null;
        $this->pue_nombre = $args['pue_nombre'] ?? '';
        $this->pue_grado = $args['pue_grado'] ?? '';
        $this->pue_situacion = $args['pue_situacion'] ?? '1';
    }
}
