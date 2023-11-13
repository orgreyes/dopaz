<?php

namespace Model;

class Puesto extends ActiveRecord{
    protected static $tabla = 'cont_puestos';
    protected static $columnasDB = ['pue_nombre','pue_situacion'];
    protected static $idTabla = 'pue_id';

    public $pue_id;
    public $pue_nombre;
    public $pue_situacion;

    public function __construct($args = [])
    {
        $this->pue_id = $args['pue_id'] ?? null;
        $this->pue_nombre = $args['pue_nombre'] ?? '';
        $this->pue_situacion = $args['pue_situacion'] ?? '1';
    }
}
