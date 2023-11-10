<?php

namespace Model;

class Resultado extends ActiveRecord{
    protected static $tabla = 'cont_resultados';
    protected static $columnasDB = ['res_aspirante','res_nota','res_evaluacion','res_situacion'];
    protected static $idTabla = 'res_id';

    public $res_id;
    public $res_aspirante;
    public $res_nota;
    public $res_evaluacion;
    public $res_situacion;

    public function __construct($args = [])
    {
        $this->res_id = $args['res_id'] ?? null;
        $this->res_aspirante = $args['res_aspirante'] ?? '';
        $this->res_nota = $args['res_nota'] ?? '';
        $this->res_evaluacion = $args['res_evaluacion'] ?? '';
        $this->res_situacion = $args['res_situacion'] ?? '1';
    }
}
