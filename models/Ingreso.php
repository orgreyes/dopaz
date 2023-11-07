<?php

namespace Model;

class Ingreso extends ActiveRecord{
    protected static $tabla = 'cont_ingresos';
    protected static $columnasDB = ['ing_aspirante','ing_contingente','ing_fecha_cont','ing_puesto','ing_codigo','ing_situacion'];
    protected static $idTabla = 'ing_id';

    public $ing_id;
    public $ing_aspirante;
    public $ing_contingente;
    public $ing_fecha_cont;
    public $ing_puesto;
    public $ing_codigo;
    public $ing_situacion;

    public function __construct($args = [])
    {
        $this->ing_id = $args['ing_id'] ?? null;
        $this->ing_aspirante = $args['ing_aspirante'] ?? '';
        $this->ing_contingente = $args['ing_contingente'] ?? '';
        $this->ing_fecha_cont = date('Y-m-d');
        $this->ing_puesto = $args['ing_puesto'] ?? '';
        $this->ing_codigo = $args['ing_codigo'] ?? '';
        $this->ing_situacion = $args['ing_situacion'] ?? '1';
    }
}
