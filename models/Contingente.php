<?php

namespace Model;

class Contingente extends ActiveRecord{
    protected static $tabla = 'contingentes';
    protected static $columnasDB = ['cont_nombre','cont_fecha_pre','cont_fecha_inicio','cont_fecha_final','cont_fecha_post','cont_situacion'];
    protected static $idTabla = 'cont_id';

    public $cont_id;
    public $cont_nombre;
    public $cont_fecha_pre;
    public $cont_fecha_inicio;
    public $cont_fecha_final;
    public $cont_fecha_post;
    public $cont_situacion;

    public function __construct($args = [])
    {
        $this->cont_id = $args['cont_id'] ?? null;
        $this->cont_nombre = $args['cont_nombre'] ?? '';
        $this->cont_fecha_pre = $args['cont_fecha_pre'] ?? '';
        $this->cont_fecha_inicio = $args['cont_fecha_inicio'] ?? '';
        $this->cont_fecha_final = $args['cont_fecha_final'] ?? '';
        $this->cont_fecha_post = $args['cont_fecha_post'] ?? '';
        $this->cont_situacion = $args['cont_situacion'] ?? '1';
    }
}
